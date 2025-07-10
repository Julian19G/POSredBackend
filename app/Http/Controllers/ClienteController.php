<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       $clientes = Cliente::with('referidoPor')->get();
       return view('clientes.index', compact('clientes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clientes = Cliente::all();
        return view('clientes.create', compact('clientes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'telefono' => 'required|string|unique::clientes',
            'email' => 'required|email|max:100|unique:clientes',
            'direccion' => 'required|string',
            'referido_por' => 'nullable|exists::clientes,id',
        ]);

        Cliente::create($validated);
        return redirect()->route('clientes.index')->with('success','Cliente creado Correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $cliente = Cliente::with(['referidoPor', 'referidos'])->findOrFail($id);
        return view('clientes.show', compact('cliente'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $cliente = Cliente::findOrFail($id);
        $clientes = Cliente::where('id','!=',$id)->get();
        return view('clientes.edit', compact('cliente','clientes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $cliente = Cliente::findOrFail($id);

        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'telefono' => 'required|string|max:20|unique:clientes,telefono,' . $id,
            'email' => 'required|email|max:100|unique:clientes,email,' . $id,
            'direccion' => 'required|string',
            'referido_por' => 'nullable|exists:clientes,id|not_in:' . $id,
        ]);

        $cliente->update($validated);
        return redirect()->route('clientes.index')->with('sucess','Cliente Actualizado Correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $cliente = Cliente::findOrFail($id);
        $cliente->delete();
        return redirect()->route('clientes.index')->with('success','Cliente Eliminado Correctamente');
    }
}
