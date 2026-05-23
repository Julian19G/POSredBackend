@extends('layouts.auth')

@section('title', 'Registrarse')

@section('content')
<h4 class="fw-bold mb-4 text-center">Crear cuenta</h4>

@if($esElPrimero)
    <div class="alert alert-info small">
        🎉 Serás el primer usuario — tu cuenta tendrá rol de <strong>admin</strong>.
    </div>
@endif

<form action="{{ route('register.post') }}" method="POST">
    @csrf

    <div class="mb-3">
        <label class="form-label fw-semibold">Nombre</label>
        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
               value="{{ old('name') }}" autofocus required>
        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="mb-3">
        <label class="form-label fw-semibold">Correo electrónico</label>
        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
               value="{{ old('email') }}" required>
        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="mb-3">
        <label class="form-label fw-semibold">Contraseña</label>
        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
               required autocomplete="new-password">
        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
        <div class="form-text">Mínimo 8 caracteres.</div>
    </div>

    <div class="mb-4">
        <label class="form-label fw-semibold">Confirmar contraseña</label>
        <input type="password" name="password_confirmation" class="form-control" required>
    </div>

    <button type="submit" class="btn btn-dark w-100 fw-semibold">Crear cuenta</button>
</form>
@endsection

@section('footer-links')
    ¿Ya tienes cuenta? <a href="{{ route('login') }}">Inicia sesión</a>
@endsection
