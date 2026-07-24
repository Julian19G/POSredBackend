<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\DetalleVenta;
use App\Models\Producto;
use App\Models\Variante;
use App\Models\Cliente;
use App\Models\Descuento;
use App\Models\DescuentoUso;
use App\Models\Pedido;
use App\Models\TarifaDomicilio;
use App\Models\Domicilio;
use App\Models\Vendedor;
use App\Models\Comision;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class VentaController extends Controller
{
    // Retorna el vendedor_id del usuario actual, o null si es admin
    private function miVendedorId(): ?int
    {
        if (auth()->user()->isAdmin()) return null;
        return auth()->user()->vendedor?->id;
    }

    private function verificarAccesoVenta(Venta $venta): void
    {
        $vid = $this->miVendedorId();
        if ($vid !== null && $venta->vendedor_id !== $vid) {
            abort(403, 'No tienes acceso a esta venta.');
        }
    }

    public function index(\Illuminate\Http\Request $request)
    {
        $query = Venta::with(['cliente', 'vendedor', 'detalles'])->latest();

        // Vendedores solo ven sus propias ventas
        $vid = $this->miVendedorId();
        if ($vid !== null) {
            $query->where('vendedor_id', $vid);
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }
        if ($request->filled('fecha_desde')) {
            $query->whereDate('created_at', '>=', $request->fecha_desde);
        }
        if ($request->filled('fecha_hasta')) {
            $query->whereDate('created_at', '<=', $request->fecha_hasta);
        }
        if ($request->filled('buscar')) {
            $b = $request->buscar;
            $query->whereHas('cliente', fn($q) => $q->where('nombre', 'like', "%{$b}%"));
        }

        $ventas = $query->paginate(15)->withQueryString();

        return view('ventas.index', compact('ventas'));
    }

    public function create()
    {
        return view('ventas.create', [
            'productos'  => Producto::with('variantes')->where('activo', true)->get(),
            'clientes'   => Cliente::orderBy('nombre')->get(),
            'descuentos' => Descuento::activos()->get(),
            'vendedores' => Vendedor::activos()->orderBy('nombre')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'cliente_id'         => 'required|exists:clientes,id',
            'vendedor_id'        => 'nullable|exists:vendedores,id',
            'variantes'          => 'required|array|min:1',
            'variantes.*'        => 'required|integer|min:0',
            'productos'          => 'required|array|min:1',
            'productos.*'        => 'required|exists:productos,id',
            'cantidades'         => 'required|array|min:1',
            'cantidades.*'       => 'required|integer|min:1',
            'precios_manuales'   => 'nullable|array',
            'precios_manuales.*' => 'nullable|numeric|min:0',
            'descuento_id'       => 'nullable|exists:descuentos,id',
            'descuento_manual'   => 'nullable|numeric|min:0',
            'motivo_descuento'   => 'nullable|string|max:255',
            'envio'              => 'required|in:0,1',
            'direccion'          => 'nullable|required_if:envio,1|string|max:255',
            'ciudad'             => 'nullable|required_if:envio,1|string|max:100',
            'departamento'       => 'nullable|string|max:100',
            'pais'               => 'nullable|string|max:100',
            'costo_envio'        => 'nullable|numeric|min:0',
            'fecha_entrega'      => 'nullable|date',
            'comentarios'        => 'nullable|string|max:500',
            'metodo_pago'        => 'nullable|in:efectivo,transferencia,cripto,tarjeta,otro',
        ]);

        foreach ($request->variantes as $i => $vid) {
            if ((int) $vid > 0 && !Variante::where('id', $vid)->exists()) {
                throw ValidationException::withMessages([
                    'variantes' => 'La variante de la fila ' . ($i + 1) . ' no existe.',
                ]);
            }
        }

        DB::beginTransaction();

        try {
            $subtotal = $this->calcularSubtotal($request);

            $descuento = null;
            if ($request->filled('descuento_id')) {
                $descuento = Descuento::findOrFail($request->descuento_id);
                $cliente   = Cliente::findOrFail($request->cliente_id);

                if (!$descuento->puedeUsar($cliente)) {
                    throw new \Exception('Este descuento ya no está disponible para este cliente.');
                }
            }

            $descuentoMonto  = 0;
            $descuentoManual = (float) ($request->descuento_manual ?? 0);

            if ($descuento) {
                $descuentoMonto = $descuento->tipo === 'porcentaje'
                    ? $subtotal * ($descuento->valor / 100)
                    : $descuento->valor;
            } elseif ($descuentoManual > 0) {
                $descuentoMonto = $descuentoManual;
            }

            $esEnvio    = (int) $request->envio === 1;
            $costoEnvio = $esEnvio ? (float) ($request->costo_envio ?? 0) : 0;

            $venta = Venta::create([
                'cliente_id'       => $request->cliente_id,
                'vendedor_id'      => $request->vendedor_id ?: null,
                'subtotal'         => $subtotal,
                'descuento_id'     => $descuento?->id,
                'descuento_manual' => $descuentoMonto,
                'motivo_descuento' => $request->motivo_descuento,
                'envio'            => $esEnvio,
                'costo_envio'      => $costoEnvio,
                'direccion_envio'  => $esEnvio ? $request->direccion : null,
                'estado'           => 'pendiente',
            ]);

            $this->registrarDetalles($venta, $request);

            if ($esEnvio) {
                $tarifa = TarifaDomicilio::vigente();

                $partes = array_filter([
                    $request->direccion,
                    $request->ciudad,
                    $request->departamento,
                    $request->comentarios,
                ]);

                Domicilio::create([
                    'venta_id'               => $venta->id,
                    'direccion'              => $request->direccion,
                    'ciudad'                 => $request->ciudad,
                    'departamento'           => $request->departamento,
                    'pais'                   => $request->pais ?? 'Colombia',
                    'estado'                 => 'pendiente',
                    'costo_envio'            => $costoEnvio,
                    'fecha_entrega'          => $request->fecha_entrega,
                    'comentarios'            => $request->comentarios,
                    'tarifa_id'              => $tarifa?->id,
                    'tarifa_monto'           => $tarifa?->monto,
                    'cobrar_en_entrega'      => true,
                    'monto_cobrar'           => null, // se calcula al aceptar (venta.total)
                    'instrucciones_entrega'  => implode(' — ', $partes),
                    'instrucciones_recogida' => 'Contactar al vendedor para coordinar recogida.',
                ]);
            }

            Pedido::create([
                'venta_id'    => $venta->id,
                'estado'      => 'nuevo',
                'metodo_pago' => $request->metodo_pago ?: null,
                'estado_pago' => 'pendiente',
            ]);

            if ($descuento) {
                DescuentoUso::create([
                    'descuento_id' => $descuento->id,
                    'cliente_id'   => $venta->cliente_id,
                    'venta_id'     => $venta->id,
                ]);
            }

            Comision::crearParaVenta($venta);

            DB::commit();

            return redirect()->route('ventas.index')
                ->with('success', '✅ Venta registrada exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function show($id)
    {
        $venta = Venta::with([
            'cliente',
            'vendedor',
            'detalles.variante.producto',
            'domicilio',
            'pedido.comprobantes',
            'pagos.registradoPor',
        ])->findOrFail($id);
        $this->verificarAccesoVenta($venta);
        return view('ventas.show', compact('venta'));
    }

    public function recibo($id)
    {
        $venta = Venta::with(['cliente', 'detalles.variante.producto', 'domicilio', 'pedido'])->findOrFail($id);
        $this->verificarAccesoVenta($venta);
        return view('ventas.recibo', compact('venta'));
    }

    public function edit($id)
    {
        abort_if(!auth()->user()->isAdmin(), 403, 'Solo el administrador puede editar ventas.');
        return view('ventas.edit', [
            'venta'      => Venta::with('detalles.variante.producto')->findOrFail($id),
            'productos'  => Producto::with('variantes')->where('activo', true)->get(),
            'clientes'   => Cliente::all(),
            'vendedores' => Vendedor::activos()->orderBy('nombre')->get(),
        ]);
    }

    public function update(Request $request, $id)
    {
        abort_if(!auth()->user()->isAdmin(), 403, 'Solo el administrador puede editar ventas.');
        $venta = Venta::findOrFail($id);

        $request->validate([
            'cliente_id'         => 'required|exists:clientes,id',
            'vendedor_id'        => 'nullable|exists:vendedores,id',
            'variantes'          => 'required|array|min:1',
            'variantes.*'        => 'integer|min:0',
            'productos'          => 'required|array|min:1',
            'productos.*'        => 'required|exists:productos,id',
            'cantidades'         => 'required|array|min:1',
            'cantidades.*'       => 'integer|min:1',
            'precios_manuales'   => 'nullable|array',
            'precios_manuales.*' => 'nullable|numeric|min:0',
            'descuento_id'       => 'nullable|exists:descuentos,id',
            'descuento_manual'   => 'nullable|numeric|min:0',
            'motivo_descuento'   => 'nullable|string|max:255',
            'envio'              => 'boolean',
            'estado'             => 'nullable|in:pendiente,pagada,cancelada',
        ]);

        DB::beginTransaction();
        try {
            foreach ($venta->detalles as $detalle) {
                if ($detalle->variante) {
                    $detalle->variante->increment('stock', $detalle->cantidad);
                    $unidadesBase = $detalle->variante->cantidad_por_variante * $detalle->cantidad;
                    $detalle->variante->producto->increment('stock', $unidadesBase);
                } elseif ($detalle->producto) {
                    $detalle->producto->increment('stock', $detalle->cantidad);
                }
            }

            $venta->detalles()->delete();

            $subtotal = $this->calcularSubtotal($request);

            $venta->update([
                'cliente_id'       => $request->cliente_id,
                'vendedor_id'      => $request->vendedor_id ?: null,
                'subtotal'         => $subtotal,
                'descuento_manual' => $request->descuento_manual ?? 0,
                'motivo_descuento' => $request->motivo_descuento,
                'envio'            => $request->boolean('envio'),
                'estado'           => $request->estado ?? 'pendiente',
            ]);

            $this->registrarDetalles($venta, $request);

            // Anular comision pendiente anterior y recalcular con nuevos datos
            $venta->comision?->estado === 'pendiente'
                && $venta->comision->delete();
            Comision::crearParaVenta($venta->fresh());

            DB::commit();
            return redirect()->route('ventas.index')
                ->with('success', 'Venta actualizada correctamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        abort_if(!auth()->user()->isAdmin(), 403, 'Solo el administrador puede eliminar ventas.');
        $venta = Venta::findOrFail($id);

        DB::beginTransaction();
        try {
            foreach ($venta->detalles as $detalle) {
                if ($detalle->variante) {
                    $detalle->variante->increment('stock', $detalle->cantidad);
                    $unidadesBase = $detalle->variante->cantidad_por_variante * $detalle->cantidad;
                    $detalle->variante->producto->increment('stock', $unidadesBase);
                } elseif ($detalle->producto) {
                    $detalle->producto->increment('stock', $detalle->cantidad);
                }
            }

            $venta->detalles()->delete();
            $venta->delete();

            DB::commit();
            return back()->with('success', 'Venta eliminada.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    // ─────────────────────────────────────────
    //  MÉTODOS PRIVADOS
    // ─────────────────────────────────────────

    private function calcularSubtotal(Request $request): float
    {
        $subtotal = 0;
        foreach ($request->variantes as $i => $varianteId) {
            $cantidad = (int) $request->cantidades[$i];
            if ((int) $varianteId > 0) {
                $variante  = Variante::findOrFail($varianteId);
                $subtotal += $variante->precio * $cantidad;
            } else {
                $subtotal += (float) ($request->precios_manuales[$i] ?? 0) * $cantidad;
            }
        }
        return $subtotal;
    }

    private function registrarDetalles(Venta $venta, Request $request): void
    {
        // ── Pasada 1: validar stock y construir líneas ──────────────────────
        $lineas     = [];
        $brutoTotal = 0;

        foreach ($request->variantes as $i => $varianteId) {
            $cantidad = (int) $request->cantidades[$i];

            if ((int) $varianteId > 0) {
                $variante     = Variante::with('producto')->findOrFail($varianteId);
                $unidadesBase = $variante->cantidad_por_variante * $cantidad;

                if ($variante->stock < $cantidad) {
                    throw new \Exception(
                        "Stock insuficiente para la variante \"{$variante->nombre}\" " .
                        "del producto \"{$variante->producto->nombre}\". " .
                        "Disponible: {$variante->stock}"
                    );
                }
                if ($variante->producto->stock < $unidadesBase) {
                    throw new \Exception(
                        "Stock base insuficiente para \"{$variante->producto->nombre}\". " .
                        "Necesitas {$unidadesBase} unidades, disponible: {$variante->producto->stock}"
                    );
                }

                $bruto    = $variante->precio * $cantidad;
                $lineas[] = [
                    'data'         => [
                        'venta_id'        => $venta->id,
                        'producto_id'     => $variante->producto_id,
                        'variante_id'     => $variante->id,
                        'nombre_producto' => $variante->producto->nombre,
                        'nombre_variante' => $variante->nombre,
                        'codigo_producto' => $variante->producto->codigo ?? null,
                        'cantidad'        => $cantidad,
                        'precio_unitario' => $variante->precio,
                    ],
                    'bruto'        => $bruto,
                    'variante'     => $variante,
                    'unidadesBase' => $unidadesBase,
                ];

            } else {
                $precioManual = (float) ($request->precios_manuales[$i] ?? 0);
                $producto     = Producto::findOrFail((int) $request->productos[$i]);

                if ($producto->stock < $cantidad) {
                    throw new \Exception(
                        "Stock insuficiente para \"{$producto->nombre}\". " .
                        "Disponible: {$producto->stock}"
                    );
                }

                $bruto    = $precioManual * $cantidad;
                $lineas[] = [
                    'data'     => [
                        'venta_id'        => $venta->id,
                        'producto_id'     => $producto->id,
                        'variante_id'     => null,
                        'nombre_producto' => $producto->nombre,
                        'nombre_variante' => null,
                        'codigo_producto' => $producto->codigo ?? null,
                        'cantidad'        => $cantidad,
                        'precio_unitario' => $precioManual,
                    ],
                    'bruto'    => $bruto,
                    'producto' => $producto,
                ];
            }

            $brutoTotal += $lineas[count($lineas) - 1]['bruto'];
        }

        // ── Pasada 2: distribuir descuento proporcional entre líneas ───────
        $descuento  = (float) ($venta->descuento_manual ?? 0);
        $acumulado  = 0;
        $lastIdx    = count($lineas) - 1;

        foreach ($lineas as $idx => &$linea) {
            if ($descuento > 0 && $brutoTotal > 0) {
                $linea['descuentoLinea'] = $idx === $lastIdx
                    ? max(round($descuento - $acumulado, 2), 0)
                    : round($descuento * ($linea['bruto'] / $brutoTotal), 2);
                $acumulado += $linea['descuentoLinea'];
            } else {
                $linea['descuentoLinea'] = 0;
            }
        }
        unset($linea);

        // ── Pasada 3: persistir y descontar stock ───────────────────────────
        foreach ($lineas as $linea) {
            $row = $linea['data'];
            $row['descuento_aplicado'] = $linea['descuentoLinea'];
            $row['subtotal']           = max($linea['bruto'] - $linea['descuentoLinea'], 0);

            DetalleVenta::create($row);

            if (isset($linea['variante'])) {
                $linea['variante']->decrement('stock', $row['cantidad']);
                $linea['variante']->producto->decrement('stock', $linea['unidadesBase']);
            } else {
                $linea['producto']->decrement('stock', $row['cantidad']);
            }
        }
    }
}