@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="fw-bold mb-4">Detalle de la Categoría</h1>

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body d-flex flex-column flex-md-row align-items-start gap-4">
            
            {{-- Imagen --}}
            <div class="text-center">
                @if($categoria->imagen)
                    <img src="{{ asset('storage/' . $categoria->imagen) }}" 
                         alt="{{ $categoria->nombre }}" 
                         class="img-fluid rounded shadow-sm" 
                         style="max-width: 250px; height: auto;">
                @else
                    <div class="bg-light d-flex align-items-center justify-content-center rounded" 
                         style="width: 250px; height: 200px;">
                        <span class="text-muted">Sin imagen</span>
                    </div>
                @endif
            </div>

            {{-- Información --}}
            <div class="flex-grow-1">
                <h4 class="fw-bold mb-3">{{ $categoria->nombre }}</h4>

                <p class="mb-2">
                    <strong>Descripción:</strong><br>
                    {{ $categoria->descripcion ?? 'Sin descripción' }}
                </p>

                <p class="mb-2">
                    <strong>Estado:</strong> 
                    @if($categoria->activa)
                        <span class="badge bg-success">Activa</span>
                    @else
                        <span class="badge bg-secondary">Inactiva</span>
                    @endif
                </p>

                <p class="text-muted">
                    <small><i class="bi bi-clock-history"></i> Última actualización: {{ $categoria->updated_at->format('d/m/Y H:i') }}</small>
                </p>

                <div class="mt-4 d-flex gap-2">
                    <a href="{{ route('categorias.edit', $categoria) }}" class="btn btn-warning">
                        <i class="bi bi-pencil-square"></i> Editar
                    </a>
                    <a href="{{ route('categorias.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left-circle"></i> Volver
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
