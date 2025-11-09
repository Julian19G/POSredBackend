<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\DetalleVenta;
use App\Models\Producto;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VentaController extends Controller
{
    /**
     * Muestra todas las ventas
     */
    public function index()
    {
        $ventas = Venta::with(['cliente', 'detalles.producto'])->latest()->get();
        return view('ventas.index', compact('ventas'));
    }

    /**
     * Muestra el formulario de creación de una venta
     */
    public function create()
    {
        $productos = Producto::where('stock', '>', 0)->get();
        $clientes = Cliente::all();
        return view('ventas.create', compact('productos', 'clientes'));
    }

    /**
     * Guarda una nueva venta
     */
    public function store(Request $request)
    {
        $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'productos' => 'required|array',
            'productos.*' => 'exists:productos,id',
            'cantidades' => 'required|array',
            'cantidades.*' => 'integer|min:1',
            'descuento_manual' => 'nullable|numeric|min:0',
            'motivo_descuento' => 'nullable|string|max:255',
            'costo_envio' => 'nullable|numeric|min:0',
            'direccion_envio' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            $subtotal = 0;

            // Calcular subtotal
            foreach ($request->productos as $i => $producto_id) {
                $producto = Producto::findOrFail($producto_id);
                $cantidad = $request->cantidades[$i];
                $subtotal += $producto->precio * $cantidad;
            }

            // Crear venta principal
            $venta = Venta::create([
                'cliente_id' => $request->cliente_id,
                'subtotal' => $subtotal,
                'descuento_manual' => $request->descuento_manual ?? 0,
                'motivo_descuento' => $request->motivo_descuento,
                'costo_envio' => $request->costo_envio ?? 0,
                'direccion_envio' => $request->direccion_envio,
                'estado' => 'pendiente',
                'total' => 0, // Se recalcula en el modelo automáticamente
            ]);

            // Registrar detalles
            foreach ($request->productos as $i => $producto_id) {
                $producto = Producto::findOrFail($producto_id);
                $cantidad = $request->cantidades[$i];
                $subtotal = $producto->precio * $cantidad;

                DetalleVenta::create([
                    'venta_id' => $venta->id,
                    'producto_id' => $producto_id,
                    'cantidad' => $cantidad,
                    'precio_unitario' => $producto->precio,
                    'subtotal' => $subtotal
                ]);

                // Actualizar stock
                $producto->decrement('stock', $cantidad);
            }

            DB::commit();
            return redirect()->route('ventas.index')->with('success', 'Venta registrada con éxito.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al registrar la venta: ' . $e->getMessage()]);
        }
    }

    /**
     * Muestra una venta específica
     */
    public function show($id)
    {
        $venta = Venta::with(['cliente', 'detalles.producto'])->findOrFail($id);
        return view('ventas.show', compact('venta'));
    }

    /**
     * Muestra el formulario para editar una venta
     */
    public function edit($id)
    {
        $venta = Venta::with('detalles')->findOrFail($id);
        $productos = Producto::all();
        $clientes = Cliente::all();
        return view('ventas.edit', compact('venta', 'productos', 'clientes'));
    }

    /**
     * Actualiza una venta existente
     */
    public function update(Request $request, $id)
    {
        $venta = Venta::findOrFail($id);

        $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'productos' => 'required|array',
            'productos.*' => 'exists:productos,id',
            'cantidades' => 'required|array',
            'cantidades.*' => 'integer|min:1',
            'descuento_manual' => 'nullable|numeric|min:0',
            'motivo_descuento' => 'nullable|string|max:255',
            'costo_envio' => 'nullable|numeric|min:0',
            'direccion_envio' => 'nullable|string|max:255',
            'estado' => 'in:pendiente,pagada,cancelada',
        ]);

        DB::beginTransaction();
        try {
            // Revertir stock
            foreach ($venta->detalles as $detalle) {
                $detalle->producto->increment('stock', $detalle->cantidad);
            }

            // Eliminar detalles antiguos
            $venta->detalles()->delete();

            // Calcular nuevo subtotal
            $subtotal = 0;
            foreach ($request->productos as $i => $producto_id) {
                $producto = Producto::findOrFail($producto_id);
                $cantidad = $request->cantidades[$i];
                $subtotal += $producto->precio * $cantidad;
            }

            // Actualizar venta principal
            $venta->update([
                'cliente_id' => $request->cliente_id,
                'subtotal' => $subtotal,
                'descuento_manual' => $request->descuento_manual ?? 0,
                'motivo_descuento' => $request->motivo_descuento,
                'costo_envio' => $request->costo_envio ?? 0,
                'direccion_envio' => $request->direccion_envio,
                'estado' => $request->estado ?? 'pendiente',
            ]);

            // Volver a agregar detalles
            foreach ($request->productos as $i => $producto_id) {
                $producto = Producto::findOrFail($producto_id);
                $cantidad = $request->cantidades[$i];
                $subtotal = $producto->precio * $cantidad;

                DetalleVenta::create([
                    'venta_id' => $venta->id,
                    'producto_id' => $producto_id,
                    'cantidad' => $cantidad,
                    'precio_unitario' => $producto->precio,
                    'subtotal' => $subtotal
                ]);

                $producto->decrement('stock', $cantidad);
            }

            DB::commit();
            return redirect()->route('ventas.index')->with('success', 'Venta actualizada correctamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al actualizar la venta: ' . $e->getMessage()]);
        }
    }

    /**
     * Elimina una venta
     */
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
            return redirect()->route('ventas.index')->with('success', 'Venta eliminada correctamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al eliminar la venta: ' . $e->getMessage()]);
        }
    }
}
