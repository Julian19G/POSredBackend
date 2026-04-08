<?php

namespace App\Http\Controllers;

use App\Models\Domicilio;
use Illuminate\Http\Request;

class DomicilioController extends Controller
{
    public function index()
    {
        $domicilios = Domicilio::with(['venta.cliente'])
                               ->orderBy('created_at', 'desc')
                               ->paginate(15);

        return view('domicilios.index', compact('domicilios'));
    }

    public function create()
    {
        // No se usa: el domicilio se crea desde la venta
        abort(404);
    }

    public function store(Request $request)
    {
        // Tampoco se usa directamente: lo llama VentaController
        abort(404);
    }

    public function show($id)
    {
        $domicilio = Domicilio::with(['venta.cliente'])->findOrFail($id);
        return view('domicilios.show', compact('domicilio'));
    }

    public function edit($id)
    {
        $domicilio = Domicilio::with(['venta.cliente'])->findOrFail($id);
        return view('domicilios.edit', compact('domicilio'));
    }

    public function update(Request $request, $id)
    {
        $domicilio = Domicilio::findOrFail($id);

        $validated = $request->validate([
            'direccion'     => 'required|string|max:255',
            'ciudad'        => 'nullable|string|max:255',
            'departamento'  => 'nullable|string|max:255',
            'pais'          => 'nullable|string|max:255',
            'telefono'      => 'nullable|string|max:30',
            'estado'        => 'nullable|in:pendiente,enviado,entregado,cancelado',
            'costo_envio'   => 'nullable|numeric|min:0',
            'fecha_envio'   => 'nullable|date',
            'fecha_entrega' => 'nullable|date',
            'comentarios'   => 'nullable|string|max:255',
        ]);

        $domicilio->update($validated);

        return redirect()
            ->route('domicilios.show', $id)
            ->with('success', 'Domicilio actualizado correctamente.');
    }

    public function destroy($id)
    {
        $domicilio = Domicilio::findOrFail($id);
        $domicilio->delete();

        return redirect()
            ->route('domicilios.index')
            ->with('success', 'Domicilio eliminado correctamente.');
    }

    public function actualizarEstado(Request $request, $id)
    {
        $request->validate([
            'estado' => 'required|in:pendiente,enviado,entregado,cancelado',
        ]);

        $domicilio = Domicilio::findOrFail($id);
        $domicilio->estado = $request->estado;

        if ($request->estado === 'enviado') {
            $domicilio->fecha_envio = now();
        }
        if ($request->estado === 'entregado') {
            $domicilio->fecha_entrega = now();
        }

        $domicilio->save();

        return redirect()
            ->route('domicilios.show', $id)
            ->with('success', 'Estado actualizado correctamente.');
    }
}