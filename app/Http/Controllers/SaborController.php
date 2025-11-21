<?php

namespace App\Http\Controllers;

use App\Models\Sabor;
use Illuminate\Http\Request;

class SaborController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sabores = Sabor::orderBy('id', 'DESC')->paginate(10);
        return view('sabores.index', compact('sabores'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('sabores.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|max:100|unique:sabores,nombre',
            'descripcion' => 'nullable|string',
            'intensidad' => 'nullable|integer|min:1|max:10',
            'activo' => 'boolean',
            'imagen' => 'nullable|image|max:2048'
        ]);

        // Guardar imagen si existe
        if ($request->hasFile('imagen')) {
            $validated['imagen'] = $request->file('imagen')->store('sabores', 'public');
        }

        Sabor::create($validated);

        return redirect()->route('sabores.index')->with('success', 'Sabor creado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Sabor $sabor)
    {
        return view('sabores.show', compact('sabor'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sabor $sabor)
    {
        return view('sabores.edit', compact('sabor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Sabor $sabor)
    {
        $validated = $request->validate([
            'nombre' => 'required|max:100|unique:sabores,nombre,' . $sabor->id,
            'descripcion' => 'nullable|string',
            'intensidad' => 'nullable|integer|min:1|max:10',
            'activo' => 'boolean',
            'imagen' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('imagen')) {
            $validated['imagen'] = $request->file('imagen')->store('sabores', 'public');
        }

        $sabor->update($validated);

        return redirect()->route('sabores.index')->with('success', 'Sabor actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sabor $sabor)
    {
        $sabor->delete();

        return redirect()->route('sabores.index')->with('success', 'Sabor eliminado correctamente.');
    }
}
