@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="fw-bold mb-4">Crear Nueva Categoría</h1>

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body">
            <form action="{{ route('categorias.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- Nombre --}}
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" name="nombre" id="nombre" 
                           class="form-control @error('nombre') is-invalid @enderror"
                           placeholder="Ingresa el nombre de la categoría"
                           value="{{ old('nombre') }}" required>
                    @error('nombre')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Descripción --}}
                <div class="mb-3">
                    <label for="descripcion" class="form-label">Descripción</label>
                    <textarea name="descripcion" id="descripcion" rows="3" 
                              class="form-control @error('descripcion') is-invalid @enderror"
                              placeholder="Describe brevemente la categoría">{{ old('descripcion') }}</textarea>
                    @error('descripcion')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Imagen --}}
                <div class="mb-3">
                    <label for="imagen" class="form-label">Imagen</label>
                    <input type="file" name="imagen" id="imagen" 
                           class="form-control @error('imagen') is-invalid @enderror" 
                           accept="image/*">
                    @error('imagen')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Estado --}}
                <div class="mb-3">
                    <label for="activa" class="form-label">Estado</label>
                    <select name="activa" id="activa" class="form-select">
                        <option value="1" {{ old('activa', 1) == 1 ? 'selected' : '' }}>Activa</option>
                        <option value="0" {{ old('activa', 1) == 0 ? 'selected' : '' }}>Inactiva</option>
                    </select>
                </div>

                {{-- Botones --}}
                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('categorias.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Cancelar
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-save"></i> Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
