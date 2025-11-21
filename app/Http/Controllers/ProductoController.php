<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\sabor;
use App\Models\efecto;
use App\Models\color;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductoController extends Controller
{
public function index()
    {
        $productos = Producto::with(['sabores', 'colores', 'efectos'])->get();

        return view('productos.index', compact('productos'));
    }


public function create()
{
    $categorias = Categoria::all();
    $sabores = Sabor::all();
    $efectos = Efecto::all();
    $colores = Color::all();

    return view('productos.create', compact('categorias','sabores','efectos','colores'));
}


    public function store(Request $request)
    {
        $request->validate([
            'nombre'        => 'required|string|max:255',
            'descripcion'   => 'nullable|string',
            'precio'        => 'required|numeric|min:0',
            'stock'         => 'required|integer|min:0',
            'categoria_id'  => 'nullable|exists:categorias,id',
            'imagen'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'activo'        => 'nullable|boolean',

            // 🔥 Validación relaciones many-to-many
            'sabores' => 'array|nullable',
            'sabores.*' => 'exists:sabores,id',

            'efectos' => 'array|nullable',
            'efectos.*' => 'exists:efectos,id',

            'colores' => 'array|nullable',
            'colores.*' => 'exists:colores,id',
        ]);

        // Datos seguros
        $data = $request->only([
            'nombre', 'descripcion', 'precio', 'stock', 'categoria_id'
        ]);

        $data['activo'] = $request->has('activo');

        // Imagen
        if ($request->hasFile('imagen')) {
            $data['imagen'] = $request->file('imagen')->store('productos', 'public');
        }

        // Crear producto
        $producto = Producto::create($data);

        // 🔥 Sincronizar relaciones
        $producto->sabores()->sync($request->sabores ?? []);
        $producto->efectos()->sync($request->efectos ?? []);
        $producto->colores()->sync($request->colores ?? []);

        return redirect()->route('productos.index')
            ->with('success', 'Producto creado exitosamente');
    }

    public function show(Producto $producto)
    {
        return view('productos.show', compact('producto'));
    }

    public function edit(Producto $producto)
    {
        $categorias = Categoria::all();
        $sabores    = Sabor::all();
        $efectos    = Efecto::all();
        $colores    = Color::all();

        return view('productos.edit', compact(
            'producto',
            'categorias',
            'sabores',
            'efectos',
            'colores'
        ));
    }


    public function update(Request $request, Producto $producto)
    {
        $request->validate([
            'nombre'        => 'required|string|max:255',
            'descripcion'   => 'nullable|string',
            'precio'        => 'required|numeric|min:0',
            'stock'         => 'required|integer|min:0',
            'categoria_id'  => 'nullable|exists:categorias,id',
            'imagen'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'activo'        => 'nullable|boolean',

            // 🔥 Validación relaciones
            'sabores' => 'array|nullable',
            'sabores.*' => 'exists:sabores,id',

            'efectos' => 'array|nullable',
            'efectos.*' => 'exists:efectos,id',

            'colores' => 'array|nullable',
            'colores.*' => 'exists:colores,id',
        ]);

        $data = $request->only([
            'nombre', 'descripcion', 'precio', 'stock', 'categoria_id'
        ]);

        $data['activo'] = $request->has('activo');

        // Imagen actualizada
        if ($request->hasFile('imagen')) {

            if ($producto->imagen && Storage::disk('public')->exists($producto->imagen)) {
                Storage::disk('public')->delete($producto->imagen);
            }

            $data['imagen'] = $request->file('imagen')->store('productos', 'public');
        }

        $producto->update($data);

        // 🔥 Sync relaciones M:N
        $producto->sabores()->sync($request->sabores ?? []);
        $producto->efectos()->sync($request->efectos ?? []);
        $producto->colores()->sync($request->colores ?? []);

        return redirect()->route('productos.index')
            ->with('success', 'Producto actualizado correctamente');
    }

    public function destroy(Producto $producto)
    {
        if ($producto->imagen && Storage::disk('public')->exists($producto->imagen)) {
            Storage::disk('public')->delete($producto->imagen);
        }

        $producto->delete();

        return redirect()->route('productos.index')
            ->with('success', 'Producto eliminado correctamente');
    }
}
