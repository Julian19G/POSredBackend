@extends('layouts.app')

@section('title', 'Detalle del Color')

@section('content')
<div class="container">

    <h1 class="mb-4">Detalle del color</h1>

    <div class="card shadow">
        <div class="card-body">

            <h3>{{ $color->nombre }}</h3>

            <p>
                <strong>Código HEX:</strong>
                {{ $color->codigo_hex ?? 'No definido' }}
            </p>

            @if($color->codigo_hex)
            <p><strong>Vista del color:</strong></p>
            <div 
                style="
                    width:120px;
                    height:120px;
                    background: {{ $color->codigo_hex }};
                    border-radius:15px;
                    border: 1px solid #999;">
            </div>
            @endif

            <p class="mt-3">
                <strong>Descripción:</strong><br>
                {{ $color->descripcion ?? 'Sin descripción' }}
            </p>

            <p>
                <strong>Estado:</strong>
                @if($color->activo)
                    <span class="badge bg-success">Activo</span>
                @else
                    <span class="badge bg-danger">Inactivo</span>
                @endif
            </p>

            <div class="mt-3">
                <a href="{{ route('colores.index') }}" class="btn btn-secondary">Volver</a>
                <a href="{{ route('colores.edit', $color->id) }}" class="btn btn-warning">Editar</a>
            </div>

        </div>
    </div>

</div>
@endsection
