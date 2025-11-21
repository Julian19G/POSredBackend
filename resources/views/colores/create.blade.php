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

        <button class="btn btn-primary">Guardar</button>
        <a href="{{ route('colores.index') }}" class="btn btn-secondary">Cancelar</a>

    </form>
</div>
@endsection
