@extends('layouts.app')

@section('content')
<div class="container" style="max-width:520px">
    <div class="mt-4 mb-3">
        <h1>Editar Domiciliario</h1>
    </div>

    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-4">
            <form action="{{ route('domiciliarios.update', $d) }}" method="POST">
                @csrf @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Nombre completo</label>
                    <input type="text" name="nombre" class="form-control"
                           value="{{ old('nombre', $d->nombre) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Teléfono</label>
                    <input type="text" name="telefono" class="form-control"
                           value="{{ old('telefono', $d->telefono) }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Vehículo</label>
                    <select name="vehiculo" class="form-select" required>
                        @foreach(['moto' => '🏍 Moto', 'bicicleta' => '🚲 Bicicleta', 'pie' => '🚶 A pie', 'carro' => '🚗 Carro'] as $val => $label)
                            <option value="{{ $val }}" {{ old('vehiculo', $d->vehiculo) === $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-check form-switch mb-4">
                    <input class="form-check-input" type="checkbox" name="activo" id="activo"
                           {{ old('activo', $d->activo) ? 'checked' : '' }}>
                    <label class="form-check-label" for="activo">Activo</label>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-fill">Guardar cambios</button>
                    <a href="{{ route('domiciliarios.show', $d) }}" class="btn btn-outline-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
