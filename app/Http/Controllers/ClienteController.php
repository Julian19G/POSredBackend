<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Venta;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function index(Request $request)
    {
        $query = Cliente::with('referidoPor');

        if ($request->filled('buscar')) {
            $b = $request->buscar;
            $query->where(function ($q) use ($b) {
                $q->where('nombre',    'like', "%{$b}%")
                  ->orWhere('telefono', 'like', "%{$b}%")
                  ->orWhere('whatsapp', 'like', "%{$b}%")
                  ->orWhere('email',    'like', "%{$b}%")
                  ->orWhere('instagram','like', "%{$b}%");
            });
        }

        $clientes = $query->orderBy('nombre')->paginate(20)->withQueryString();
        return view('clientes.index', compact('clientes'));
    }

    public function create()
    {
        $clientes = Cliente::orderBy('nombre')->get();
        return view('clientes.create', compact('clientes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre'           => 'required|string|max:100',
            'telefono'         => 'required|string|max:20|unique:clientes,telefono',
            'whatsapp'         => 'nullable|string|max:20',
            'instagram'        => 'nullable|string|max:100',
            'email'            => 'nullable|email|max:100|unique:clientes,email',
            'direccion'        => 'nullable|string|max:255',
            'barrio'           => 'nullable|string|max:100',
            'ciudad'           => 'nullable|string|max:100',
            'fecha_nacimiento' => 'nullable|date',
            'notas'            => 'nullable|string',
            'referido_por'     => 'nullable|exists:clientes,id',
        ]);

        Cliente::create($validated);

        return redirect()->route('clientes.index')
            ->with('success', 'Cliente creado correctamente.');
    }

    public function show(string $id)
    {
        $cliente = Cliente::with(['referidoPor', 'referidos'])->findOrFail($id);
        $ventas  = Venta::with('pedido')
                    ->where('cliente_id', $id)
                    ->latest()
                    ->paginate(10, ['*'], 'ventas_page');

        $totalGastado = Venta::where('cliente_id', $id)->where('estado', '!=', 'cancelada')->sum('total');
        $pendientes   = Venta::where('cliente_id', $id)->where('estado', 'pendiente')->count();

        return view('clientes.show', compact('cliente', 'ventas', 'totalGastado', 'pendientes'));
    }

    public function edit(string $id)
    {
        $cliente  = Cliente::findOrFail($id);
        $clientes = Cliente::where('id', '!=', $id)->orderBy('nombre')->get();
        return view('clientes.edit', compact('cliente', 'clientes'));
    }

    public function update(Request $request, string $id)
    {
        $cliente = Cliente::findOrFail($id);

        $validated = $request->validate([
            'nombre'           => 'required|string|max:100',
            'telefono'         => 'required|string|max:20|unique:clientes,telefono,' . $id,
            'whatsapp'         => 'nullable|string|max:20',
            'instagram'        => 'nullable|string|max:100',
            'email'            => 'nullable|email|max:100|unique:clientes,email,' . $id,
            'direccion'        => 'nullable|string|max:255',
            'barrio'           => 'nullable|string|max:100',
            'ciudad'           => 'nullable|string|max:100',
            'fecha_nacimiento' => 'nullable|date',
            'notas'            => 'nullable|string',
            'referido_por'     => ['nullable', 'exists:clientes,id', "not_in:{$id}"],
        ]);

        $cliente->update($validated);

        return redirect()->route('clientes.index')
            ->with('success', 'Cliente actualizado correctamente.');
    }

    public function destroy(string $id)
    {
        $cliente = Cliente::findOrFail($id);
        $cliente->delete();
        return redirect()->route('clientes.index')
            ->with('success', 'Cliente eliminado correctamente.');
    }
}
