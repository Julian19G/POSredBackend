<?php

namespace App\Http\Controllers;

use App\Models\Inventario;
use App\Models\Producto;
use Illuminate\Http\Request;

class InventarioController extends Controller
{
    /**
     * Mostrar lista de movimientos de inventario.
     */
    public function index()
    {
        $movimientos = Inventario::with('producto')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('inventario.index', compact('movimientos'));
    }

    /**
     * Formulario para crear un movimiento.
     */
    public function create()
    {
        $productos = Producto::all();
        return view('inventario.create', compact('productos'));
    }

    /**
     * Guardar un movimiento (entrada / salida).
     */
    public function store(Request $request)
    {
        $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'tipo' => 'required|in:entrada,salida',
            'cantidad' => 'required|integer|min:1',
            'descripcion' => 'nullable|string|max:255',
        ]);

        Inventario::create($request->all());

        return redirect()->route('inventario.index')
            ->with('success', 'Movimiento de inventario registrado correctamente.');
    }

    /**
     * Mostrar detalle de un movimiento específico.
     */
    public function show($id)
    {
        $movimiento = Inventario::with('producto')->findOrFail($id);
        return view('inventario.show', compact('movimiento'));
    }

    /**
     * Formulario de edición.
     */
    public function edit($id)
    {
        $movimiento = Inventario::findOrFail($id);
        $productos = Producto::all();

        return view('inventario.edit', compact('movimiento', 'productos'));
    }

    /**
     * Actualizar un movimiento de inventario.
     */
    public function update(Request $request, $id)
    {
        $movimiento = Inventario::findOrFail($id);

        $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'tipo' => 'required|in:entrada,salida',
            'cantidad' => 'required|integer|min:1',
            'descripcion' => 'nullable|string|max:255',
        ]);

        $movimiento->update($request->all());

        return redirect()->route('inventario.index')
            ->with('success', 'Movimiento actualizado correctamente.');
    }

    /**
     * Eliminar un movimiento.
     */
    public function destroy($id)
    {
        $movimiento = Inventario::findOrFail($id);
        $movimiento->delete();

        return redirect()->route('inventario.index')
            ->with('success', 'Movimiento eliminado correctamente.');
    }
}
