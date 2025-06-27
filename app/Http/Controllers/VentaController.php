<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\DetalleVenta;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VentaController extends Controller
{
    public function index()
    {
        $ventas = Venta::with('detalles.producto')->get();
        return view('ventas.index', compact('ventas'));
    }

    public function create()
    {
        $productos = Producto::all();
        return view('ventas.create', compact('productos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'productos' => 'required|array',
            'productos.*' => 'exists:productos,id',
            'cantidades' => 'required|array',
            'cantidades.*' => 'integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            $total = 0;
            foreach ($request->productos as $i => $producto_id) {
                $producto = Producto::findOrFail($producto_id);
                $cantidad = $request->cantidades[$i];
                $total += $producto->precio * $cantidad;
            }

            $venta = Venta::create(['total' => $total]);

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
            return redirect()->route('ventas.index')->with('success', 'Venta registrada con éxito.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function show($id)
    {
        $venta = Venta::with('detalles.producto')->findOrFail($id);
        return view('ventas.show', compact('venta'));
    }

    public function edit($id)
    {
        $venta = Venta::with('detalles')->findOrFail($id);
        $productos = Producto::all();
        return view('ventas.edit', compact('venta', 'productos'));
    }

    public function update(Request $request, $id)
    {
        $venta = Venta::find($id);
        if (!$venta) {
            return redirect()->route('ventas.index')->withErrors(['Venta no encontrada']);
        }

        $request->validate([
            'productos' => 'required|array',
            'productos.*' => 'exists:productos,id',
            'cantidades' => 'required|array',
            'cantidades.*' => 'integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            // Devolver stock de productos anteriores
            foreach ($venta->detalles as $detalle) {
                $producto = Producto::find($detalle->producto_id);
                if ($producto) {
                    $producto->increment('stock', $detalle->cantidad);
                }
            }

            $venta->detalles()->delete();

            $total = 0;
            foreach ($request->productos as $i => $producto_id) {
                $producto = Producto::findOrFail($producto_id);
                $cantidad = $request->cantidades[$i];
                $total += $producto->precio * $cantidad;
            }

            $venta->update(['total' => $total]);

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
            return redirect()->route('ventas.index')->with('success', 'Venta actualizada con éxito.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        $venta = Venta::find($id);
        if (!$venta) {
            return redirect()->route('ventas.index')->withErrors(['Venta no encontrada']);
        }

        DB::beginTransaction();
        try {
            foreach ($venta->detalles as $detalle) {
                $producto = Producto::find($detalle->producto_id);
                if ($producto) {
                    $producto->increment('stock', $detalle->cantidad);
                }
            }

            $venta->detalles()->delete();
            $venta->delete();

            DB::commit();
            return redirect()->route('ventas.index')->with('success', 'Venta eliminada correctamente.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
