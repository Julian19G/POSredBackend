<?php

namespace App\Http\Controllers;

use App\Models\Presentacion;
use Illuminate\Http\Request;

class PresentacionController extends Controller
{
    public function index()
    {
        abort_if(!auth()->user()->isAdmin(), 403);
        $presentaciones = Presentacion::orderBy('cantidad')->get();
        return view('presentaciones.index', compact('presentaciones'));
    }

    public function store(Request $request)
    {
        abort_if(!auth()->user()->isAdmin(), 403);
        $data = $request->validate([
            'nombre'   => 'required|string|max:60|unique:presentaciones,nombre',
            'cantidad' => 'required|numeric|gt:0',
        ]);
        $data['activo'] = true;
        Presentacion::create($data);

        return back()->with('success', 'Presentación creada.');
    }

    public function destroy(Presentacion $presentacion)
    {
        abort_if(!auth()->user()->isAdmin(), 403);
        $presentacion->delete();
        return back()->with('success', 'Presentación eliminada.');
    }
}
