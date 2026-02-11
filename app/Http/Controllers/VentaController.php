<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\DetalleVenta;
use App\Models\Producto;
use App\Models\Cliente;
use App\Models\Descuento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class VentaController extends Controller
{
    /* =========================
     * LISTADO
     * ========================= */
    public function index()
    {
        $ventas = Venta::with(['cliente', 'detalles.producto'])
            ->latest()
            ->paginate(10);

        return view('ventas.index', compact('ventas'));
    }

    /* =========================
     * FORM CREAR
     * ========================= */
    public function create()
    {
        return view('ventas.create', [
            'productos' => Producto::where('stock', '>', 0)->get(),
            'clientes'  => Cliente::all(),
        ]);
    }

    /* =========================
     * GUARDAR
     * ========================= */
    public function store(Request $request)
    {
        $this->validarVenta($request);

        // 🔥 Resolver descuentos (regla de negocio)
        $descuento = $this->resolverDescuento($request);

        DB::beginTransaction();
        try {
            $subtotal = $this->calcularSubtotal($request);

            $venta = Venta::create([
                'cliente_id'       => $request->cliente_id,
                'subtotal'         => $subtotal,
                'descuento_id'     => $descuento?->id,
                'descuento_manual' => $request->descuento_manual ?? 0,
                'motivo_descuento' => $request->motivo_descuento,
                'envio'            => $request->boolean('envio'),
                'estado'           => 'pendiente',
            ]);

            $this->registrarDetalles($venta, $request);

            DB::commit();
            return redirect()->route('ventas.index')
                ->with('success', 'Venta registrada con éxito.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /* =========================
     * VER
     * ========================= */
    public function show($id)
    {
        $venta = Venta::with(['cliente', 'detalles.producto'])->findOrFail($id);
        return view('ventas.show', compact('venta'));
    }

    /* =========================
     * FORM EDITAR
     * ========================= */
    public function edit($id)
    {
        return view('ventas.edit', [
            'venta'     => Venta::with('detalles.producto')->findOrFail($id),
            'productos' => Producto::all(),
            'clientes'  => Cliente::all(),
        ]);
    }

    /* =========================
     * ACTUALIZAR
     * ========================= */
    public function update(Request $request, $id)
    {
        $venta = Venta::findOrFail($id);
        $this->validarVenta($request);

        // 🔥 Resolver descuentos
        $descuento = $this->resolverDescuento($request);

        DB::beginTransaction();
        try {
            // Revertir stock
            foreach ($venta->detalles as $detalle) {
                $detalle->producto->increment('stock', $detalle->cantidad);
            }

            $venta->detalles()->delete();

            $subtotal = $this->calcularSubtotal($request);

            $venta->update([
                'cliente_id'       => $request->cliente_id,
                'subtotal'         => $subtotal,
                'descuento_id'     => $descuento?->id,
                'descuento_manual' => $request->descuento_manual ?? 0,
                'motivo_descuento' => $request->motivo_descuento,
                'envio'            => $request->boolean('envio'),
                'estado'           => $request->estado ?? 'pendiente',
            ]);

            $this->registrarDetalles($venta, $request);

            DB::commit();
            return redirect()->route('ventas.index')
                ->with('success', 'Venta actualizada correctamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /* =========================
     * ELIMINAR
     * ========================= */
    public function destroy($id)
    {
        $venta = Venta::findOrFail($id);

        DB::beginTransaction();
        try {
            foreach ($venta->detalles as $detalle) {
                $detalle->producto->increment('stock', $detalle->cantidad);
            }

            $venta->detalles()->delete();
            $venta->delete();

            DB::commit();
            return back()->with('success', 'Venta eliminada.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /* =========================
     * MÉTODOS PRIVADOS
     * ========================= */

    private function validarVenta(Request $request): void
    {
        $request->validate([
            'cliente_id'       => 'required|exists:clientes,id',
            'productos'        => 'required|array|min:1',
            'productos.*'      => 'exists:productos,id',
            'cantidades'       => 'required|array|min:1',
            'cantidades.*'     => 'integer|min:1',
            'descuento_id'     => 'nullable|exists:descuentos,id',
            'descuento_manual' => 'nullable|numeric|min:0',
            'motivo_descuento' => 'nullable|string|max:255',
            'envio'            => 'boolean',
            'estado'           => 'nullable|in:pendiente,pagada,cancelada',
        ]);
    }

    private function resolverDescuento(Request $request): ?Descuento
    {
        $descuento = null;

        if ($request->filled('descuento_id')) {
            $descuento = Descuento::activos()->findOrFail($request->descuento_id);

            if ($descuento->aplicable_manual && empty($request->motivo_descuento)) {
                throw ValidationException::withMessages([
                    'motivo_descuento' => 'Debes justificar este descuento.',
                ]);
            }
        }

        if (($request->descuento_manual ?? 0) > 0 && empty($request->motivo_descuento)) {
            throw ValidationException::withMessages([
                'motivo_descuento' => 'Debes justificar el descuento aplicado.',
            ]);
        }

        return $descuento;
    }

    private function calcularSubtotal(Request $request): float
    {
        $subtotal = 0;

        foreach ($request->productos as $i => $producto_id) {
            $producto = Producto::findOrFail($producto_id);
            $subtotal += $producto->precio * $request->cantidades[$i];
        }

        return $subtotal;
    }

    private function registrarDetalles(Venta $venta, Request $request): void
    {
        foreach ($request->productos as $i => $producto_id) {
            $producto = Producto::findOrFail($producto_id);
            $cantidad = $request->cantidades[$i];

            DetalleVenta::create([
                'venta_id'        => $venta->id,
                'producto_id'     => $producto_id,
                'nombre_producto' => $producto->nombre,
                'codigo_producto' => $producto->codigo,
                'cantidad'        => $cantidad,
                'precio_unitario' => $producto->precio,
                'subtotal'        => $producto->precio * $cantidad,
            ]);

            $producto->decrement('stock', $cantidad);
        }
    }
}
