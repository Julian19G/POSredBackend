<?php

namespace App\Http\Controllers;

use App\Models\Descuento;
use App\Models\Producto;
use App\Models\Categoria;
use Illuminate\Http\Request;

class DescuentoController extends Controller
{
    /**
     * Mostrar lista de descuentos
     */
    public function index()
    {
        $descuentos = Descuento::withCount(['productos', 'categorias'])
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('descuentos.index', compact('descuentos'));
    }

    /**
     * Formulario para crear descuento
     */
    public function create()
    {
        $productos = Producto::activos()->get();
        $categorias = Categoria::all();

        return view('descuentos.create', compact('productos', 'categorias'));
    }

    /**
     * Guardar nuevo descuento
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre'             => 'required|string|max:255',
            'codigo'             => 'nullable|string|unique:descuentos,codigo',
            'tipo'               => 'required|in:porcentaje,fijo',
            'valor'              => 'required|numeric|min:0',
            'fecha_inicio'       => 'required|date',
            'fecha_fin'          => 'required|date|after:fecha_inicio',
            'uso_maximo'         => 'nullable|integer|min:0',
            'uso_cliente_maximo' => 'nullable|integer|min:0',
            'productos'          => 'array',
            'productos.*'        => 'integer|exists:productos,id',
            'categorias'         => 'array',
            'categorias.*'       => 'integer|exists:categorias,id',
        ]);

        $esManual = $request->boolean('aplicable_manual');

        if ($esManual && empty($validated['codigo'])) {
            return back()
                ->withErrors(['codigo' => 'El código es obligatorio para descuentos manuales.'])
                ->withInput();
        }

        if (!$esManual && empty($request->input('productos')) && empty($request->input('categorias'))) {
            return back()
                ->withErrors(['productos' => 'Selecciona al menos un producto o categoría, o marca el descuento como manual.'])
                ->withInput();
        }

        $descuento = Descuento::create([
            ...$validated,
            'activo'           => $request->boolean('activo'),
            'aplicable_manual' => $esManual,
        ]);

        $descuento->productos()->sync($request->input('productos', []));
        $descuento->categorias()->sync($request->input('categorias', []));

        return redirect()->route('descuentos.show', $descuento)
            ->with('success', "Descuento '{$descuento->nombre}' creado exitosamente.");
    }

    /**
     * Ver detalles del descuento
     */
    public function show(Descuento $descuento)
    {
        $descuento->load(['productos', 'categorias']);

        return view('descuentos.show', compact('descuento'));
    }

    /**
     * Formulario para editar descuento
     */
    public function edit(Descuento $descuento)
    {
        $descuento->load(['productos', 'categorias']);
        $productos = Producto::activos()->get();
        $categorias = Categoria::all();

        $productosSeleccionados = $descuento->productos->pluck('id')->toArray();
        $categoriasSeleccionadas = $descuento->categorias->pluck('id')->toArray();

        return view('descuentos.edit', compact('descuento', 'productos', 'categorias', 'productosSeleccionados', 'categoriasSeleccionadas'));
    }

    /**
     * Guardar cambios del descuento
     */
    public function update(Request $request, Descuento $descuento)
    {
        $validated = $request->validate([
            'nombre'             => 'required|string|max:255',
            'codigo'             => 'nullable|string|unique:descuentos,codigo,'.$descuento->id,
            'tipo'               => 'required|in:porcentaje,fijo',
            'valor'              => 'required|numeric|min:0',
            'fecha_inicio'       => 'required|date',
            'fecha_fin'          => 'required|date|after:fecha_inicio',
            'uso_maximo'         => 'nullable|integer|min:0',
            'uso_cliente_maximo' => 'nullable|integer|min:0',
            'productos'          => 'array',
            'productos.*'        => 'integer|exists:productos,id',
            'categorias'         => 'array',
            'categorias.*'       => 'integer|exists:categorias,id',
        ]);

        $esManual = $request->boolean('aplicable_manual');

        if ($esManual && empty($validated['codigo'])) {
            return back()
                ->withErrors(['codigo' => 'El código es obligatorio para descuentos manuales.'])
                ->withInput();
        }

        if (!$esManual && empty($request->input('productos')) && empty($request->input('categorias'))) {
            return back()
                ->withErrors(['productos' => 'Selecciona al menos un producto o categoría, o marca el descuento como manual.'])
                ->withInput();
        }

        $descuento->update([
            ...$validated,
            'activo'           => $request->boolean('activo'),
            'aplicable_manual' => $esManual,
        ]);

        $descuento->productos()->sync($request->input('productos', []));
        $descuento->categorias()->sync($request->input('categorias', []));

        return redirect()->route('descuentos.show', $descuento)
            ->with('success', "Descuento '{$descuento->nombre}' actualizado exitosamente.");
    }

    /**
     * Eliminar descuento
     */
    public function destroy(Descuento $descuento)
    {
        $nombre = $descuento->nombre;
        $descuento->delete();

        return redirect()->route('descuentos.index')
            ->with('success', "Descuento '{$nombre}' eliminado exitosamente.");
    }

    public function apiPorCategoria($categoriaId)
{
    $ahora = now();

    $descuentosCategoria = Descuento::whereHas('categorias', fn($q) => $q->where('categorias.id', $categoriaId))
        ->where('activo', true)
        ->where('fecha_inicio', '<=', $ahora)
        ->where('fecha_fin', '>=', $ahora)
        ->get();

    $productos = Producto::where('categoria_id', $categoriaId)
        ->activos()
        ->with('variantes')
        ->get()
        ->map(function ($producto) {
            $descuento = $producto->mejorDescuento();

            $variantes = $producto->variantes->map(function ($v) use ($producto, $descuento) {
                return [
                    'id' => $v->id,
                    'nombre' => $v->nombre,
                    'precio_original' => (float) $v->precio,
                    'precio_final' => $descuento
                        ? $producto->precioConDescuento($v->precio)
                        : (float) $v->precio,
                    'stock' => (int) $v->stock,
                ];
            });

            return [
                'id' => $producto->id,
                'nombre' => $producto->nombre,
                'descripcion' => $producto->descripcion,
                'imagen' => $producto->imagen,
                'precio_desde' => (float) $variantes->min('precio_original'),
                'precio_desde_final' => (float) $variantes->min('precio_final'),
                'descuento' => $descuento ? [
                    'valor' => (float) $descuento->valor,
                    'tipo' => $descuento->tipo,
                    'porcentaje' => $descuento->tipo === 'porcentaje' ? (int) $descuento->valor : null,
                    'nombre' => $descuento->nombre,
                ] : null,
                'variantes' => $variantes,
            ];
        });

    return response()->json([
        'categoria_id' => $categoriaId,
        'descuentos_globales' => $descuentosCategoria,
        'productos' => $productos,
    ]);
}

public function apiProductos(Request $request)
{
    $productoIds = $request->input('productos', []);

    if (empty($productoIds)) {
        return response()->json(['error' => 'No products specified'], 400);
    }

    $productos = Producto::whereIn('id', $productoIds)
        ->with('variantes')
        ->get()
        ->mapWithKeys(function ($producto) {
            $descuento = $producto->mejorDescuento();

            $variantes = $producto->variantes->map(function ($v) use ($producto, $descuento) {
                return [
                    'id' => $v->id,
                    'precio_original' => (float) $v->precio,
                    'precio_final' => $descuento
                        ? $producto->precioConDescuento($v->precio)
                        : (float) $v->precio,
                ];
            });

            return [$producto->id => [
                'descuento' => $descuento ? [
                    'valor' => (float) $descuento->valor,
                    'tipo' => $descuento->tipo,
                    'porcentaje' => $descuento->tipo === 'porcentaje' ? (int) $descuento->valor : null,
                    'nombre' => $descuento->nombre,
                ] : null,
                'variantes' => $variantes,
            ]];
        });

    return response()->json(['productos' => $productos]);
}


public function apiActivos()
{
    $ahora = now();

    $descuentos = Descuento::where('activo', true)
        ->where('aplicable_manual', false)
        ->where('fecha_inicio', '<=', $ahora)
        ->where('fecha_fin', '>=', $ahora)
        ->with(['productos:id', 'categorias:id'])
        ->get();

    $productoIds  = $descuentos->pluck('productos')->flatten()->pluck('id');
    $categoriaIds = $descuentos->pluck('categorias')->flatten()->pluck('id');

    $productos = Producto::activos()
        ->where(function ($q) use ($productoIds, $categoriaIds) {
            $q->whereIn('id', $productoIds)
              ->orWhereIn('categoria_id', $categoriaIds);
        })
        ->with('variantes')
        ->get();

    $resultado = $productos->map(function ($producto) {
        $descuento = $producto->mejorDescuento();
        if (!$descuento || $producto->variantes->isEmpty()) return null;

        $precioOriginal = (float) $producto->variantes->min('precio');
        $precioFinal    = (float) $producto->precioConDescuento($precioOriginal);
        $porcentaje     = $descuento->tipo === 'porcentaje'
            ? (int) $descuento->valor
            : ($precioOriginal > 0 ? round((1 - $precioFinal / $precioOriginal) * 100) : 0);

        return [
            'id'              => $producto->id,
            'nombre'          => $producto->nombre,
            'imagen'          => $producto->imagen ? asset('storage/' . $producto->imagen) : null,
            'precioOriginal'  => $precioOriginal,
            'precioDescuento' => $precioFinal,
            'porcentaje'      => $porcentaje,
        ];
    })->filter()->values();

    return response()->json($resultado);
}
}