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
    private function miVendedorId(): ?int
    {
        if (auth()->user()->isAdmin()) return null;
        return auth()->user()->vendedor?->id;
    }

    private function verificarAccesoPedido(Pedido $pedido): void
    {
        $vid = $this->miVendedorId();
        if ($vid !== null && $pedido->venta?->vendedor_id !== $vid) {
            abort(403, 'No tienes acceso a este pedido.');
        }
    }

    public function index()
    {
        $query = Pedido::with(['venta.cliente', 'venta.detalles.producto', 'venta.domicilio'])
            ->latest();

        $vid = $this->miVendedorId();
        if ($vid !== null) {
            $query->whereHas('venta', fn($q) => $q->where('vendedor_id', $vid));
        }

        $pedidos      = $query->paginate(15);
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

        $this->verificarAccesoPedido($pedido);

        return view('pedidos.show', compact('pedido'));
    }

    /**
     * Actualizar estado del pedido + sincronizar venta y domicilio
     */
    public function actualizarEstado(Request $request, $id)
    {
        $request->validate([
            'estado'          => 'required|in:nuevo,en_preparacion,despachado,entregado,cancelado',
            'metodo_pago'     => 'nullable|in:efectivo,transferencia,cripto,tarjeta,otro',
            'monto_pago'      => 'nullable|numeric|min:0',
            'referencia_pago' => 'nullable|string|max:255',
            'notas'           => 'nullable|string|max:500',
        ]);

        $pedido = Pedido::with('venta.domicilio', 'venta.pagos')->findOrFail($id);
        $this->verificarAccesoPedido($pedido);

        // — Validar: "entregado" requiere pago confirmado —
        if ($request->estado === 'entregado' && $pedido->estado_pago !== 'pagado') {
            if (!$request->filled('metodo_pago')) {
                return back()->withErrors([
                    'error' => 'No puedes marcar el pedido como entregado sin confirmar el método de pago.',
                ]);
            }
        }

        DB::transaction(function () use ($pedido, $request) {

            $nuevoEstado = $request->estado;

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

            // — Al entregar: registrar pago si aún no hay uno confirmado —
            if ($nuevoEstado === 'entregado') {
                $yaTienePago = $pedido->venta->pagos->where('estado', 'confirmado')->isNotEmpty();

                if (!$yaTienePago && $request->filled('metodo_pago')) {
                    Pago::create([
                        'venta_id'       => $pedido->venta_id,
                        'registrado_por' => auth()->id(),
                        'monto'          => $request->filled('monto_pago')
                                             ? (float) $request->monto_pago
                                             : $pedido->venta->total,
                        'metodo'         => $request->metodo_pago,
                        'estado'         => 'confirmado',
                        'fecha_pago'     => now(),
                        'referencia'     => $request->referencia_pago,
                    ]);
                }

                $pedido->update(['estado_pago' => 'pagado']);
                $pedido->venta->update(['estado' => 'pagada']);
            } elseif ($nuevoEstado === 'cancelado') {
                $pedido->venta->update(['estado' => 'cancelada']);

                $comision = $pedido->venta->comision;
                if ($comision && $comision->estado === 'pendiente') {
                    $comision->update(['estado' => 'anulada']);
                }
            } else {
                $pedido->venta->update(['estado' => 'pendiente']);
            }

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
        $this->verificarAccesoPedido($pedido);

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

        $pedido = Pedido::with('venta')->findOrFail($id);
        $this->verificarAccesoPedido($pedido);

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
        abort_if(!auth()->user()->isAdmin(), 403, 'Solo el administrador puede verificar comprobantes.');
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
        abort_if(!auth()->user()->isAdmin(), 403, 'Solo el administrador puede rechazar comprobantes.');
        $pedido      = Pedido::findOrFail($pedidoId);
        $comprobante = Comprobante::where('pedido_id', $pedido->id)->findOrFail($comprobanteId);

        $comprobante->update([
            'estado' => 'rechazado',
            'notas'  => $request->notas ?? $comprobante->notas,
        ]);

        return back()->with('success', '❌ Comprobante rechazado.');
    }
}