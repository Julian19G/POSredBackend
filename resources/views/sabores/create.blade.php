@extends('layouts.app')

@section('title', 'Crear Sabor')

@section('content')
<div class="container">
    <h1 class="mb-4">Crear nuevo sabor</h1>

    <form action="{{ route('sabores.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Nombre --}}
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre del sabor</label>
            <input type="text"
                   name="nombre"
                   id="nombre"
                   class="form-control @error('nombre') is-invalid @enderror"
                   value="{{ old('nombre') }}"
                   required>
            @error('nombre')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Descripción --}}
        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea name="descripcion"
                      id="descripcion"
                      rows="3"
                      class="form-control @error('descripcion') is-invalid @enderror">{{ old('descripcion') }}</textarea>
            @error('descripcion')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Intensidad --}}
        <div class="mb-3">
            <label for="intensidad" class="form-label">Intensidad</label>
            <input type="number"
                   name="intensidad"
                   id="intensidad"
                   min="1"
                   max="10"
                   class="form-control @error('intensidad') is-invalid @enderror"
                   value="{{ old('intensidad', 1) }}">
            @error('intensidad')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Activo --}}
        <div class="mb-3 form-check">
            <input class="form-check-input"
                   type="checkbox"
                   id="activo"
                   name="activo"
                   checked>
            <label class="form-check-label" for="activo">Activo</label>
        </div>

        {{-- Imagen --}}
        <div class="mb-3">
            <label for="imagen" class="form-label">Imagen (opcional)</label>
            <input type="file" 
                   class="form-control @error('imagen') is-invalid @enderror"
                   id="imagen"
                   name="imagen"
                   accept="image/*">
            @error('imagen')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Botón --}}
        <button type="submit" class="btn btn-primary">Guardar</button>
        <a href="{{ route('sabores.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
