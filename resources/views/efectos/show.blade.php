@extends('layouts.app')

@section('title', 'Detalle del Efecto')

@section('content')
<div class="container">

    <h1 class="mb-4">Detalle del Efecto</h1>

    <div class="card shadow">
        <div class="card-body">

            <h3>{{ $efecto->nombre }}</h3>

            <p><strong>Descripción:</strong><br>
                {{ $efecto->descripcion ?? 'Sin descripción' }}
            </p>

            <p>
                <strong>Tipo:</strong>
                <span class="badge 
                    @if($efecto->tipo=='positivo') bg-success 
                    @elseif($efecto->tipo=='negativo') bg-danger 
                    @else bg-secondary @endif
                ">
                    {{ ucfirst($efecto->tipo) }}
                </span>
            </p>

            <p>
                <strong>Estado:</strong>
                @if($efecto->activo)
                    <span class="badge bg-success">Activo</span>
                @else
                    <span class="badge bg-danger">Inactivo</span>
                @endif
            </p>

            @if($efecto->imagen)
                <p><strong>Imagen:</strong></p>
                <img src="{{ asset('storage/'.$efecto->imagen) }}" width="250" class="rounded">
            @endif

            <div class="mt-3">
                <a href="{{ route('efectos.index') }}" class="btn btn-secondary">Volver</a>
                <a href="{{ route('efectos.edit', $efecto->id) }}" class="btn btn-warning">Editar</a>
            </div>

        </div>
    </div>

</div>
@endsection
