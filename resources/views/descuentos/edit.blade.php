@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">✏️ Editar Descuento</h1>

    <form action="{{ route('descuentos.update', $descuento) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Nombre --}}
        <div class="mb-3">
            <label class="form-label">Nombre</label>
            <input type="text" name="nombre" class="form-control"
                   value="{{ $descuento->nombre }}" required>
        </div>

        {{-- Código --}}
        <div class="mb-3">
            <label class="form-label">Código</label>
            <input type="text" name="codigo" class="form-control"
                   value="{{ $descuento->codigo }}" required>
        </div>

        {{-- Tipo --}}
        <div class="mb-3">
            <label class="form-label">Tipo</label>
            <select name="tipo" class="form-select" required>
                <option value="porcentaje" {{ $descuento->tipo === 'porcentaje' ? 'selected' : '' }}>
                    Porcentaje
                </option>
                <option value="fijo" {{ $descuento->tipo === 'fijo' ? 'selected' : '' }}>
                    Valor fijo
                </option>
            </select>
        </div>

        {{-- Valor --}}
        <div class="mb-3">
            <label class="form-label">Valor</label>
            <input type="number" step="0.01" name="valor" class="form-control"
                   value="{{ $descuento->valor }}" required>
        </div>

        {{-- Activo --}}
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" name="activo" value="1"
                   {{ $descuento->activo ? 'checked' : '' }}>
            <label class="form-check-label">Activo</label>
        </div>

        {{-- Aplicable manual --}}
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" name="aplicable_manual" value="1"
                   {{ $descuento->aplicable_manual ? 'checked' : '' }}>
            <label class="form-check-label">Aplicable manualmente</label>
        </div>

        {{-- Fecha inicio --}}
        <div class="mb-3">
            <label class="form-label">Fecha inicio</label>
            <input type="date" name="fecha_inicio" class="form-control"
                   value="{{ $descuento->fecha_inicio->format('Y-m-d') }}" required>
        </div>

        {{-- Fecha fin --}}
        <div class="mb-3">
            <label class="form-label">Fecha fin</label>
            <input type="date" name="fecha_fin" class="form-control"
                   value="{{ $descuento->fecha_fin->format('Y-m-d') }}" required>
        </div>

        {{-- Uso máximo --}}
        <div class="mb-3">
            <label class="form-label">Uso máximo total</label>
            <input type="number" name="uso_maximo" class="form-control"
                   value="{{ $descuento->uso_maximo }}">
        </div>

        {{-- Uso máximo por cliente --}}
        <div class="mb-3">
            <label class="form-label">Uso máximo por cliente</label>
            <input type="number" name="uso_cliente_maximo" class="form-control"
                   value="{{ $descuento->uso_cliente_maximo }}">
        </div>

        <button class="btn btn-warning">Actualizar</button>
        <a href="{{ route('descuentos.index', $descuento) }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
