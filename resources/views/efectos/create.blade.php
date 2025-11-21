@extends('layouts.app')

@section('title', 'Crear Efecto')

@section('content')
<div class="container">

    <h1 class="mb-4">Crear nuevo efecto</h1>

    <form action="{{ route('efectos.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Nombre --}}
        <div class="mb-3">
            <label class="form-label">Nombre</label>
            <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror"
                value="{{ old('nombre') }}" required>

            @error('nombre')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Descripción --}}
        <div class="mb-3">
            <label class="form-label">Descripción</label>
            <textarea name="descripcion" rows="3"
                class="form-control @error('descripcion') is-invalid @enderror">{{ old('descripcion') }}</textarea>

            @error('descripcion')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Tipo --}}
        <div class="mb-3">
            <label class="form-label">Tipo</label>
            <select name="tipo" class="form-select @error('tipo') is-invalid @enderror">
                <option value="positivo" {{ old('tipo')=='positivo' ? 'selected' : '' }}>Positivo</option>
                <option value="negativo" {{ old('tipo')=='negativo' ? 'selected' : '' }}>Negativo</option>
                <option value="neutral" {{ old('tipo')=='neutral' ? 'selected' : '' }}>Neutral</option>
            </select>

            @error('tipo')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Activo --}}
        <div class="form-check mb-3">
            <input type="checkbox" name="activo" id="activo" class="form-check-input" checked>
            <label for="activo" class="form-check-label">Activo</label>
        </div>

        {{-- Imagen --}}
        <div class="mb-3">
            <label class="form-label">Imagen (opcional)</label>
            <input type="file" name="imagen" class="form-control @error('imagen') is-invalid @enderror" accept="image/*">

            @error('imagen')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Botones --}}
        <button class="btn btn-primary">Guardar</button>
        <a href="{{ route('efectos.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
