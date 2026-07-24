<?php

namespace App\Http\Controllers;

use App\Models\Inventario;
use App\Models\Producto;
use App\Models\Variante;
use Illuminate\Http\Request;

class InventarioController extends Controller
{
    public function create(Producto $producto)
    {
        $producto->load('variantes');
        return view('inventarios.create', compact('producto'));
    }

    public function store(Request $request, Producto $producto)
    {
        $request->validate([
            'variante_id'  => 'nullable|exists:variantes,id',
            'cantidad'     => 'required|numeric|gt:0',
            'descripcion'  => 'nullable|string|max:255',
        ]);

        $cantidad = (float) $request->cantidad;

        if ($request->filled('variante_id')) {
            // Entrada por presentación: N paquetes = N × unidades de la presentación
            $variante     = Variante::findOrFail($request->variante_id);
            $unidadesBase = $variante->cantidad_por_variante * $cantidad;
            $producto->increment('stock', $unidadesBase);
        } else {
            // Entrada directa en unidades base
            $producto->increment('stock', $cantidad);
        }

        // Recalcular disponibilidad de cada presentación con el nuevo stock total
        $producto->load('variantes');
        $producto->sincronizarStockPaquetes();

        Inventario::create([
            'producto_id'  => $producto->id,
            'variante_id'  => $request->variante_id ?: null,
            'tipo'         => 'entrada',
            'cantidad'     => $cantidad,
            'descripcion'  => $request->descripcion,
        ]);

        return redirect()->route('productos.show', $producto)
            ->with('success', "Stock actualizado: +{$cantidad} unidades agregadas a \"{$producto->nombre}\".");
    }
}
