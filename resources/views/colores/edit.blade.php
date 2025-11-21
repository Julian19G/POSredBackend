@extends('layouts.app')

@section('title', 'Editar Color')

@section('content')
<div class="container">

    <h1 class="mb-4">Editar color</h1>

    <form action="{{ route('colores.update', $color->id) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Nombre --}}
        <div class="mb-3">
            <label class="form-label">Nombre</label>
            <input 
                type="text" 
                name="nombre" 
                class="form-control @error('nombre') is-invalid @enderror"
                value="{{ old('nombre', $color->nombre) }}"
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
                class="form-control @error('codigo_hex') is-invalid @enderror"
                value="{{ old('codigo_hex', $color->codigo_hex) }}"
                placeholder="#FFFFFF">

            @error('codigo_hex')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror

            @if($color->codigo_hex)
                <div 
                    class="mt-2" 
                    style="width:60px; height:60px; background: {{ $color->codigo_hex }}; border-radius:8px; border:1px solid #666;">
                </div>
            @endif
        </div>

        {{-- Descripción --}}
        <div class="mb-3">
            <label class="form-label">Descripción</label>
            <textarea 
                name="descripcion" 
                rows="3"
                class="form-control @error('descripcion') is-invalid @enderror">{{ old('descripcion', $color->descripcion) }}</textarea>

            @error('descripcion')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Activo --}}
        <div class="form-check mb-3">
            <input 
                type="checkbox" 
                name="activo" 
                id="activo" 
                class="form-check-input"
                {{ $color->activo ? 'checked' : '' }}>
            <label for="activo" class="form-check-label">Activo</label>
        </div>

        <button class="btn btn-primary">Actualizar</button>
        <a href="{{ route('colores.index') }}" class="btn btn-secondary">Cancelar</a>

    </form>

</div>
@endsection
