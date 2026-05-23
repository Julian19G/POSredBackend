<?php

namespace App\Http\Controllers;

use App\Models\Vendedor;
use App\Models\Comision;
use App\Models\Liquidacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LiquidacionController extends Controller
{
    public function store(Request $request, Vendedor $vendedor)
    {
        $request->validate([
            'comision_ids'   => 'required|array|min:1',
            'comision_ids.*' => 'integer|exists:comisiones,id',
            'metodo_pago'    => 'required|in:efectivo,transferencia,cripto,tarjeta,otro',
            'referencia'     => 'nullable|string|max:255',
            'notas'          => 'nullable|string|max:500',
            'fecha_pago'     => 'required|date',
        ]);

        DB::transaction(function () use ($request, $vendedor) {
            $comisiones = Comision::whereIn('id', $request->comision_ids)
                ->where('vendedor_id', $vendedor->id)
                ->where('estado', 'pendiente')
                ->get();

            if ($comisiones->isEmpty()) {
                throw new \Exception('No hay comisiones pendientes seleccionadas.');
            }

            $liquidacion = Liquidacion::create([
                'vendedor_id' => $vendedor->id,
                'monto_total' => $comisiones->sum('monto_comision'),
                'metodo_pago' => $request->metodo_pago,
                'referencia'  => $request->referencia,
                'notas'       => $request->notas,
                'fecha_pago'  => $request->fecha_pago,
            ]);

            $comisiones->each(fn($c) => $c->update([
                'estado'         => 'pagada',
                'liquidacion_id' => $liquidacion->id,
            ]));
        });

        return redirect()->route('vendedores.show', $vendedor->id)
            ->with('success', '💰 Liquidación registrada. Comisiones marcadas como pagadas.');
    }

    public function show(Vendedor $vendedor, Liquidacion $liquidacion)
    {
        abort_if($liquidacion->vendedor_id !== $vendedor->id, 404);

        $liquidacion->load('comisiones.venta.cliente');

        return view('liquidaciones.show', compact('vendedor', 'liquidacion'));
    }
}
