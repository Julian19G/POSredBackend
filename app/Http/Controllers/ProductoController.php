<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Categoria; // ✅ Importamos el modelo
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    /**
     * Mostrar listado de productos.
     */
    public function index()
    {
        $productos = Producto::with('categoria')->get(); // Carga relación con categoría
        return view('productos.index', compact('productos'));
    }

    /**
     * Mostrar formulario de creación.
     */
    public function create()
    {
        $categorias = Categoria::all(); // ✅ ahora sí definimos la variable
        return view('productos.create', compact('categorias'));
    }

    /**
     * Guardar un nuevo producto.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'categoria_id' => 'nullable|exists:categorias,id',
            'imagen' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'activo' => 'boolean'
        ]);

        Producto::create($request->all());

        return redirect()->route('productos.index')->with('success', 'Producto creado exitosamente');
    }

    /**
     * Mostrar un producto específico.
     */
    public function show(Producto $producto)
    {
        return view('productos.show', compact('producto'));
    }

    /**
     * Mostrar formulario de edición.
     */
    public function edit(Producto $producto)
    {
        $categorias = Categoria::all(); // ✅ también lo incluimos en la edición
        return view('productos.edit', compact('producto', 'categorias'));
    }

    /**
     * Actualizar producto.
     */
    public function update(Request $request, Producto $producto)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'categoria_id' => 'nullable|exists:categorias,id',
            'imagen' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'activo' => 'boolean'
        ]);

        $producto->update($request->all());

        return redirect()->route('productos.index')->with('success', 'Producto actualizado correctamente');
    }

    /**
     * Eliminar un producto.
     */
    public function destroy(Producto $producto)
    {
        $producto->delete();
        return redirect()->route('productos.index')->with('success', 'Producto eliminado correctamente');
    }
}
