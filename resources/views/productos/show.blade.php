@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Detalle del Producto</h1>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">{{ $producto->nombre }}</h5>
            <p class="card-text"><strong>Descripción:</strong> {{ $producto->descripcion }}</p>
            <p class="card-text"><strong>Precio:</strong> ${{ number_format($producto->precio, 0, ',', '.') }}</p>
            <p class="card-text"><strong>Stock:</strong> {{ $producto->stock }}</p>

            <a href="{{ route('productos.edit', $producto) }}" class="btn btn-warning">Editar</a>
            <a href="{{ route('productos.index') }}" class="btn btn-secondary">Volver</a>
        </div>
    </div>
</div>
@endsection
