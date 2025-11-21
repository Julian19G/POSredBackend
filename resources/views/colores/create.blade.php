@extends('layouts.app')

@section('title', 'Crear Color')

@section('content')
<div class="container">
    <h1 class="mb-4">Crear nuevo color</h1>

    <form action="{{ route('colores.store') }}" method="POST">
        @csrf

        {{-- Nombre --}}
        <div class="mb-3">
            <label class="form-label">Nombre</label>
            <input 
                type="text" 
                name="nombre" 
                class="form-control @error('nombre') is-invalid @enderror"
                value="{{ old('nombre') }}"
                required>
            @error('nombre')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Código HEX --}}
        <div class="mb-3">
            <label class="form-label">Código HEX</label>
            <input 
                type="text" 
                name="codigo_hex" 
                placeholder="#FFFFFF"
                class="form-control @error('codigo_hex') is-invalid @enderror"
                value="{{ old('codigo_hex') }}">
            @error('codigo_hex')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Descripción --}}
        <div class="mb-3">
            <label class="form-label">Descripción</label>
            <textarea 
                name="descripcion" 
                rows="3"
                class="form-control @error('descripcion') is-invalid @enderror">{{ old('descripcion') }}</textarea>

            @error('descripcion')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Activo --}}
        <div class="form-check mb-3">
            <input type="checkbox" name="activo" id="activo" class="form-check-input" checked>
            <label for="activo" class="form-check-label">Activo</label>
        </div>

        <button class="btn btn-primary">Guardar</button>
        <a href="{{ route('colores.index') }}" class="btn btn-secondary">Cancelar</a>

    </form>
</div>
@endsection
