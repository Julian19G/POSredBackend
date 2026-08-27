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
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ProductoController extends Controller
{
    public function index(Request $request)
    {
        $esAdmin = auth()->user()->isAdmin();

        if ($esAdmin) {
            Producto::where('stock', '<=', 0)->where('activo', true)->update(['activo' => false]);
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
        $categorias     = Categoria::all();
        $sabores        = Sabor::all();
        $efectos        = Efecto::all();
        $colores        = Color::all();
        $presentaciones = \App\Models\Presentacion::activas()->orderBy('cantidad')->get();
        $tiposFlor      = \App\Models\TipoFlor::activos()->orderBy('nombre')->get();
        $floresId       = Categoria::where('slug', 'flores')->value('id');
        $productosPlantilla = Producto::with('variantes:id,producto_id,nombre,cantidad_por_variante,precio')
            ->whereHas('variantes')
            ->orderBy('nombre')
            ->get(['id', 'nombre']);
        $productosPlantillaData = $productosPlantilla->map(function ($producto) {
            return [
                'id' => $producto->id,
                'nombre' => $producto->nombre,
                'variantes' => $producto->variantes->map(function ($variante) {
                    return [
                        'nombre' => $variante->nombre,
                        'cantidad' => $variante->cantidad_por_variante,
                        'precio' => $variante->precio,
                    ];
                })->values(),
            ];
        })->values();

        return view('productos.create', compact(
            'categorias', 'sabores', 'efectos', 'colores',
            'presentaciones', 'tiposFlor', 'floresId', 'productosPlantilla',
            'productosPlantillaData'
        ));
    }

    public function store(Request $request)
    {
        abort_if(!auth()->user()->isAdmin(), 403, 'Solo el administrador puede crear productos.');

        // Descarta filas de variante completamente vacías antes de validar
        $this->limpiarVariantesVacias($request);

        $request->validate([
            'nombre'       => 'required|string|max:255',
            'descripcion'  => 'nullable|string',
            // ❌ precio ya no va aquí
            'stock'        => 'required|numeric|min:0',
            'categoria_id' => 'nullable|exists:categorias,id',
            'tipo_flor_id' => 'nullable|exists:tipos_flor,id',
            'imagen'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'activo'       => 'nullable|boolean',

            'sabores'   => 'array|nullable',
            'sabores.*' => 'exists:sabores,id',
            'efectos'   => 'array|nullable',
            'efectos.*' => 'exists:efectos,id',
            'colores'   => 'array|nullable',
            'colores.*' => 'exists:colores,id',

            // ✅ Validación de variantes (el stock por variante se calcula del stock total)
            'variantes'                          => 'nullable|array',
            'variantes.*.nombre'                 => 'required|string|max:100',
            'variantes.*.cantidad_por_variante'  => 'required|numeric|gt:0',
            'variantes.*.precio'                 => 'required|numeric|min:0',
        ]);

        $data = $request->only(['nombre', 'descripcion', 'stock', 'categoria_id']);
        $data['activo']       = $request->has('activo');
        $data['tipo_flor_id'] = $this->tipoFlorSeleccionado($request);

        if ($request->hasFile('imagen')) {
            $data['imagen'] = $request->file('imagen')->store('productos', 'public');
        }

        $producto = Producto::create($data);

        // Relaciones M:N (array_filter descarta filas dejadas en "-- Seleccionar --")
        $producto->sabores()->sync(array_filter($request->sabores ?? []));
        $producto->efectos()->sync(array_filter($request->efectos ?? []));
        $producto->colores()->sync(array_filter($request->colores ?? []));

        // ✅ Crear variantes (sin stock: se deriva del stock total del producto)
        foreach ($request->variantes ?? [] as $v) {
            $producto->variantes()->create([
                'nombre'                => $v['nombre'],
                'cantidad_por_variante' => $v['cantidad_por_variante'],
                'precio'                => $v['precio'],
            ]);
        }

        $producto->load('variantes');
        $producto->sincronizarStockPaquetes();

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
        $categorias     = Categoria::all();
        $sabores        = Sabor::all();
        $efectos        = Efecto::all();
        $colores        = Color::all();
        $presentaciones = \App\Models\Presentacion::activas()->orderBy('cantidad')->get();
        $tiposFlor      = \App\Models\TipoFlor::activos()->orderBy('nombre')->get();
        $floresId       = Categoria::where('slug', 'flores')->value('id');

        $producto->load('variantes');

        return view('productos.edit', compact(
            'producto', 'categorias', 'sabores', 'efectos', 'colores',
            'presentaciones', 'tiposFlor', 'floresId'
        ));
    }

    public function update(Request $request, Producto $producto)
    {
        abort_if(!auth()->user()->isAdmin(), 403, 'Solo el administrador puede editar productos.');

        // Descarta filas de variante completamente vacías antes de validar
        $this->limpiarVariantesVacias($request);

        $request->validate([
            'nombre'       => 'required|string|max:255',
            'descripcion'  => 'nullable|string',
            'stock'        => 'required|numeric|min:0',
            'categoria_id' => 'nullable|exists:categorias,id',
            'tipo_flor_id' => 'nullable|exists:tipos_flor,id',
            'imagen'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'activo'       => 'nullable|boolean',

            // Motivo obligatorio solo cuando se deja inactivo
            'motivo_inactivo'         => ['nullable', 'required_if:activo,0', Rule::in(Producto::MOTIVOS_INACTIVO)],
            'motivo_inactivo_detalle' => 'nullable|required_if:motivo_inactivo,Otro|string|max:500',

            'sabores'   => 'array|nullable',
            'sabores.*' => 'exists:sabores,id',
            'efectos'   => 'array|nullable',
            'efectos.*' => 'exists:efectos,id',
            'colores'   => 'array|nullable',
            'colores.*' => 'exists:colores,id',

            // El stock por variante se calcula del stock total (no se recibe)
            'variantes'                          => 'nullable|array',
            'variantes.*.id'                     => 'nullable|exists:variantes,id',
            'variantes.*.nombre'                 => 'required|string|max:100',
            'variantes.*.cantidad_por_variante'  => 'required|numeric|gt:0',
            'variantes.*.precio'                 => 'required|numeric|min:0',
        ], [
            'motivo_inactivo.required_if'         => 'Debes indicar el motivo para inhabilitar el producto.',
            'motivo_inactivo_detalle.required_if' => 'Describe el motivo cuando seleccionas "Otro".',
        ]);

        $data = $request->only(['nombre', 'descripcion', 'stock', 'categoria_id']);
        $data['activo']       = $request->boolean('activo');
        $data['tipo_flor_id'] = $this->tipoFlorSeleccionado($request);

        if ($data['activo']) {
            // Si queda activo no guardamos motivo
            $data['motivo_inactivo']         = null;
            $data['motivo_inactivo_detalle'] = null;
        } else {
            $data['motivo_inactivo']         = $request->motivo_inactivo;
            $data['motivo_inactivo_detalle'] = $request->motivo_inactivo === 'Otro'
                ? $request->motivo_inactivo_detalle
                : null;
        }

        if ($request->hasFile('imagen')) {
            if ($producto->imagen && Storage::disk('public')->exists($producto->imagen)) {
                Storage::disk('public')->delete($producto->imagen);
            }
            $data['imagen'] = $request->file('imagen')->store('productos', 'public');
        }

        $producto->update($data);

        $producto->sabores()->sync(array_filter($request->sabores ?? []));
        $producto->efectos()->sync(array_filter($request->efectos ?? []));
        $producto->colores()->sync(array_filter($request->colores ?? []));

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
                    ]);
                    $idsEnviados[] = $variante->id;
                }
            } else {
                // Crear nueva (stock se deriva del total del producto)
                $nueva = $producto->variantes()->create([
                    'nombre'                => $v['nombre'],
                    'cantidad_por_variante' => $v['cantidad_por_variante'],
                    'precio'                => $v['precio'],
                ]);
                $idsEnviados[] = $nueva->id;
            }
        }

        // Eliminar las que el usuario borró del formulario
        $producto->variantes()
                 ->whereNotIn('id', $idsEnviados)
                 ->delete();

        // Recalcular disponibilidad de paquetes con el stock total actualizado
        $producto->load('variantes');
        $producto->sincronizarStockPaquetes();

        return redirect()->route('productos.index')
            ->with('success', 'Producto actualizado correctamente');
    }

    /**
     * Elimina del request las filas de variante que llegan totalmente vacías
     * (p. ej. la fila placeholder del formulario cuando el producto no tiene
     * variantes). Así no disparan la validación 'required' al solo cambiar
     * el estado del producto.
     */
    /**
     * Devuelve el tipo_flor_id solo si la categoría elegida es "Flores";
     * en otra categoría el tipo de flor no aplica (null).
     */
    private function tipoFlorSeleccionado(Request $request): ?int
    {
        if (!$request->filled('categoria_id') || !$request->filled('tipo_flor_id')) {
            return null;
        }
        $cat = Categoria::find($request->categoria_id);
        return ($cat && $cat->slug === 'flores') ? (int) $request->tipo_flor_id : null;
    }

    private function limpiarVariantesVacias(Request $request): void
    {
        $variantes = $request->input('variantes', []);

        if (!is_array($variantes)) {
            return;
        }

        $variantes = array_filter($variantes, function ($v) {
            $campos = ['nombre', 'cantidad_por_variante', 'precio', 'stock'];
            foreach ($campos as $campo) {
                if (isset($v[$campo]) && $v[$campo] !== '' && $v[$campo] !== null) {
                    return true; // tiene al menos un dato => se conserva
                }
            }
            return false; // fila vacía => se descarta
        });

        $request->merge(['variantes' => array_values($variantes)]);
    }

    public function destroy(Producto $producto)
    {
        abort_if(!auth()->user()->isAdmin(), 403, 'Solo el administrador puede eliminar productos.');

        // No se puede borrar un producto con historial: la BD lo bloquea (RESTRICT)
        // para no romper ventas/inventario. En ese caso se debe inhabilitar, no eliminar.
        $tieneVentas     = DB::table('detalle_ventas')->where('producto_id', $producto->id)->exists();
        $tieneInventario = DB::table('inventarios')->where('producto_id', $producto->id)->exists();

        if ($tieneVentas || $tieneInventario) {
            $motivo = $tieneVentas ? 'tiene ventas registradas' : 'tiene movimientos de inventario';
            return redirect()->route('productos.index')
                ->with('error', "No se puede eliminar «{$producto->nombre}» porque {$motivo}. "
                    . 'Para retirarlo del catálogo, edítalo y cámbialo a Inactivo.');
        }

        if ($producto->imagen && Storage::disk('public')->exists($producto->imagen)) {
            Storage::disk('public')->delete($producto->imagen);
        }

        // Variantes, reseñas y pivotes se eliminan en cascada por las FK
        $producto->delete();

        return redirect()->route('productos.index')
            ->with('success', 'Producto eliminado correctamente');
    }
}