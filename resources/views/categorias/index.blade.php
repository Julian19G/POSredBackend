@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold mb-0">Listado de Categorías</h1>
        <a href="{{ route('categorias.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Nueva Categoría
        </a>
    </div>

    {{-- Mensaje de éxito --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    @endif

    {{-- Tabla de categorías --}}
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 70px;">ID</th>
                        <th style="width: 100px;">Imagen</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Estado</th>
                        <th class="text-center" style="width: 240px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categorias as $categoria)
                        <tr>
                            <td>{{ $categoria->id }}</td>

                            {{-- Imagen --}}
                            <td>
                                @if($categoria->imagen)
                                    <img src="{{ asset('storage/' . $categoria->imagen) }}" 
                                         alt="{{ $categoria->nombre }}" 
                                         class="rounded" 
                                         style="width: 60px; height: 60px; object-fit: cover;">
                                @else
                                    <span class="text-muted fst-italic">Sin imagen</span>
                                @endif
                            </td>

                            <td class="fw-semibold">{{ $categoria->nombre }}</td>
                            <td>{{ $categoria->descripcion ?: 'Sin descripción' }}</td>
                            <td>
                                @if($categoria->activa)
                                    <span class="badge bg-success">Activa</span>
                                @else
                                    <span class="badge bg-secondary">Inactiva</span>
                                @endif
                            </td>

                            {{-- Acciones --}}
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('categorias.show', $categoria) }}" 
                                       class="btn btn-info btn-sm">
                                        <i class="bi bi-eye"></i> Ver
                                    </a>
                                    <a href="{{ route('categorias.edit', $categoria) }}" 
                                       class="btn btn-warning btn-sm text-white">
                                        <i class="bi bi-pencil"></i> Editar
                                    </a>
                                    <form action="{{ route('categorias.destroy', $categoria) }}" 
                                          method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-danger btn-sm"
                                                onclick="return confirm('¿Estás seguro de eliminar esta categoría?')">
                                            <i class="bi bi-trash"></i> Eliminar
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                <i class="bi bi-folder-x"></i> No hay categorías registradas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
