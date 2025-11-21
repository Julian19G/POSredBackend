@extends('layouts.app')

@section('title', 'Detalle del Sabor')

@section('content')
<div class="container">

    <h1 class="mb-4">Detalle del Sabor</h1>

    <div class="card shadow">
        <div class="card-body">

            <h3 class="card-title">{{ $sabor->nombre }}</h3>

            <p><strong>Descripción:</strong><br>
                {{ $sabor->descripcion ?? 'Sin descripción' }}
            </p>

            <p><strong>Intensidad:</strong> {{ $sabor->intensidad }}</p>

            <p><strong>Estado:</strong>
                @if($sabor->activo)
                    <span class="badge bg-success">Activo</span>
                @else
                    <span class="badge bg-danger">Inactivo</span>
                @endif
            </p>

            @if($sabor->imagen)
                <div class="my-3">
                    <strong>Imagen:</strong><br>
                    <img src="{{ asset('storage/' . $sabor->imagen) }}"
                         alt="imagen sabor"
                         class="img-fluid rounded"
                         width="250">
                </div>
            @endif

            <a href="{{ route('sabores.index') }}" class="btn btn-secondary mt-3">Volver</a>
            <a href="{{ route('sabores.edit', $sabor->id) }}" class="btn btn-warning mt-3">Editar</a>

        </div>
    </div>

</div>
@endsection
