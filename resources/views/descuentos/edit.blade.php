@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>✏️ Editar Descuento</h1>
        <a href="{{ route('descuentos.index') }}" class="btn btn-secondary">← Volver</a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-4">
            <form action="{{ route('descuentos.update', $descuento) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-3">

                    {{-- Nombre --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Nombre</label>
                        <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror"
                               value="{{ old('nombre', $descuento->nombre) }}" required>
                        @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Código --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Código</label>
                        <input type="text" name="codigo" class="form-control @error('codigo') is-invalid @enderror"
                               value="{{ old('codigo', $descuento->codigo) }}" required>
                        @error('codigo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Tipo --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Tipo</label>
                        <select name="tipo" class="form-select @error('tipo') is-invalid @enderror" required>
                            <option value="porcentaje" {{ old('tipo', $descuento->tipo) === 'porcentaje' ? 'selected' : '' }}>Porcentaje (%)</option>
                            <option value="fijo"       {{ old('tipo', $descuento->tipo) === 'fijo'       ? 'selected' : '' }}>Valor fijo ($)</option>
                        </select>
                        @error('tipo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Valor --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Valor</label>
                        <input type="number" step="0.01" min="0" name="valor"
                               class="form-control @error('valor') is-invalid @enderror"
                               value="{{ old('valor', $descuento->valor) }}" required>
                        @error('valor') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Fecha inicio --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Fecha inicio</label>
                        <input type="date" name="fecha_inicio"
                               class="form-control @error('fecha_inicio') is-invalid @enderror"
                               value="{{ old('fecha_inicio', $descuento->fecha_inicio->format('Y-m-d')) }}" required>
                        @error('fecha_inicio') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Fecha fin --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Fecha fin</label>
                        <input type="date" name="fecha_fin"
                               class="form-control @error('fecha_fin') is-invalid @enderror"
                               value="{{ old('fecha_fin', $descuento->fecha_fin->format('Y-m-d')) }}" required>
                        @error('fecha_fin') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Uso máximo total --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Uso máximo total <small class="text-muted">(opcional)</small></label>
                        <input type="number" min="1" name="uso_maximo"
                               class="form-control @error('uso_maximo') is-invalid @enderror"
                               value="{{ old('uso_maximo', $descuento->uso_maximo) }}" placeholder="Sin límite">
                        @error('uso_maximo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Uso máximo por cliente --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Uso máximo por cliente <small class="text-muted">(opcional)</small></label>
                        <input type="number" min="1" name="uso_cliente_maximo"
                               class="form-control @error('uso_cliente_maximo') is-invalid @enderror"
                               value="{{ old('uso_cliente_maximo', $descuento->uso_cliente_maximo) }}" placeholder="Sin límite">
                        @error('uso_cliente_maximo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Checkboxes --}}
                    <div class="col-12 d-flex gap-4 pt-1">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="activo" value="1"
                                   {{ old('activo', $descuento->activo) ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold">Activo</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="aplicable_manual" value="1"
                                   {{ old('aplicable_manual', $descuento->aplicable_manual) ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold">Aplicable manualmente</label>
                        </div>
                    </div>

                </div>

                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-warning px-4">💾 Actualizar</button>
                    <a href="{{ route('descuentos.index') }}" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
