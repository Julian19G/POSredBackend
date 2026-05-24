<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Vendedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function showForm()
    {
        return view('auth.register', ['esElPrimero' => User::count() === 0]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'                  => 'required|string|max:150',
            'email'                 => 'required|email|unique:users,email',
            'password'              => 'required|string|min:8|confirmed',
        ]);

        // El primer usuario registrado es automáticamente admin
        $role = User::count() === 0 ? 'admin' : 'vendedor';

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $role,
        ]);

        // Los vendedores obtienen automáticamente su perfil de vendedor
        if ($role === 'vendedor') {
            Vendedor::create([
                'user_id'             => $user->id,
                'nombre'              => $request->name,
                'email'               => $request->email,
                'activo'              => true,
                'comision_porcentaje' => 0,
            ]);
        }

        Auth::login($user);

        return redirect()->route('dashboard')
            ->with('success', '¡Bienvenido! Tu cuenta fue creada como ' . $role . '.');
    }
}
