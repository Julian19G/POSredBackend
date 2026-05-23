<?php

namespace App\Http\Controllers;

use App\Models\Domicilio;
use App\Models\Zona;
use Illuminate\Http\Request;

class DomicilioController extends Controller
{
    public function index()
    {
        $domicilios = Domicilio::with(['venta.cliente', 'zona'])
                               ->orderBy('created_at', 'desc')
                               ->paginate(15);

        return view('domicilios.index', compact('domicilios'));
    }

    public function create()
    {
        abort(404); // se crea desde VentaController
    }

    public function store(Request $request)
    {
        abort(404); // se crea desde VentaController
    }

    public function show($id)
    {
        $domicilio = Domicilio::with(['venta.cliente', 'zona'])->findOrFail($id);
        return view('domicilios.show', compact('domicilio'));
    }

    public function edit($id)
    {
        $domicilio = Domicilio::with(['venta.cliente'])->findOrFail($id);
        $zonas     = Zona::activas()->orderBy('nombre')->get();
        return view('domicilios.edit', compact('domicilio', 'zonas'));
    }

    public function update(Request $request, $id)
    {
        $domicilio = Domicilio::findOrFail($id);

        $validated = $request->validate([
            'direccion'             => 'required|string|max:255',
            'ciudad'                => 'nullable|string|max:255',
            'departamento'          => 'nullable|string|max:255',
            'pais'                  => 'nullable|string|max:255',
            'zona_id'               => 'nullable|exists:zonas_cali,id',
            'latitud'               => 'nullable|numeric|between:-90,90',
            'longitud'              => 'nullable|numeric|between:-180,180',
            'referencia_ubicacion'  => 'nullable|string|max:255',
            'estado'                => 'nullable|in:pendiente,enviado,entregado,cancelado',
            'costo_envio'           => 'nullable|numeric|min:0',
            'fecha_envio'           => 'nullable|date',
            'fecha_entrega'         => 'nullable|date',
            'comentarios'           => 'nullable|string|max:255',
        ]);

        $domicilio->update($validated);

        return redirect()->route('domicilios.show', $id)
            ->with('success', 'Domicilio actualizado correctamente.');
    }

    public function destroy($id)
    {
        Domicilio::findOrFail($id)->delete();
        return redirect()->route('domicilios.index')
            ->with('success', 'Domicilio eliminado.');
    }

    public function actualizarEstado(Request $request, $id)
    {
        $request->validate([
            'estado' => 'required|in:pendiente,enviado,entregado,cancelado',
        ]);

        $domicilio = Domicilio::findOrFail($id);
        $domicilio->estado = $request->estado;

        if ($request->estado === 'enviado')    $domicilio->fecha_envio    = now();
        if ($request->estado === 'entregado')  $domicilio->fecha_entrega  = now();

        $domicilio->save();

        return redirect()->route('domicilios.show', $id)
            ->with('success', 'Estado actualizado.');
    }

    /**
     * Vista mapa: todos los domicilios pendientes/en-camino sobre Cali
     */
    public function mapa()
    {
        $domicilios = Domicilio::with(['venta.cliente', 'zona'])
            ->pendientes()
            ->get()
            ->map(function ($d) {
                return [
                    'id'        => $d->id,
                    'cliente'   => $d->venta->cliente->nombre ?? '—',
                    'direccion' => $d->direccion,
                    'zona'      => $d->zona->nombre ?? null,
                    'estado'    => $d->estado,
                    'lat'       => $d->latitud,
                    'lng'       => $d->longitud,
                    'url'       => route('domicilios.show', $d->id),
                ];
            });

        return view('domicilios.mapa', compact('domicilios'));
    }
}
