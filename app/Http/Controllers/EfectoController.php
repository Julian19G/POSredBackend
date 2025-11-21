<?php

namespace App\Http\Controllers;

use App\Models\Efecto;
use Illuminate\Http\Request;

class EfectoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $efectos = Efecto::orderBy('id', 'DESC')->paginate(10);
        return view('efectos.index', compact('efectos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tipos = ['positivo', 'negativo', 'neutral'];
        return view('efectos.create', compact('tipos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|max:100|unique:efectos,nombre',
            'descripcion' => 'nullable|string',
            'tipo' => 'required|in:positivo,negativo,neutral',
            'activo' => 'boolean',
            'imagen' => 'nullable|image|max:2048',
        ]);

        // Manejar imagen si se envía
        if ($request->hasFile('imagen')) {
            $validated['imagen'] = $request->file('imagen')->store('efectos', 'public');
        }

        Efecto::create($validated);

        return redirect()->route('efectos.index')->with('success', 'Efecto creado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Efecto $efecto)
    {
        return view('efectos.show', compact('efecto'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Efecto $efecto)
    {
        $tipos = ['positivo', 'negativo', 'neutral'];
        return view('efectos.edit', compact('efecto', 'tipos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Efecto $efecto)
    {
        $validated = $request->validate([
            'nombre' => 'required|max:100|unique:efectos,nombre,' . $efecto->id,
            'descripcion' => 'nullable|string',
            'tipo' => 'required|in:positivo,negativo,neutral',
            'activo' => 'boolean',
            'imagen' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('imagen')) {
            $validated['imagen'] = $request->file('imagen')->store('efectos', 'public');
        }

        $efecto->update($validated);

        return redirect()->route('efectos.index')->with('success', 'Efecto actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Efecto $efecto)
    {
        $efecto->delete();

        return redirect()->route('efectos.index')->with('success', 'Efecto eliminado.');
    }
}
