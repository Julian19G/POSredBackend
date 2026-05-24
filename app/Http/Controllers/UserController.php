<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Vendedor;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private function requireAdmin(): void
    {
        abort_if(!auth()->user()->isAdmin(), 403, 'Solo los administradores pueden acceder a esta sección.');
    }

    public function index()
    {
        $this->requireAdmin();

        $users = User::with('vendedor')
            ->withCount('vendedor as tiene_vendedor')
            ->orderBy('name')
            ->get();

        return view('users.index', compact('users'));
    }

    public function edit(User $user)
    {
        $this->requireAdmin();

        // Vendedores sin usuario asignado, más el actual de este user
        $vendedoresDisponibles = Vendedor::where(function ($q) use ($user) {
            $q->whereNull('user_id')->orWhere('user_id', $user->id);
        })->orderBy('nombre')->get();

        $vendedorActual = Vendedor::where('user_id', $user->id)->first();

        return view('users.edit', compact('user', 'vendedoresDisponibles', 'vendedorActual'));
    }

    public function update(Request $request, User $user)
    {
        $this->requireAdmin();

        $request->validate([
            'role'        => 'required|in:admin,vendedor',
            'vendedor_id' => 'nullable|exists:vendedores,id',
        ]);

        // Deslinkar cualquier vendedor que tuviera este user_id
        Vendedor::where('user_id', $user->id)->update(['user_id' => null]);

        // Vincular al nuevo vendedor si se seleccionó
        if ($request->filled('vendedor_id')) {
            Vendedor::findOrFail($request->vendedor_id)->update(['user_id' => $user->id]);
        }

        $user->update(['role' => $request->role]);

        return redirect()->route('users.index')
            ->with('success', "Usuario {$user->name} actualizado correctamente.");
    }

    public function destroy(User $user)
    {
        $this->requireAdmin();

        abort_if($user->id === auth()->id(), 403, 'No puedes eliminarte a ti mismo.');

        // Deslinkar vendedor antes de eliminar
        Vendedor::where('user_id', $user->id)->update(['user_id' => null]);

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', "Usuario eliminado.");
    }
}
