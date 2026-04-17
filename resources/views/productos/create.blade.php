@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Crear Producto</h1>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('productos.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label class="form-label">Nombre</label>
            <input type="text" name="nombre" class="form-control" value="{{ old('nombre') }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Descripción</label>
            <textarea name="descripcion" class="form-control">{{ old('descripcion') }}</textarea>
        </div>

        {{-- ❌ PRECIO ELIMINADO --}}

        <div class="mb-3">
            <label class="form-label">Stock base</label>
            <input type="number" name="stock" class="form-control" value="{{ old('stock') }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Categoría</label>
            <select name="categoria_id" class="form-select">
                <option value="">-- Selecciona una categoría --</option>
                @foreach($categorias as $categoria)
                    <option value="{{ $categoria->id }}"
                        {{ old('categoria_id') == $categoria->id ? 'selected' : '' }}>
                        {{ $categoria->nombre }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Imagen</label>
            <input type="file" name="imagen" class="form-control" accept="image/*">
        </div>

        <div class="mb-3">
            <label class="form-label">Estado</label>
            <select class="form-select" name="activo">
                <option value="1" {{ old('activo', 1) == 1 ? 'selected' : '' }}>Activo</option>
                <option value="0" {{ old('activo') == 0 ? 'selected' : '' }}>Inactivo</option>
            </select>
        </div>

        {{-- SABORES --}}
        <div class="mb-3">
            <label class="form-label">Sabores</label>
            <div id="sabores-container">
                <div class="d-flex gap-2 mb-2">
                    <select name="sabores[]" class="form-select">
                        @foreach($sabores as $sabor)
                            <option value="{{ $sabor->id }}">{{ $sabor->nombre }}</option>
                        @endforeach
                    </select>
                    <button type="button" class="btn btn-danger remove-sabor">X</button>
                </div>
            </div>
            <button type="button" id="add-sabor" class="btn btn-primary btn-sm">Agregar sabor</button>
        </div>

        {{-- EFECTOS --}}
        <div class="mb-3">
            <label class="form-label">Efectos</label>
            <div id="efectos-container">
                <div class="d-flex gap-2 mb-2">
                    <select name="efectos[]" class="form-select">
                        @foreach($efectos as $efecto)
                            <option value="{{ $efecto->id }}">{{ $efecto->nombre }}</option>
                        @endforeach
                    </select>
                    <button type="button" class="btn btn-danger remove-efecto">X</button>
                </div>
            </div>
            <button type="button" id="add-efecto" class="btn btn-primary btn-sm">Agregar efecto</button>
        </div>

        {{-- COLORES --}}
        <div class="mb-3">
            <label class="form-label">Colores</label>
            <div id="colores-container">
                <div class="d-flex gap-2 mb-2">
                    <select name="colores[]" class="form-select">
                        @foreach($colores as $color)
                            <option value="{{ $color->id }}">{{ $color->nombre }}</option>
                        @endforeach
                    </select>
                    <button type="button" class="btn btn-danger remove-color">X</button>
                </div>
            </div>
            <button type="button" id="add-color" class="btn btn-primary btn-sm">Agregar color</button>
        </div>

        {{-- ✅ VARIANTES --}}
        <div class="mb-4">
            <label class="form-label fw-semibold">Variantes / Presentaciones</label>
            <div id="variantes-container">
                <div class="variante-row border rounded p-3 mb-3 bg-light">
                    <div class="row g-2 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label">Nombre</label>
                            <input type="text" name="variantes[0][nombre]" class="form-control"
                                   placeholder="Ej: x10, Paquete, Caja" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Cantidad por variante</label>
                            <input type="number" name="variantes[0][cantidad_por_variante]" 
                                   class="form-control" placeholder="Ej: 10" min="1" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Precio</label>
                            <input type="number" step="0.01" name="variantes[0][precio]" 
                                   class="form-control" placeholder="0.00" min="0" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Stock</label>
                            <input type="number" name="variantes[0][stock]" 
                                   class="form-control" placeholder="0" min="0" required>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="button" class="btn btn-danger w-100 remove-variante">Eliminar</button>
                        </div>
                    </div>
                </div>
            </div>
            <button type="button" id="add-variante" class="btn btn-success btn-sm">
                ➕ Agregar variante
            </button>
        </div>

        <button type="submit" class="btn btn-success">Guardar</button>
        <a href="{{ route('productos.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<script>
    // ── Sabores ──────────────────────────────────────────
    document.getElementById('add-sabor').addEventListener('click', function () {
        let row = document.createElement('div');
        row.classList.add('d-flex', 'gap-2', 'mb-2');
        row.innerHTML = `
            <select name="sabores[]" class="form-select">
                @foreach($sabores as $sabor)
                    <option value="{{ $sabor->id }}">{{ $sabor->nombre }}</option>
                @endforeach
            </select>
            <button type="button" class="btn btn-danger remove-sabor">X</button>`;
        document.getElementById('sabores-container').appendChild(row);
        row.querySelector('.remove-sabor').addEventListener('click', () => row.remove());
    });
    document.querySelectorAll('.remove-sabor').forEach(btn => btn.addEventListener('click', function () {
        this.parentElement.remove();
    }));

    // ── Efectos ──────────────────────────────────────────
    document.getElementById('add-efecto').addEventListener('click', function () {
        let row = document.createElement('div');
        row.classList.add('d-flex', 'gap-2', 'mb-2');
        row.innerHTML = `
            <select name="efectos[]" class="form-select">
                @foreach($efectos as $efecto)
                    <option value="{{ $efecto->id }}">{{ $efecto->nombre }}</option>
                @endforeach
            </select>
            <button type="button" class="btn btn-danger remove-efecto">X</button>`;
        document.getElementById('efectos-container').appendChild(row);
        row.querySelector('.remove-efecto').addEventListener('click', () => row.remove());
    });
    document.querySelectorAll('.remove-efecto').forEach(btn => btn.addEventListener('click', function () {
        this.parentElement.remove();
    }));

    // ── Colores ──────────────────────────────────────────
    document.getElementById('add-color').addEventListener('click', function () {
        let row = document.createElement('div');
        row.classList.add('d-flex', 'gap-2', 'mb-2');
        row.innerHTML = `
            <select name="colores[]" class="form-select">
                @foreach($colores as $color)
                    <option value="{{ $color->id }}">{{ $color->nombre }}</option>
                @endforeach
            </select>
            <button type="button" class="btn btn-danger remove-color">X</button>`;
        document.getElementById('colores-container').appendChild(row);
        row.querySelector('.remove-color').addEventListener('click', () => row.remove());
    });
    document.querySelectorAll('.remove-color').forEach(btn => btn.addEventListener('click', function () {
        this.parentElement.remove();
    }));

    // ── Variantes ─────────────────────────────────────────
    let varianteIndex = 1;

    document.getElementById('add-variante').addEventListener('click', function () {
        const container = document.getElementById('variantes-container');
        const row = document.createElement('div');
        row.classList.add('variante-row', 'border', 'rounded', 'p-3', 'mb-3', 'bg-light');
        row.innerHTML = `
            <div class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">Nombre</label>
                    <input type="text" name="variantes[${varianteIndex}][nombre]" 
                           class="form-control" placeholder="Ej: x10, Paquete, Caja" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Cantidad por variante</label>
                    <input type="number" name="variantes[${varianteIndex}][cantidad_por_variante]" 
                           class="form-control" placeholder="Ej: 10" min="1" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Precio</label>
                    <input type="number" step="0.01" name="variantes[${varianteIndex}][precio]" 
                           class="form-control" placeholder="0.00" min="0" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Stock</label>
                    <input type="number" name="variantes[${varianteIndex}][stock]" 
                           class="form-control" placeholder="0" min="0" required>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="button" class="btn btn-danger w-100 remove-variante">Eliminar</button>
                </div>
            </div>`;
        container.appendChild(row);
        row.querySelector('.remove-variante').addEventListener('click', () => row.remove());
        varianteIndex++;
    });

    document.querySelectorAll('.remove-variante').forEach(btn => btn.addEventListener('click', function () {
        this.closest('.variante-row').remove();
    }));
</script>
@endsection