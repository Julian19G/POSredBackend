<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\Venta;
use App\Models\Domicilio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            'venta.detalles.producto',
            'venta.domicilio',
            'venta.descuento',
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
     * Registrar el método de pago por separado
     */
    public function registrarPago(Request $request, $id)
    {
        $request->validate([
            'metodo_pago' => 'required|in:efectivo,transferencia,cripto,tarjeta,otro',
        ]);

        $pedido = Pedido::findOrFail($id);
        $pedido->update([
            'metodo_pago' => $request->metodo_pago,
            'estado_pago' => 'pagado',
        ]);

        $pedido->venta->update(['estado' => 'pagada']);

        return back()->with('success', '💰 Pago registrado.');
    }
}