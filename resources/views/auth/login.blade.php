@extends('layouts.auth')

@section('title', 'Iniciar sesión')

@section('content')
<h4 class="fw-bold mb-4 text-center">Iniciar sesión</h4>

@if(session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif

<form action="{{ route('login.post') }}" method="POST">
    @csrf

    <div class="mb-3">
        <label class="form-label fw-semibold">Correo electrónico</label>
        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
               value="{{ old('email') }}" autofocus required autocomplete="email">
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <div class="d-flex justify-content-between">
            <label class="form-label fw-semibold">Contraseña</label>
            <a href="{{ route('password.request') }}" class="small">¿Olvidaste tu contraseña?</a>
        </div>
        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
               required autocomplete="current-password">
        @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-4 form-check">
        <input type="checkbox" name="remember" id="remember" class="form-check-input">
        <label class="form-check-label" for="remember">Recordarme</label>
    </div>

    <button type="submit" class="btn btn-dark w-100 fw-semibold">Entrar</button>
</form>
@endsection

@section('footer-links')
    ¿No tienes cuenta? <a href="{{ route('register') }}">Regístrate</a>
@endsection
