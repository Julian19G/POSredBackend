@extends('layouts.app')

@section('content')
<div class="container" style="max-width:580px">
    <div class="mt-4 mb-3">
        <h1>Nuevo Domiciliario</h1>
        <p class="text-muted small">Se creará un usuario con rol <strong>domiciliario</strong> automáticamente.</p>
    </div>

    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-4">
            <form action="{{ route('domiciliarios.store') }}" method="POST">
                @csrf

                <h6 class="text-uppercase text-muted small fw-bold mb-3">Datos personales</h6>

                <div class="mb-3">
                    <label class="form-label">Nombre completo <span class="text-danger">*</span></label>
                    <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror"
                           value="{{ old('nombre') }}" required>
                    @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Teléfono</label>
                    <input type="text" name="telefono" class="form-control"
                           value="{{ old('telefono') }}" placeholder="300 000 0000">
                </div>

                <div class="mb-3">
                    <label class="form-label">Vehículo <span class="text-danger">*</span></label>
                    <select name="vehiculo" class="form-select @error('vehiculo') is-invalid @enderror" required>
                        @foreach(['moto' => '🏍 Moto', 'bicicleta' => '🚲 Bicicleta', 'pie' => '🚶 A pie', 'carro' => '🚗 Carro'] as $val => $label)
                            <option value="{{ $val }}" {{ old('vehiculo') === $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <hr>
                <h6 class="text-uppercase text-muted small fw-bold mb-3">Acceso al sistema</h6>

                <div class="mb-3">
                    <label class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email') }}" required>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="row g-3 mb-4">
                    <div class="col">
                        <label class="form-label">Contraseña <span class="text-danger">*</span></label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col">
                        <label class="form-label">Confirmar contraseña</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-fill">Registrar domiciliario</button>
                    <a href="{{ route('domiciliarios.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
