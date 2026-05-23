@extends('layouts.app')

@section('content')
<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-4 mt-3">
        <h1 class="mb-0">Agregar stock — {{ $producto->nombre }}</h1>
        <a href="{{ route('productos.show', $producto) }}" class="btn btn-secondary">← Volver</a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <ul class="mb-0">
                @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4" style="max-width:520px">
        <div class="card-body p-4">

            <form action="{{ route('inventarios.store', $producto) }}" method="POST">
                @csrf

                {{-- Variante (si el producto tiene variantes) --}}
                @if($producto->variantes->count())
                <div class="mb-3">
                    <label class="form-label fw-semibold">Variante <small class="text-muted">(opcional — deja vacío para agregar solo al stock base)</small></label>
                    <select name="variante_id" id="variante_id" class="form-select">
                        <option value="">— Solo stock base del producto —</option>
                        @foreach($producto->variantes as $v)
                            <option value="{{ $v->id }}"
                                    data-consume="{{ $v->cantidad_por_variante }}"
                                    {{ old('variante_id') == $v->id ? 'selected' : '' }}>
                                {{ $v->nombre }} (stock actual: {{ $v->stock }},
                                consume {{ $v->cantidad_por_variante }} uds base c/u)
                            </option>
                        @endforeach
                    </select>
                    <div id="variante-info" class="form-text mt-1"></div>
                </div>
                @else
                <input type="hidden" name="variante_id" value="">
                @endif

                {{-- Cantidad --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Cantidad a agregar</label>
                    <input type="number" name="cantidad" id="cantidad"
                           class="form-control @error('cantidad') is-invalid @enderror"
                           min="1" value="{{ old('cantidad', 1) }}" required>
                    <div id="stock-preview" class="form-text mt-1 text-success"></div>
                    @error('cantidad') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Descripción --}}
                <div class="mb-4">
                    <label class="form-label fw-semibold">Motivo / Descripción <small class="text-muted">(opcional)</small></label>
                    <input type="text" name="descripcion"
                           class="form-control @error('descripcion') is-invalid @enderror"
                           placeholder="Ej: compra de proveedor, corrección de inventario…"
                           value="{{ old('descripcion') }}">
                    @error('descripcion') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-success px-4">✅ Confirmar entrada</button>
                    <a href="{{ route('productos.show', $producto) }}" class="btn btn-outline-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
const stockBaseActual = {{ $producto->stock }};
const selectVariante  = document.getElementById('variante_id');
const inputCantidad   = document.getElementById('cantidad');
const infoDiv         = document.getElementById('variante-info');
const previewDiv      = document.getElementById('stock-preview');

function actualizar() {
    const opt      = selectVariante?.options[selectVariante.selectedIndex];
    const cantidad = parseInt(inputCantidad.value) || 0;
    const consume  = parseInt(opt?.dataset?.consume || 0);

    if (opt && opt.value && consume > 0) {
        infoDiv.textContent = `Al agregar ${cantidad} pack(s) → se suman ${cantidad * consume} unidades al stock base.`;
    } else if (infoDiv) {
        infoDiv.textContent = '';
    }

    if (cantidad > 0) {
        const nuevaBase = opt?.value
            ? stockBaseActual + (cantidad * consume)
            : stockBaseActual + cantidad;
        previewDiv.textContent = `Stock base: ${stockBaseActual} → ${nuevaBase}`;
    } else {
        previewDiv.textContent = '';
    }
}

selectVariante?.addEventListener('change', actualizar);
inputCantidad.addEventListener('input', actualizar);
actualizar();
</script>
@endsection
