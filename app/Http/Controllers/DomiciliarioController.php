<?php

namespace App\Http\Controllers;

use App\Models\Domiciliario;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DomiciliarioController extends Controller
{
    public function index()
    {
        abort_if(!auth()->user()->isAdmin(), 403);

        $domiciliarios = Domiciliario::with('user')
            ->withCount('domicilios')
            ->orderBy('nombre')
            ->paginate(20);

        return view('domiciliarios.index', compact('domiciliarios'));
    }

    public function create()
    {
        abort_if(!auth()->user()->isAdmin(), 403);
        return view('domiciliarios.create');
    }

    public function store(Request $request)
    {
        abort_if(!auth()->user()->isAdmin(), 403);

        $request->validate([
            'nombre'    => 'required|string|max:100',
            'telefono'  => 'nullable|string|max:20',
            'vehiculo'  => 'required|in:moto,bicicleta,pie,carro',
            'email'     => 'required|email|unique:users,email',
            'password'  => 'required|string|min:8|confirmed',
        ]);

        DB::transaction(function () use ($request) {
            $user = User::create([
                'name'     => $request->nombre,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
                'role'     => 'domiciliario',
            ]);

            Domiciliario::create([
                'user_id'  => $user->id,
                'nombre'   => $request->nombre,
                'telefono' => $request->telefono,
                'vehiculo' => $request->vehiculo,
                'activo'   => true,
            ]);
        });

        return redirect()->route('domiciliarios.index')
            ->with('success', 'Domiciliario registrado correctamente.');
    }

    public function show($id)
    {
        $d = Domiciliario::with(['user', 'rutas' => fn($q) => $q->latest()->limit(10)])
            ->withCount(['domicilios', 'domicilios as entregas_count' => fn($q) => $q->where('estado', 'entregado')])
            ->findOrFail($id);

        // El propio domiciliario puede verse a sí mismo
        if (!auth()->user()->isAdmin() && auth()->user()->domiciliario?->id !== $d->id) {
            abort(403);
        }

        return view('domiciliarios.show', compact('d'));
    }

    public function edit($id)
    {
        abort_if(!auth()->user()->isAdmin(), 403);
        $d = Domiciliario::findOrFail($id);
        return view('domiciliarios.edit', compact('d'));
    }

    public function update(Request $request, $id)
    {
        abort_if(!auth()->user()->isAdmin(), 403);

        $d = Domiciliario::findOrFail($id);

        $request->validate([
            'nombre'   => 'required|string|max:100',
            'telefono' => 'nullable|string|max:20',
            'vehiculo' => 'required|in:moto,bicicleta,pie,carro',
            'activo'   => 'boolean',
        ]);

        $d->update($request->only(['nombre', 'telefono', 'vehiculo']) + [
            'activo' => $request->boolean('activo'),
        ]);

        // Sync user name
        $d->user->update(['name' => $request->nombre]);

        return redirect()->route('domiciliarios.show', $id)
            ->with('success', 'Domiciliario actualizado.');
    }

    public function destroy($id)
    {
        abort_if(!auth()->user()->isAdmin(), 403);
        $d = Domiciliario::findOrFail($id);
        $d->user->update(['role' => 'vendedor']); // degrade role
        $d->delete();
        return redirect()->route('domiciliarios.index')
            ->with('success', 'Domiciliario eliminado.');
    }
}
