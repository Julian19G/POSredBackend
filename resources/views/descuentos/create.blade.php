@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>➕ Crear Descuento</h1>
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
            <form action="{{ route('descuentos.store') }}" method="POST">
                @csrf

                <div class="row g-3">

                    {{-- Nombre --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Nombre</label>
                        <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror"
                               value="{{ old('nombre') }}" required>
                        @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Tipo --}}
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Tipo</label>
                        <select name="tipo" class="form-select @error('tipo') is-invalid @enderror" required>
                            <option value="porcentaje" {{ old('tipo') === 'porcentaje' ? 'selected' : '' }}>Porcentaje (%)</option>
                            <option value="fijo"       {{ old('tipo') === 'fijo'       ? 'selected' : '' }}>Valor fijo ($)</option>
                        </select>
                        @error('tipo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Valor --}}
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Valor</label>
                        <input type="number" step="0.01" min="0" name="valor"
                               class="form-control @error('valor') is-invalid @enderror"
                               value="{{ old('valor') }}" required>
                        @error('valor') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Fecha inicio --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Fecha inicio</label>
                        <input type="date" name="fecha_inicio"
                               class="form-control @error('fecha_inicio') is-invalid @enderror"
                               value="{{ old('fecha_inicio') }}" required>
                        @error('fecha_inicio') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Fecha fin --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Fecha fin</label>
                        <input type="date" name="fecha_fin"
                               class="form-control @error('fecha_fin') is-invalid @enderror"
                               value="{{ old('fecha_fin') }}" required>
                        @error('fecha_fin') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Checkboxes de estado --}}
                    <div class="col-12 d-flex gap-4 pt-1">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="activo" value="1"
                                   {{ old('activo', '1') ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold">Activo</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="aplicable_manual" name="aplicable_manual" value="1"
                                   {{ old('aplicable_manual') ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold">Es un cupón manual (el cliente ingresa un código)</label>
                        </div>
                    </div>

                    <hr class="mt-2">

                    {{-- Bloque: cupón manual --}}
                    <div id="bloque-manual" class="col-12" style="display: none;">
                        <div class="alert alert-info small mb-2">
                            El cliente deberá ingresar este código en el checkout para aplicar el descuento.
                        </div>
                        <label class="form-label fw-semibold">Código</label>
                        <input type="text" name="codigo" class="form-control @error('codigo') is-invalid @enderror"
                               value="{{ old('codigo') }}">
                        @error('codigo') <div class="invalid-feedback">{{ $message }}</div> @enderror

                        <div class="row g-3 mt-1">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Uso máximo total <small class="text-muted">(opcional)</small></label>
                                <input type="number" min="1" name="uso_maximo"
                                       class="form-control @error('uso_maximo') is-invalid @enderror"
                                       value="{{ old('uso_maximo') }}" placeholder="Sin límite">
                                @error('uso_maximo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Uso máximo por cliente <small class="text-muted">(opcional)</small></label>
                                <input type="number" min="1" name="uso_cliente_maximo"
                                       class="form-control @error('uso_cliente_maximo') is-invalid @enderror"
                                       value="{{ old('uso_cliente_maximo') }}" placeholder="Sin límite">
                                @error('uso_cliente_maximo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Bloque: descuento automático por producto/categoría --}}
                    <div id="bloque-automatico" class="col-12">
                        <div class="alert alert-info small mb-2">
                            Se aplicará automáticamente en el catálogo, sin que el cliente haga nada.
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Productos</label>
                                <select name="productos[]" class="form-select @error('productos') is-invalid @enderror" multiple size="8">
                                    @foreach($productos as $producto)
                                        <option value="{{ $producto->id }}"
                                            {{ collect(old('productos'))->contains($producto->id) ? 'selected' : '' }}>
                                            {{ $producto->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Ctrl/Cmd + clic para elegir varios</small>
                                @error('productos') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Categorías</label>
                                <select name="categorias[]" class="form-select @error('categorias') is-invalid @enderror" multiple size="8">
                                    @foreach($categorias as $categoria)
                                        <option value="{{ $categoria->id }}"
                                            {{ collect(old('categorias'))->contains($categoria->id) ? 'selected' : '' }}>
                                            {{ $categoria->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Ctrl/Cmd + clic para elegir varios</small>
                                @error('categorias') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>

                </div>

                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary px-4">💾 Guardar</button>
                    <a href="{{ route('descuentos.index') }}" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const checkboxManual = document.getElementById('aplicable_manual');
    const bloqueManual = document.getElementById('bloque-manual');
    const bloqueAutomatico = document.getElementById('bloque-automatico');

    function toggleBloques() {
        const esManual = checkboxManual.checked;
        bloqueManual.style.display = esManual ? 'block' : 'none';
        bloqueAutomatico.style.display = esManual ? 'none' : 'block';
    }

    checkboxManual.addEventListener('change', toggleBloques);
    toggleBloques(); // estado inicial
</script>
@endpush
@endsection