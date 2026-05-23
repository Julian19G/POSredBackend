@extends('layouts.auth')

@section('title', 'Nueva contraseña')

@section('content')
<h4 class="fw-bold mb-4 text-center">Nueva contraseña</h4>

<form action="{{ route('password.update') }}" method="POST">
    @csrf
    <input type="hidden" name="token" value="{{ $token }}">

    <div class="mb-3">
        <label class="form-label fw-semibold">Correo electrónico</label>
        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
               value="{{ old('email', request('email')) }}" required autofocus>
        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="mb-3">
        <label class="form-label fw-semibold">Nueva contraseña</label>
        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
               required autocomplete="new-password">
        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="mb-4">
        <label class="form-label fw-semibold">Confirmar contraseña</label>
        <input type="password" name="password_confirmation" class="form-control" required>
    </div>

    <button type="submit" class="btn btn-dark w-100 fw-semibold">Restablecer contraseña</button>
</form>
@endsection
