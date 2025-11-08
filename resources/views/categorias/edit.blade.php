@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="fw-bold mb-4">Editar Categoría</h1>

    {{-- Mensajes de error --}}
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Formulario --}}
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body">
            <form action="{{ route('categorias.update', $categoria) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- Nombre --}}
                <div class="mb-3">
                    <label for="nombre" class="form-label fw-semibold">Nombre</label>
                    <input type="text" 
                           name="nombre" 
                           id="nombre" 
                           class="form-control" 
                           value="{{ old('nombre', $categoria->nombre) }}" 
                           required>
                </div>

                {{-- Descripción --}}
                <div class="mb-3">
                    <label for="descripcion" class="form-label fw-semibold">Descripción</label>
                    <textarea name="descripcion" 
                              id="descripcion" 
                              class="form-control" 
                              rows="3">{{ old('descripcion', $categoria->descripcion) }}</textarea>
                </div>

                {{-- Imagen --}}
                <div class="mb-3">
                    <label for="imagen" class="form-label fw-semibold">Imagen</label>
                    @if($categoria->imagen)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $categoria->imagen) }}" 
                                 alt="{{ $categoria->nombre }}" 
                                 class="img-thumbnail rounded" 
                                 style="max-width: 150px; height: auto;">
                        </div>
                    @endif
                    <input type="file" name="imagen" id="imagen" class="form-control">
                    <small class="text-muted">Sube una nueva imagen si deseas reemplazar la actual.</small>
                </div>

                {{-- Estado (Select) --}}
                <div class="mb-4">
                    <label for="activa" class="form-label fw-semibold">Estado</label>
                    <select class="form-select" name="activa" id="activa">
                        <option value="1" {{ old('activa', $categoria->activa) == 1 ? 'selected' : '' }}>Activa</option>
                        <option value="0" {{ old('activa', $categoria->activa) == 0 ? 'selected' : '' }}>Inactiva</option>
                    </select>
                </div>

                {{-- Botones --}}
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle"></i> Actualizar
                    </button>
                    <a href="{{ route('categorias.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left-circle"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
