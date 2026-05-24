<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\Venta;
use App\Models\Domicilio;
use App\Models\Comprobante;
use App\Models\Comision;
use App\Models\Pago;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PedidoController extends Controller
{
    public function index()
    {
        $pedidos = Pedido::with(['venta.cliente', 'venta.detalles.producto', 'venta.domicilio'])
            ->latest()
            ->paginate(15);

        $estadosLabel = Pedido::estadosLabel();

        return view('pedidos.index', compact('pedidos', 'estadosLabel'));
    }

    public function show($id)
    {
        $pedido = Pedido::with([
            'venta.cliente',
            'venta.detalles',
            'venta.domicilio.zona',
            'venta.descuento',
            'venta.comision.vendedor',
            'comprobantes',
        ])->findOrFail($id);

        return view('pedidos.show', compact('pedido'));
    }

    /**
     * Actualizar estado del pedido + sincronizar venta y domicilio
     */
    public function actualizarEstado(Request $request, $id)
    {
        $request->validate([
            'estado'      => 'required|in:nuevo,en_preparacion,despachado,entregado,cancelado',
            'metodo_pago' => 'nullable|in:efectivo,transferencia,cripto,tarjeta,otro',
            'notas'       => 'nullable|string|max:500',
        ]);

        $pedido = Pedido::with('venta.domicilio')->findOrFail($id);

        DB::transaction(function () use ($pedido, $request) {

            $nuevoEstado = $request->estado;

            // — Timestamps automáticos según estado —
            match ($nuevoEstado) {
                'en_preparacion' => $pedido->fecha_preparacion = now(),
                'despachado'     => $pedido->fecha_despacho    = now(),
                'entregado'      => $pedido->fecha_entrega     = now(),
                'cancelado'      => $pedido->fecha_cancelacion = now(),
                default          => null,
            };

            $pedido->estado = $nuevoEstado;

            if ($request->filled('metodo_pago')) {
                $pedido->metodo_pago = $request->metodo_pago;
            }

            if ($request->filled('notas')) {
                $pedido->notas = $request->notas;
            }

            $pedido->save();

            // — Sincronizar estado de la VENTA —
            $estadoVenta = match ($nuevoEstado) {
                'entregado' => 'pagada',
                'cancelado' => 'cancelada',
                default     => 'pendiente',
            };
            $pedido->venta->update(['estado' => $estadoVenta]);

            // — Sincronizar estado_pago del pedido si se entregó —
            if ($nuevoEstado === 'entregado') {
                $pedido->update(['estado_pago' => 'pagado']);
            }

            // — Anular comisión si el pedido se cancela —
            if ($nuevoEstado === 'cancelado') {
                $comision = $pedido->venta->comision;
                if ($comision && $comision->estado === 'pendiente') {
                    $comision->update(['estado' => 'anulada']);
                }
            }

            // — Sincronizar estado del DOMICILIO (si existe) —
            if ($pedido->venta->domicilio) {
                $estadoDomicilio = match ($nuevoEstado) {
                    'despachado' => 'enviado',
                    'entregado'  => 'entregado',
                    'cancelado'  => 'cancelado',
                    default      => $pedido->venta->domicilio->estado,
                };

                $pedido->venta->domicilio->update(['estado' => $estadoDomicilio]);
            }
        });

        return back()->with('success', '✅ Estado actualizado correctamente.');
    }

    /**
     * Registrar cobro directo (ej: efectivo en mano)
     */
    public function registrarPago(Request $request, $id)
    {
        $request->validate([
            'metodo_pago' => 'required|in:efectivo,transferencia,cripto,tarjeta,otro',
            'monto'       => 'nullable|numeric|min:0',
            'referencia'  => 'nullable|string|max:255',
        ]);

        $pedido = Pedido::with('venta')->findOrFail($id);

        DB::transaction(function () use ($pedido, $request) {
            $monto = $request->filled('monto') ? (float) $request->monto : $pedido->venta->total;

            Pago::create([
                'venta_id'       => $pedido->venta_id,
                'registrado_por' => auth()->id(),
                'monto'          => $monto,
                'metodo'         => $request->metodo_pago,
                'estado'         => 'confirmado',
                'fecha_pago'     => now(),
                'referencia'     => $request->referencia,
            ]);

            $pedido->update([
                'metodo_pago' => $request->metodo_pago,
                'estado_pago' => 'pagado',
            ]);

            $pedido->venta->update(['estado' => 'pagada']);
        });

        return back()->with('success', '💰 Pago registrado.');
    }

    /**
     * Subir comprobante de pago
     */
    public function subirComprobante(Request $request, $id)
    {
        $request->validate([
            'tipo'       => 'required|in:efectivo,transferencia,cripto,tarjeta,otro',
            'monto'      => 'required|numeric|min:0',
            'referencia' => 'nullable|string|max:255',
            'imagen'     => 'nullable|image|mimes:jpg,jpeg,png,webp,pdf|max:5120',
            'notas'      => 'nullable|string|max:500',
        ]);

        $pedido = Pedido::findOrFail($id);

        $data = [
            'pedido_id'  => $pedido->id,
            'tipo'       => $request->tipo,
            'monto'      => $request->monto,
            'referencia' => $request->referencia,
            'notas'      => $request->notas,
            'estado'     => 'pendiente',
        ];

        if ($request->hasFile('imagen')) {
            $data['imagen_path'] = $request->file('imagen')
                ->store('comprobantes', 'public');
        }

        Comprobante::create($data);

        return back()->with('success', '📎 Comprobante registrado. Pendiente de verificación.');
    }

    /**
     * Verificar comprobante → marca venta como pagada
     */
    public function verificarComprobante(Request $request, $pedidoId, $comprobanteId)
    {
        $pedido      = Pedido::findOrFail($pedidoId);
        $comprobante = Comprobante::where('pedido_id', $pedido->id)->findOrFail($comprobanteId);

        DB::transaction(function () use ($comprobante, $pedido, $request) {
            $comprobante->update([
                'estado'         => 'verificado',
                'notas'          => $request->notas ?? $comprobante->notas,
                'verificado_en'  => now(),
            ]);

            Pago::create([
                'venta_id'       => $pedido->venta_id,
                'comprobante_id' => $comprobante->id,
                'registrado_por' => auth()->id(),
                'monto'          => $comprobante->monto,
                'metodo'         => $comprobante->tipo,
                'estado'         => 'confirmado',
                'fecha_pago'     => $comprobante->verificado_en,
                'referencia'     => $comprobante->referencia,
            ]);

            $pedido->update([
                'metodo_pago' => $comprobante->tipo,
                'estado_pago' => 'pagado',
            ]);

            $pedido->venta->update(['estado' => 'pagada']);
        });

        return back()->with('success', '✅ Comprobante verificado. Venta marcada como pagada.');
    }

    /**
     * Rechazar comprobante
     */
    public function rechazarComprobante(Request $request, $pedidoId, $comprobanteId)
    {
        $pedido      = Pedido::findOrFail($pedidoId);
        $comprobante = Comprobante::where('pedido_id', $pedido->id)->findOrFail($comprobanteId);

        $comprobante->update([
            'estado' => 'rechazado',
            'notas'  => $request->notas ?? $comprobante->notas,
        ]);

        return back()->with('success', '❌ Comprobante rechazado.');
    }
}