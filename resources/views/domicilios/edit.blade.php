@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">✏️ Editar Domicilio</h1>

    <form action="{{ route('domicilios.update', $domicilio) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Dirección</label>
            <input type="text" name="direccion" class="form-control"
                   value="{{ $domicilio->direccion }}" required>
        </div>

        <div class="mb-3">
            <label>Ciudad</label>
            <input type="text" name="ciudad" class="form-control"
                   value="{{ $domicilio->ciudad }}">
        </div>

        <div class="mb-3">
            <label>Departamento</label>
            <input type="text" name="departamento" class="form-control"
                   value="{{ $domicilio->departamento }}">
        </div>

        <div class="mb-3">
            <label>País</label>
            <input type="text" name="pais" class="form-control"
                   value="{{ $domicilio->pais }}">
        </div>

        <div class="mb-3">
            <label>Teléfono</label>
            <input type="text" name="telefono" class="form-control"
                   value="{{ $domicilio->telefono }}">
        </div>

        <div class="mb-3">
            <label>Estado</label>
            <select name="estado" class="form-select">
                @foreach(['pendiente','enviado','entregado','cancelado'] as $estado)
                    <option value="{{ $estado }}"
                        {{ $domicilio->estado === $estado ? 'selected' : '' }}>
                        {{ ucfirst($estado) }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Costo envío</label>
            <input type="number" step="0.01" name="costo_envio" class="form-control"
                   value="{{ $domicilio->costo_envio }}">
        </div>

        <div class="mb-3">
            <label>Comentarios</label>
            <textarea name="comentarios" class="form-control">{{ $domicilio->comentarios }}</textarea>
        </div>

        <button class="btn btn-warning">Actualizar</button>
        <a href="{{ route('domicilios.show', $domicilio) }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
