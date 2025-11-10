<?php

namespace App\Http\Controllers;

use App\Models\DetalleVenta;
use App\Models\Producto;
use App\Models\Venta;
use Illuminate\Http\Request;

class DetalleVentaController extends Controller
{
    /**
     * Mostrar lista de detalles de venta
     */
    public function index()
    {
        $detalleVentas = DetalleVenta::with(['venta', 'producto'])->orderByDesc('id')->paginate(10);
        return view('detalle_ventas.index', compact('detalleVentas'));
    }

    /**
     * Mostrar formulario para crear un nuevo detalle
     */
    public function create()
    {
        $ventas = Venta::all();
        $productos = Producto::all();

        return view('detalle_ventas.create', compact('ventas', 'productos'));
    }

    /**
     * Guardar un nuevo detalle de venta
     */
    public function store(Request $request)
    {
        $request->validate([
            'venta_id' => 'required|exists:ventas,id',
            'producto_id' => 'required|exists:productos,id',
            'cantidad' => 'required|integer|min:1',
            'precio_unitario' => 'nullable|numeric|min:0',
            'descuento_aplicado' => 'nullable|numeric|min:0',
            'impuesto' => 'nullable|numeric|min:0',
        ]);

        $producto = Producto::findOrFail($request->producto_id);

        // Cálculos automáticos
        $precioUnitario = $request->precio_unitario ?? $producto->precio;
        $descuento = $request->descuento_aplicado ?? 0;
        $impuesto = $request->impuesto ?? 0;
        $subtotal = ($precioUnitario * $request->cantidad) - $descuento + $impuesto;

        // Crear el registro
        DetalleVenta::create([
            'venta_id' => $request->venta_id,
            'producto_id' => $producto->id,
            'nombre_producto' => $producto->nombre,
            'codigo_producto' => $producto->codigo ?? null,
            'cantidad' => $request->cantidad,
            'precio_unitario' => $precioUnitario,
            'descuento_aplicado' => $descuento,
            'impuesto' => $impuesto,
            'subtotal' => $subtotal,
        ]);

        return redirect()->route('detalle_ventas.index')
            ->with('success', '✅ Detalle de venta creado correctamente.');
    }


    /**
 * Mostrar un detalle de venta específico
 */
public function show($id)
{
    $detalleVenta = DetalleVenta::with(['venta', 'producto'])->findOrFail($id);

    return view('detalle_ventas.show', compact('detalleVenta'));
}

    /**
     * Mostrar formulario para editar un detalle
     */
    public function edit($id)
    {
        $detalleVenta = DetalleVenta::findOrFail($id);
        $ventas = Venta::all();
        $productos = Producto::all();

        return view('detalle_ventas.edit', compact('detalleVenta', 'ventas', 'productos'));
    }

    /**
     * Actualizar un detalle de venta
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'venta_id' => 'required|exists:ventas,id',
            'producto_id' => 'required|exists:productos,id',
            'cantidad' => 'required|integer|min:1',
            'precio_unitario' => 'nullable|numeric|min:0',
            'descuento_aplicado' => 'nullable|numeric|min:0',
            'impuesto' => 'nullable|numeric|min:0',
        ]);

        $detalle = DetalleVenta::findOrFail($id);
        $producto = Producto::findOrFail($request->producto_id);

        // Recalcular valores
        $precioUnitario = $request->precio_unitario ?? $producto->precio;
        $descuento = $request->descuento_aplicado ?? 0;
        $impuesto = $request->impuesto ?? 0;
        $subtotal = ($precioUnitario * $request->cantidad) - $descuento + $impuesto;

        $detalle->update([
            'venta_id' => $request->venta_id,
            'producto_id' => $producto->id,
            'nombre_producto' => $producto->nombre,
            'codigo_producto' => $producto->codigo ?? null,
            'cantidad' => $request->cantidad,
            'precio_unitario' => $precioUnitario,
            'descuento_aplicado' => $descuento,
            'impuesto' => $impuesto,
            'subtotal' => $subtotal,
        ]);

        return redirect()->route('detalle_ventas.index')
            ->with('success', '✏️ Detalle de venta actualizado correctamente.');
    }

    /**
     * Eliminar un detalle de venta
     */
    public function destroy($id)
    {
        $detalleVenta = DetalleVenta::findOrFail($id);
        $detalleVenta->delete();

        return redirect()->route('detalle_ventas.index')
            ->with('success', '🗑️ Detalle de venta eliminado correctamente.');
    }
}
