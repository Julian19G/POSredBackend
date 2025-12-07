<?php

namespace App\Http\Controllers;

use App\Models\Domicilio;
use Illuminate\Http\Request;

class DomicilioController extends Controller
{
    /**
     * Mostrar lista de domicilios.
     */
    public function index()
    {
        $domicilios = Domicilio::with(['venta', 'cliente'])
                               ->orderBy('created_at', 'desc')
                               ->paginate(15);

        return view('domicilios.index', compact('domicilios'));
    }

    /**
     * Vista para crear.
     */
    public function create()
    {
        return view('domicilios.create');
    }

    /**
     * Guardar un nuevo domicilio.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'venta_id'      => 'required|exists:ventas,id',
            'cliente_id'    => 'required|exists:clientes,id',
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

        Domicilio::create($validated);

        return redirect()
                ->route('domicilios.index')
                ->with('success', 'Domicilio creado correctamente.');
    }

    /**
     * Mostrar un domicilio.
     */
    public function show($id)
    {
        $domicilio = Domicilio::with(['venta', 'cliente'])->findOrFail($id);

        return view('domicilios.show', compact('domicilio'));
    }

    /**
     * Vista editar.
     */
    public function edit($id)
    {
        $domicilio = Domicilio::findOrFail($id);
        return view('domicilios.edit', compact('domicilio'));
    }

    /**
     * Actualizar datos.
     */
    public function update(Request $request, $id)
    {
        $domicilio = Domicilio::findOrFail($id);

        $validated = $request->validate([
            'direccion'     => 'string|max:255',
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

    /**
     * Eliminar domicilio.
     */
    public function destroy($id)
    {
        $domicilio = Domicilio::findOrFail($id);
        $domicilio->delete();

        return redirect()
                ->route('domicilios.index')
                ->with('success', 'Domicilio eliminado correctamente.');
    }

    /**
     * Actualizar solo el estado.
     */
    public function actualizarEstado(Request $request, $id)
    {
        $request->validate([
            'estado' => 'required|in:pendiente,enviado,entregado,cancelado',
        ]);

        $domicilio = Domicilio::findOrFail($id);
        $domicilio->estado = $request->estado;

        // Control automático de fechas
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
