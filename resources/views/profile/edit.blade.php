@extends('layouts.app')

@section('content')
<div class="container py-4" style="max-width:640px">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">⚙️ Mi perfil</h1>
        <span class="badge {{ $user->isAdmin() ? 'bg-danger' : 'bg-info text-dark' }} fs-6">
            {{ ucfirst($user->role) }}
        </span>
    </div>

    {{-- Datos personales --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-header bg-white border-0 pt-3">
            <h6 class="fw-bold mb-0">Datos de la cuenta</h6>
        </div>
        <div class="card-body">

            @if(session('success'))
                <div class="alert alert-success alert-dismissible">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form action="{{ route('profile.update') }}" method="POST">
                @csrf @method('PATCH')

                <div class="mb-3">
                    <label class="form-label fw-semibold">Nombre</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name', $user->name) }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Correo electrónico</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email', $user->email) }}" required>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <button type="submit" class="btn btn-primary">Guardar cambios</button>
            </form>
        </div>
    </div>

    {{-- Cambiar contraseña --}}
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white border-0 pt-3">
            <h6 class="fw-bold mb-0">Cambiar contraseña</h6>
        </div>
        <div class="card-body">

            @if(session('success_password'))
                <div class="alert alert-success alert-dismissible">
                    {{ session('success_password') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form action="{{ route('profile.password') }}" method="POST">
                @csrf @method('PATCH')

                <div class="mb-3">
                    <label class="form-label fw-semibold">Contraseña actual</label>
                    <input type="password" name="current_password"
                           class="form-control @error('current_password') is-invalid @enderror" required>
                    @error('current_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Nueva contraseña</label>
                    <input type="password" name="password"
                           class="form-control @error('password') is-invalid @enderror"
                           required autocomplete="new-password">
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <div class="form-text">Mínimo 8 caracteres.</div>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Confirmar nueva contraseña</label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-warning">Cambiar contraseña</button>
            </form>
        </div>
    </div>

</div>
@endsection
