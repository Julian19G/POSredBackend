@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Detalle del Producto</h1>

    <div class="card shadow-sm border-0 rounded-4">
        <div class="row g-0">
            {{-- Imagen del producto --}}
            <div class="col-md-4 text-center p-3">
                @if($producto->imagen)
                    <img src="{{ asset('storage/' . $producto->imagen) }}" 
                         alt="{{ $producto->nombre }}" 
                         class="img-fluid rounded-4 shadow-sm" 
                         style="max-height: 280px; object-fit: cover;">
                @else
                    <img src="https://via.placeholder.com/300x200?text=Sin+Imagen" 
                         class="img-fluid rounded-4 opacity-75" 
                         alt="Sin imagen">
                @endif
            </div>

            {{-- Detalles del producto --}}
            <div class="col-md-8">
                <div class="card-body">
                    <h3 class="card-title fw-bold mb-3">{{ $producto->nombre }}</h3>
                    <p class="card-text mb-2">
                        <strong>Descripción:</strong><br>
                        {{ $producto->descripcion ?: 'Sin descripción disponible.' }}
                    </p>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="card-text mb-1">
                                <strong>Precio:</strong><br>
                                ${{ number_format($producto->precio, 0, ',', '.') }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="card-text mb-1">
                                <strong>Stock:</strong><br>
                                {{ $producto->stock }}
                            </p>
                        </div>
                    </div>

                    <p class="card-text mb-2">
                        <strong>Categoría:</strong><br>
                        {{ $producto->categoria ? $producto->categoria->nombre : 'Sin categoría' }}
                    </p>

                    <p class="card-text mb-3">
                        <strong>Estado:</strong><br>
                        @if($producto->activo)
                            <span class="badge bg-success px-3 py-2">Activo</span>
                        @else
                            <span class="badge bg-danger px-3 py-2">Inactivo</span>
                        @endif
                    </p>

                    {{-- Botones de acción --}}
                    <div class="d-flex gap-2 mt-4">
                        <a href="{{ route('productos.edit', $producto) }}" class="btn btn-warning">
                            <i class="bi bi-pencil-square"></i> Editar
                        </a>
                        <a href="{{ route('productos.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left-circle"></i> Volver
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
