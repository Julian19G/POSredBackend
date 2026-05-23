@extends('layouts.auth')

@section('title', 'Recuperar contraseña')

@section('content')
<h4 class="fw-bold mb-2 text-center">Recuperar contraseña</h4>
<p class="text-muted small text-center mb-4">
    Ingresa tu correo y te enviaremos un enlace para restablecer tu contraseña.
</p>

@if(session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif

<form action="{{ route('password.email') }}" method="POST">
    @csrf

    <div class="mb-4">
        <label class="form-label fw-semibold">Correo electrónico</label>
        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
               value="{{ old('email') }}" autofocus required>
        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <button type="submit" class="btn btn-dark w-100 fw-semibold">Enviar enlace</button>
</form>
@endsection

@section('footer-links')
    <a href="{{ route('login') }}">← Volver al inicio de sesión</a>
@endsection
