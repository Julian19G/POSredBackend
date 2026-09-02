<?php

namespace App\Http\Controllers;

use App\Models\Domiciliario;
use App\Models\User;
use App\Models\Venta;
use App\Models\TarifaDomicilio;
use App\Models\Domicilio;
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

        $ventasDisponibles = Venta::with('cliente')
            ->whereDoesntHave('domicilio')
            ->latest()
            ->limit(50)
            ->get();

        return view('domiciliarios.show', compact('d', 'ventasDisponibles'));
    }

    public function registrarEntregaManual(Request $request, $id)
    {
        $d = Domiciliario::findOrFail($id);
        $user = auth()->user();
        abort_unless($user->isAdmin() || $user->domiciliario?->id === $d->id, 403);

        $data = $request->validate([
            'venta_id'  => 'nullable|exists:ventas,id',
            'cliente_nombre' => 'required_without:venta_id|nullable|string|max:150',
            'cliente_telefono' => 'required_without:venta_id|nullable|string|max:30',
            'direccion' => 'required|string|max:255',
            'ciudad'    => 'nullable|string|max:100',
            'comentarios' => 'nullable|string|max:500',
        ]);

        $venta = !empty($data['venta_id']) ? Venta::findOrFail($data['venta_id']) : null;
        if ($venta && $venta->domicilio()->exists()) {
            return back()->withErrors(['venta_id' => 'Esta venta ya tiene un domicilio registrado.']);
        }

        $tarifa = TarifaDomicilio::vigente();
        Domicilio::create([
            'venta_id'              => $venta?->id,
            'origen'                => $venta ? 'venta' : 'externa',
            'cliente_nombre'        => $data['cliente_nombre'] ?? null,
            'cliente_telefono'      => $data['cliente_telefono'] ?? null,
            'domiciliario_id'       => $d->id,
            'direccion'             => $data['direccion'],
            'ciudad'                => $data['ciudad'] ?? null,
            'pais'                  => 'Colombia',
            'estado'                => 'entregado',
            'costo_envio'           => $tarifa?->monto ?? 15000,
            'tarifa_id'             => $tarifa?->id,
            'tarifa_monto'          => $tarifa?->monto ?? 15000,
            'fecha_aceptacion'      => now(),
            'fecha_recogida'        => now(),
            'fecha_entrega_real'    => now(),
            'comentarios'           => $data['comentarios'] ?? 'Registrado manualmente.',
            'cobrar_en_entrega'     => false,
        ]);

        return redirect()->route('domiciliarios.show', $d)
            ->with('success', 'Entrega manual registrada correctamente.');
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
