<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\DetalleVenta;
use App\Models\Producto;
use App\Models\Cliente;
use App\Models\Descuento;
use App\Models\Domicilio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class VentaController extends Controller
{
    /* =========================
     * LISTADO
     * ========================= */
    public function index()
    {
        $ventas = Venta::with(['cliente', 'detalles.producto'])
            ->latest()
            ->paginate(10);

        return view('ventas.index', compact('ventas'));
    }

    /* =========================
     * FORM CREAR
     * ========================= */
    public function create()
    {
        return view('ventas.create', [
            'productos' => Producto::where('stock', '>', 0)->get(),
            'clientes'  => Cliente::all(),
            'descuentos' => Descuento::activos()->get(),
        ]);
    }

    /* =========================
     * GUARDAR
     * ========================= */
public function store(Request $request)
{
    $request->validate([
        'cliente_id'       => 'required|exists:clientes,id',
        'productos'        => 'required|array|min:1',
        'productos.*'      => 'required|exists:productos,id',
        'cantidades'       => 'required|array|min:1',
        'cantidades.*'     => 'required|integer|min:1',
        'descuento_id'     => 'nullable|exists:descuentos,id',
        'descuento_manual' => 'nullable|numeric|min:0',
        'motivo_descuento' => 'nullable|string|max:255',
        'envio'            => 'required|in:0,1',
        'direccion'        => 'nullable|required_if:envio,1|string|max:255',
        'ciudad'           => 'nullable|required_if:envio,1|string|max:100',
        'departamento'     => 'nullable|string|max:100',
        'pais'             => 'nullable|string|max:100',
        'costo_envio'      => 'nullable|numeric|min:0',
        'fecha_entrega'    => 'nullable|date',
        'comentarios'      => 'nullable|string|max:500',
    ]);

    DB::beginTransaction();

    try {
        // 1. Calcular subtotal
        $subtotal = $this->calcularSubtotal($request);

        // 2. Resolver descuento de catálogo
        $descuento = null;
        if ($request->filled('descuento_id')) {
            $descuento = Descuento::findOrFail($request->descuento_id);
            $cliente   = Cliente::findOrFail($request->cliente_id);

            if (!$descuento->puedeUsar($cliente)) {
                throw new \Exception('Este descuento ya no está disponible para este cliente.');
            }
        }

        // 3. Calcular descuento manual o de catálogo
        $descuentoMonto = 0;
        $descuentoManual = (float) ($request->descuento_manual ?? 0);

        if ($descuento) {
            $descuentoMonto = $descuento->tipo === 'porcentaje'
                ? $subtotal * ($descuento->valor / 100)
                : $descuento->valor;
        } elseif ($descuentoManual > 0) {
            $descuentoMonto = $descuentoManual;
        }

        // 4. Envío
        $esEnvio    = (int) $request->envio === 1;
        $costoEnvio = $esEnvio ? (float) ($request->costo_envio ?? 0) : 0;

        // 5. Crear venta (el evento saving calculará el total automáticamente)
        $venta = Venta::create([
            'cliente_id'       => $request->cliente_id,
            'subtotal'         => $subtotal,
            'descuento_id'     => $descuento?->id,
            'descuento_manual' => $descuentoMonto,   // guardamos el monto ya calculado
            'motivo_descuento' => $request->motivo_descuento,
            'envio'            => $esEnvio,
            'costo_envio'      => $costoEnvio,
            'direccion_envio'  => $esEnvio ? $request->direccion : null,
            'estado'           => 'pendiente',
            // total lo calcula el evento boot() → calcularTotal()
        ]);

        // 6. Detalles + stock
        $this->registrarDetalles($venta, $request);

        // 7. Domicilio (si aplica)
        if ($esEnvio) {
            Domicilio::create([
                'venta_id'      => $venta->id,
                'direccion'     => $request->direccion,
                'ciudad'        => $request->ciudad,
                'departamento'  => $request->departamento,
                'pais'          => $request->pais ?? 'Colombia',
                'estado'        => 'pendiente',
                'costo_envio'   => $costoEnvio,
                'fecha_entrega' => $request->fecha_entrega,
                'comentarios'   => $request->comentarios,
            ]);
        }

        DB::commit();

        return redirect()->route('ventas.index')
            ->with('success', '✅ Venta registrada exitosamente.');

    } catch (\Exception $e) {
        DB::rollBack();
        return back()->withErrors(['error' => $e->getMessage()])->withInput();
    }
}
    /* =========================
     * VER
     * ========================= */
    public function show($id)
    {
        $venta = Venta::with(['cliente', 'detalles.producto'])->findOrFail($id);
        return view('ventas.show', compact('venta'));
    }

    /* =========================
     * FORM EDITAR
     * ========================= */
    public function edit($id)
    {
        return view('ventas.edit', [
            'venta'     => Venta::with('detalles.producto')->findOrFail($id),
            'productos' => Producto::all(),
            'clientes'  => Cliente::all(),
        ]);
    }

    /* =========================
     * ACTUALIZAR
     * ========================= */
    public function update(Request $request, $id)
    {
        $venta = Venta::findOrFail($id);
        $this->validarVenta($request);

        // 🔥 Resolver descuentos
        $descuento = $this->resolverDescuento($request);

        DB::beginTransaction();
        try {
            // Revertir stock
            foreach ($venta->detalles as $detalle) {
                $detalle->producto->increment('stock', $detalle->cantidad);
            }

            $venta->detalles()->delete();

            $subtotal = $this->calcularSubtotal($request);

            $venta->update([
                'cliente_id'       => $request->cliente_id,
                'subtotal'         => $subtotal,
                'descuento_id'     => $descuento?->id,
                'descuento_manual' => $request->descuento_manual ?? 0,
                'motivo_descuento' => $request->motivo_descuento,
                'envio'            => $request->boolean('envio'),
                'estado'           => $request->estado ?? 'pendiente',
            ]);

            $this->registrarDetalles($venta, $request);

            DB::commit();
            return redirect()->route('ventas.index')
                ->with('success', 'Venta actualizada correctamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /* =========================
     * ELIMINAR
     * ========================= */
    public function destroy($id)
    {
        $venta = Venta::findOrFail($id);

        DB::beginTransaction();
        try {
            foreach ($venta->detalles as $detalle) {
                $detalle->producto->increment('stock', $detalle->cantidad);
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

    /* =========================
     * MÉTODOS PRIVADOS
     * ========================= */

    private function validarVenta(Request $request): void
    {
        $request->validate([
            'cliente_id'       => 'required|exists:clientes,id',
            'productos'        => 'required|array|min:1',
            'productos.*'      => 'exists:productos,id',
            'cantidades'       => 'required|array|min:1',
            'cantidades.*'     => 'integer|min:1',
            'descuento_id'     => 'nullable|exists:descuentos,id',
            'descuento_manual' => 'nullable|numeric|min:0',
            'motivo_descuento' => 'nullable|string|max:255',
            'envio'            => 'boolean',
            'estado'           => 'nullable|in:pendiente,pagada,cancelada',
        ]);
    }

    private function resolverDescuento(Request $request): ?Descuento
    {
        $descuento = null;

        if ($request->filled('descuento_id')) {
            $descuento = Descuento::activos()->findOrFail($request->descuento_id);

            if ($descuento->aplicable_manual && empty($request->motivo_descuento)) {
                throw ValidationException::withMessages([
                    'motivo_descuento' => 'Debes justificar este descuento.',
                ]);
            }
        }

        if (($request->descuento_manual ?? 0) > 0 && empty($request->motivo_descuento)) {
            throw ValidationException::withMessages([
                'motivo_descuento' => 'Debes justificar el descuento aplicado.',
            ]);
        }

        return $descuento;
    }

    private function calcularSubtotal(Request $request): float
    {
        $subtotal = 0;

        foreach ($request->productos as $i => $producto_id) {
            $producto = Producto::findOrFail($producto_id);
            $subtotal += $producto->precio * $request->cantidades[$i];
        }

        return $subtotal;
    }

    private function registrarDetalles(Venta $venta, Request $request): void
    {
        foreach ($request->productos as $i => $producto_id) {
            $producto = Producto::findOrFail($producto_id);
            $cantidad = $request->cantidades[$i];

            DetalleVenta::create([
                'venta_id'        => $venta->id,
                'producto_id'     => $producto_id,
                'nombre_producto' => $producto->nombre,
                'codigo_producto' => $producto->codigo,
                'cantidad'        => $cantidad,
                'precio_unitario' => $producto->precio,
                'subtotal'        => $producto->precio * $cantidad,
            ]);

            $producto->decrement('stock', $cantidad);
        }
    }
}
