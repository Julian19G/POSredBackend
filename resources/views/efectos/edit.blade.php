@extends('layouts.app')

@section('title', 'Editar Efecto')

@section('content')
<div class="container">

    <h1 class="mb-4">Editar Efecto</h1>

    <form action="{{ route('efectos.update', $efecto->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- Nombre --}}
        <div class="mb-3">
            <label class="form-label">Nombre</label>
            <input type="text"
                   name="nombre"
                   class="form-control @error('nombre') is-invalid @enderror"
                   value="{{ old('nombre', $efecto->nombre) }}"
                   required>

            @error('nombre')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Descripción --}}
        <div class="mb-3">
            <label class="form-label">Descripción</label>
            <textarea name="descripcion" rows="3"
                class="form-control @error('descripcion') is-invalid @enderror">{{ old('descripcion', $efecto->descripcion) }}</textarea>

            @error('descripcion')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Tipo --}}
        <div class="mb-3">
            <label class="form-label">Tipo</label>
            <select name="tipo" class="form-select @error('tipo') is-invalid @enderror">
                <option value="positivo" {{ $efecto->tipo=='positivo' ? 'selected' : '' }}>Positivo</option>
                <option value="negativo" {{ $efecto->tipo=='negativo' ? 'selected' : '' }}>Negativo</option>
                <option value="neutral" {{ $efecto->tipo=='neutral' ? 'selected' : '' }}>Neutral</option>
            </select>

            @error('tipo')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

            {{-- Estado --}}
            <div class="mb-3">
                <label class="form-label">Estado</label>
                <select name="activo" class="form-select @error('activo') is-invalid @enderror">
                    <option value="1" {{ old('activo', 1) == 1 ? 'selected' : '' }}>Activo</option>
                    <option value="0" {{ old('activo') == 0 ? 'selected' : '' }}>Inactivo</option>
                </select>

                @error('activo')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>


        {{-- Imagen --}}
        <div class="mb-3">
            <label class="form-label">Imagen (opcional)</label>
            <input type="file" name="imagen" class="form-control @error('imagen') is-invalid @enderror">

            @if($efecto->imagen)
                <small class="d-block mt-1">Imagen actual:</small>
                <img src="{{ asset('storage/'.$efecto->imagen) }}" width="120" class="mt-1">
            @endif

            @error('imagen')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Botones --}}
        <button class="btn btn-primary">Actualizar</button>
        <a href="{{ route('efectos.index') }}" class="btn btn-secondary">Cancelar</a>

    </form>
</div>
@endsection
