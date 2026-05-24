<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Variante;
use App\Models\Sabor;
use App\Models\Efecto;
use App\Models\Color;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductoController extends Controller
{
    public function index(Request $request)
    {
        $esAdmin = auth()->user()->isAdmin();

        if ($esAdmin) {
            Producto::where('stock', '<=', 0)->where('activo', true)->update(['activo' => false]);
            Producto::where('stock', '>', 0)->where('activo', false)->update(['activo' => true]);
        }

        $query = Producto::with(['categoria', 'sabores', 'colores', 'efectos', 'variantes']);

        // Vendedores solo ven productos activos
        if (!$esAdmin) {
            $query->where('activo', true);
        }

        if ($request->filled('buscar')) {
            $query->where('nombre', 'like', '%' . $request->buscar . '%');
        }
        if ($request->filled('categoria_id')) {
            $query->where('categoria_id', $request->categoria_id);
        }
        if ($esAdmin && $request->filled('activo') && $request->activo !== '') {
            $query->where('activo', $request->activo);
        }
        if ($esAdmin && $request->has('stock_bajo')) {
            $query->where('stock', '<=', 10);
        }

        $productos  = $query->paginate(20)->withQueryString();
        $categorias = Categoria::orderBy('nombre')->get();

        return view('productos.index', compact('productos', 'categorias', 'esAdmin'));
    }

    public function create()
    {
        abort_if(!auth()->user()->isAdmin(), 403, 'Solo el administrador puede crear productos.');
        $categorias = Categoria::all();
        $sabores    = Sabor::all();
        $efectos    = Efecto::all();
        $colores    = Color::all();

        return view('productos.create', compact('categorias', 'sabores', 'efectos', 'colores'));
    }

    public function store(Request $request)
    {
        abort_if(!auth()->user()->isAdmin(), 403, 'Solo el administrador puede crear productos.');
        $request->validate([
            'nombre'       => 'required|string|max:255',
            'descripcion'  => 'nullable|string',
            // ❌ precio ya no va aquí
            'stock'        => 'required|integer|min:0',
            'categoria_id' => 'nullable|exists:categorias,id',
            'imagen'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'activo'       => 'nullable|boolean',

            'sabores'   => 'array|nullable',
            'sabores.*' => 'exists:sabores,id',
            'efectos'   => 'array|nullable',
            'efectos.*' => 'exists:efectos,id',
            'colores'   => 'array|nullable',
            'colores.*' => 'exists:colores,id',

            // ✅ Validación de variantes
            'variantes'                          => 'nullable|array',
            'variantes.*.nombre'                 => 'required|string|max:100',
            'variantes.*.cantidad_por_variante'  => 'required|integer|min:1',
            'variantes.*.precio'                 => 'required|numeric|min:0',
            'variantes.*.stock'                  => 'required|integer|min:0',
        ]);

        $data = $request->only(['nombre', 'descripcion', 'stock', 'categoria_id']);
        $data['activo'] = $request->has('activo');

        if ($request->hasFile('imagen')) {
            $data['imagen'] = $request->file('imagen')->store('productos', 'public');
        }

        $producto = Producto::create($data);

        // Relaciones M:N
        $producto->sabores()->sync($request->sabores ?? []);
        $producto->efectos()->sync($request->efectos ?? []);
        $producto->colores()->sync($request->colores ?? []);

        // ✅ Crear variantes
        if ($request->filled('variantes')) {
            foreach ($request->variantes as $v) {
                $producto->variantes()->create([
                    'nombre'                => $v['nombre'],
                    'cantidad_por_variante' => $v['cantidad_por_variante'],
                    'precio'                => $v['precio'],
                    'stock'                 => $v['stock'],
                    'activo'                => true,
                ]);
            }
        }

        return redirect()->route('productos.index')
            ->with('success', 'Producto creado exitosamente');
    }

    public function show(Producto $producto)
    {
        $producto->load(['categoria', 'sabores', 'colores', 'efectos', 'variantes']);
        return view('productos.show', compact('producto'));
    }

    public function edit(Producto $producto)
    {
        abort_if(!auth()->user()->isAdmin(), 403, 'Solo el administrador puede editar productos.');
        $categorias = Categoria::all();
        $sabores    = Sabor::all();
        $efectos    = Efecto::all();
        $colores    = Color::all();

        $producto->load('variantes');

        return view('productos.edit', compact(
            'producto', 'categorias', 'sabores', 'efectos', 'colores'
        ));
    }

    public function update(Request $request, Producto $producto)
    {
        abort_if(!auth()->user()->isAdmin(), 403, 'Solo el administrador puede editar productos.');
        $request->validate([
            'nombre'       => 'required|string|max:255',
            'descripcion'  => 'nullable|string',
            'stock'        => 'required|integer|min:0',
            'categoria_id' => 'nullable|exists:categorias,id',
            'imagen'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'activo'       => 'nullable|boolean',

            'sabores'   => 'array|nullable',
            'sabores.*' => 'exists:sabores,id',
            'efectos'   => 'array|nullable',
            'efectos.*' => 'exists:efectos,id',
            'colores'   => 'array|nullable',
            'colores.*' => 'exists:colores,id',

            'variantes'                          => 'nullable|array',
            'variantes.*.id'                     => 'nullable|exists:variantes,id',
            'variantes.*.nombre'                 => 'required|string|max:100',
            'variantes.*.cantidad_por_variante'  => 'required|integer|min:1',
            'variantes.*.precio'                 => 'required|numeric|min:0',
            'variantes.*.stock'                  => 'required|integer|min:0',
        ]);

        $data = $request->only(['nombre', 'descripcion', 'stock', 'categoria_id']);
        $data['activo'] = $request->has('activo');

        if ($request->hasFile('imagen')) {
            if ($producto->imagen && Storage::disk('public')->exists($producto->imagen)) {
                Storage::disk('public')->delete($producto->imagen);
            }
            $data['imagen'] = $request->file('imagen')->store('productos', 'public');
        }

        $producto->update($data);

        $producto->sabores()->sync($request->sabores ?? []);
        $producto->efectos()->sync($request->efectos ?? []);
        $producto->colores()->sync($request->colores ?? []);

        // ✅ Sincronizar variantes
        $idsEnviados = [];

        foreach ($request->variantes ?? [] as $v) {
            if (!empty($v['id'])) {
                // Actualizar existente
                $variante = Variante::find($v['id']);
                if ($variante && $variante->producto_id === $producto->id) {
                    $variante->update([
                        'nombre'                => $v['nombre'],
                        'cantidad_por_variante' => $v['cantidad_por_variante'],
                        'precio'                => $v['precio'],
                        'stock'                 => $v['stock'],
                    ]);
                    $idsEnviados[] = $variante->id;
                }
            } else {
                // Crear nueva
                $nueva = $producto->variantes()->create([
                    'nombre'                => $v['nombre'],
                    'cantidad_por_variante' => $v['cantidad_por_variante'],
                    'precio'                => $v['precio'],
                    'stock'                 => $v['stock'],
                    'activo'                => true,
                ]);
                $idsEnviados[] = $nueva->id;
            }
        }

        // Eliminar las que el usuario borró del formulario
        $producto->variantes()
                 ->whereNotIn('id', $idsEnviados)
                 ->delete();

        return redirect()->route('productos.index')
            ->with('success', 'Producto actualizado correctamente');
    }

    public function destroy(Producto $producto)
    {
        abort_if(!auth()->user()->isAdmin(), 403, 'Solo el administrador puede eliminar productos.');
        if ($producto->imagen && Storage::disk('public')->exists($producto->imagen)) {
            Storage::disk('public')->delete($producto->imagen);
        }

        // Las variantes se eliminan solas por cascade en la migración
        $producto->delete();

        return redirect()->route('productos.index')
            ->with('success', 'Producto eliminado correctamente');
    }
}