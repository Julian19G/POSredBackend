<?php

namespace App\Http\Controllers;

use App\Models\Color;
use Illuminate\Http\Request;

class ColorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $colores = Color::orderBy('id', 'DESC')->paginate(10);
        return view('colores.index', compact('colores'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('colores.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|max:100|unique:colores,nombre',
            'codigo_hex' => 'nullable|regex:/^#([A-Fa-f0-9]{6})$/',
            'descripcion' => 'nullable|string',
            'activo' => 'required|in:0,1'
        ]);

        Color::create($validated);

        return redirect()->route('colores.index')->with('success', 'Color creado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Color $color)
    {
        return view('colores.show', compact('color'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Color $color)
    {
        return view('colores.edit', compact('color'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Color $color)
    {
        $validated = $request->validate([
            'nombre' => 'required|max:100|unique:colores,nombre,' . $color->id,
            'codigo_hex' => 'nullable|regex:/^#([A-Fa-f0-9]{6})$/',
            'descripcion' => 'nullable|string',
            'activo' => 'boolean'
        ]);

        $color->update($validated);

        return redirect()->route('colores.index')->with('success', 'Color actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Color $color)
    {
        $color->delete();
        return redirect()->route('colores.index')->with('success', 'Color eliminado.');
    }
}
