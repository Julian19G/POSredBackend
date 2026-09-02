<?php

namespace App\Http\Controllers;

use App\Models\Vendedor;
use App\Models\Domiciliario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VendedorController extends Controller
{
    public function index()
    {
        $vendedores = Vendedor::with('domiciliario')
            ->withCount('ventas')
            ->withSum('ventas', 'total')
            ->orderBy('nombre')
            ->get();

        return view('vendedores.index', compact('vendedores'));
    }

    public function create()
    {
        return view('vendedores.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre'               => 'required|string|max:150',
            'telefono'             => 'nullable|string|max:20',
            'whatsapp'             => 'nullable|string|max:20',
            'email'                => 'nullable|email|max:150',
            'instagram'            => 'nullable|string|max:100',
            'comision_porcentaje'  => 'nullable|numeric|min:0|max:100',
            'notas'                => 'nullable|string',
        ]);

        $validated['activo']              = $request->has('activo');
        $validated['comision_porcentaje'] = $validated['comision_porcentaje'] ?? 0;

        Vendedor::create($validated);

        return redirect()->route('vendedores.index')
            ->with('success', 'Vendedor creado correctamente.');
    }

    public function show(Vendedor $vendedor)
    {
        $vendedor->load('domiciliario');
        $ventas = $vendedor->ventas()->with('cliente')->latest()->paginate(10);

        $comisionesPendientes = $vendedor->comisiones()
            ->where('estado', 'pendiente')
            ->with('venta.cliente')
            ->latest()
            ->get();

        $liquidaciones = $vendedor->liquidaciones()
            ->withCount('comisiones')
            ->latest('fecha_pago')
            ->get();

        $stats = [
            'total_ventas'       => $vendedor->ventas()->count(),
            'total_comisionado'  => $vendedor->totalComisionado(),
            'total_pagado'       => $vendedor->totalPagado(),
            'saldo_pendiente'    => $vendedor->saldoPendiente(),
            'domicilios_entregados' => $vendedor->domiciliario?->domicilios()->where('estado', 'entregado')->count() ?? 0,
            'ganancia_domicilios'   => $vendedor->domiciliario?->gananciaTotal() ?? 0,
        ];
        $stats['total_ganado'] = $stats['total_comisionado'] + $stats['ganancia_domicilios'];

        $domicilios = $vendedor->domiciliario
            ? $vendedor->domiciliario->domicilios()->with('venta.cliente')->latest()->paginate(10, ['*'], 'domicilios_page')
            : collect();

        return view('vendedores.show', compact(
            'vendedor', 'ventas', 'comisionesPendientes', 'liquidaciones', 'stats', 'domicilios'
        ));
    }

    public function edit(Vendedor $vendedor)
    {
        return view('vendedores.edit', compact('vendedor'));
    }

    public function update(Request $request, Vendedor $vendedor)
    {
        $validated = $request->validate([
            'nombre'               => 'required|string|max:150',
            'telefono'             => 'nullable|string|max:20',
            'whatsapp'             => 'nullable|string|max:20',
            'email'                => 'nullable|email|max:150',
            'instagram'            => 'nullable|string|max:100',
            'comision_porcentaje'  => 'nullable|numeric|min:0|max:100',
            'notas'                => 'nullable|string',
            'domiciliario_activo'  => 'nullable|boolean',
            'vehiculo'             => 'required_if:domiciliario_activo,1|in:moto,bicicleta,pie,carro',
        ]);

        $validated['activo']              = $request->has('activo');
        $validated['comision_porcentaje'] = $validated['comision_porcentaje'] ?? 0;

        DB::transaction(function () use ($vendedor, $validated, $request) {
            $vendedor->update($validated);

            if ($request->boolean('domiciliario_activo')) {
                Domiciliario::updateOrCreate(
                    ['user_id' => $vendedor->user_id],
                    [
                        'nombre' => $vendedor->nombre,
                        'telefono' => $vendedor->telefono,
                        'vehiculo' => $request->vehiculo,
                        'activo' => true,
                    ]
                );
            } elseif ($vendedor->domiciliario) {
                $vendedor->domiciliario->update(['activo' => false]);
            }
        });

        return redirect()->route('vendedores.index')
            ->with('success', 'Vendedor actualizado correctamente.');
    }

    public function destroy(Vendedor $vendedor)
    {
        $vendedor->update(['activo' => false]);

        return redirect()->route('vendedores.index')
            ->with('success', 'Vendedor desactivado.');
    }
}
