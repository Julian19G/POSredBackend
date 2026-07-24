<?php

namespace App\Http\Controllers;

use App\Models\TipoFlor;
use Illuminate\Http\Request;

class TipoFlorController extends Controller
{
    public function index()
    {
        abort_if(!auth()->user()->isAdmin(), 403);
        $tipos = TipoFlor::orderBy('nombre')->get();
        return view('tipos_flor.index', compact('tipos'));
    }

    public function store(Request $request)
    {
        abort_if(!auth()->user()->isAdmin(), 403);
        $data = $request->validate([
            'nombre'      => 'required|string|max:60|unique:tipos_flor,nombre',
            'icono'       => 'nullable|string|max:16',
            'descripcion' => 'nullable|string|max:255',
        ]);
        $data['activo'] = true;
        TipoFlor::create($data);

        return back()->with('success', 'Tipo de flor creado.');
    }

    public function destroy(TipoFlor $tipoFlor)
    {
        abort_if(!auth()->user()->isAdmin(), 403);
        $tipoFlor->delete();
        return back()->with('success', 'Tipo de flor eliminado.');
    }
}
