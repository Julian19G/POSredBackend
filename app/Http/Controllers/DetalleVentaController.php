<?php

namespace App\Http\Controllers;

use App\Models\DetalleVenta;
use App\Models\Producto;
use App\Models\Venta;
use Illuminate\Http\Request;

class DetalleVentaController extends Controller
{
    // Mostrar formulario para crear nuevo detalle de venta
    public function create()
    {
        $ventas = Venta::all();          // Para seleccionar la venta a la que pertenece el detalle
        $productos = Producto::all();    // Para seleccionar el producto

        return view('detalle_ventas.create', compact('ventas', 'productos'));
    }

    // Guardar nuevo detalle de venta
    public function store(Request $request)
    {
        $request->validate([
            'venta_id' => 'required|exists:ventas,id',
            'producto_id' => 'required|exists:productos,id',
            'cantidad' => 'required|integer|min:1',
            'precio_unitario' => 'required|numeric|min:0',
            'subtotal' => 'required|numeric|min:0',
        ]);

        DetalleVenta::create($request->all());

        return redirect()->route('detalle_ventas.index')->with('success', 'Detalle de venta creado correctamente.');
    }

    // Listar todos los detalles de venta
    public function index()
    {
        $detalleVentas = DetalleVenta::with(['venta', 'producto'])->paginate(10);
        return view('detalle_ventas.index', compact('detalleVentas'));
    }

    // Mostrar formulario para editar (opcional)
    public function edit($id)
    {
        $detalleVenta = DetalleVenta::findOrFail($id);
        $ventas = Venta::all();
        $productos = Producto::all();

        return view('detalle_ventas.edit', compact('detalleVenta', 'ventas', 'productos'));
    }

    // Actualizar detalle de venta (opcional)
    public function update(Request $request, $id)
    {
        $request->validate([
            'venta_id' => 'required|exists:ventas,id',
            'producto_id' => 'required|exists:productos,id',
            'cantidad' => 'required|integer|min:1',
            'precio_unitario' => 'required|numeric|min:0',
            'subtotal' => 'required|numeric|min:0',
        ]);

        $detalleVenta = DetalleVenta::findOrFail($id);
        $detalleVenta->update($request->all());

        return redirect()->route('detalle_ventas.index')->with('success', 'Detalle de venta actualizado correctamente.');
    }

    // Eliminar detalle de venta
    public function destroy($id)
    {
        $detalleVenta = DetalleVenta::findOrFail($id);
        $detalleVenta->delete();

        return redirect()->route('detalle_ventas.index')->with('success', 'Detalle de venta eliminado correctamente.');
    }
}
