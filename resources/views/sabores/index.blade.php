@extends('layouts.app')

@section('content')
<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Listado de Sabores</h1>

        <a href="{{ route('sabores.create') }}" class="btn btn-primary shadow-sm">
            + Crear Sabor
        </a>
    </div>

    {{-- Mensaje de éxito --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- Tabla --}}
    <div class="card shadow-sm">
        <div class="card-body p-0">

            <table class="table table-striped table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Imagen</th>
                        <th>Nombre</th>
                        <th>Intensidad</th>
                        <th>Estado</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($sabores as $sabor)
                        <tr>
                            <td>{{ $sabor->id }}</td>

                            <td>
                                @if($sabor->imagen)
                                    <img src="{{ asset('storage/' . $sabor->imagen) }}"
                                         alt="imagen sabor"
                                         class="img-thumbnail"
                                         style="width: 60px; height: 60px; object-fit: cover;">
                                @else
                                    <span class="text-muted fst-italic">Sin imagen</span>
                                @endif
                            </td>

                            <td class="fw-semibold">{{ $sabor->nombre }}</td>

                            <td>
                                <span class="badge bg-purple bg-opacity-25 text-dark">
                                    {{ $sabor->intensidad }}
                                </span>
                            </td>

                            <td>
                                @if($sabor->activo)
                                    <span class="badge bg-success">Activo</span>
                                @else
                                    <span class="badge bg-danger">Inactivo</span>
                                @endif
                            </td>

                            <td class="text-end">

                                <a href="{{ route('sabores.show', $sabor) }}"
                                   class="btn btn-sm btn-info text-white me-1">
                                   Ver
                                </a>

                                <a href="{{ route('sabores.edit', $sabor) }}"
                                   class="btn btn-sm btn-warning me-1">
                                   Editar
                                </a>

                                <form action="{{ route('sabores.destroy', $sabor) }}"
                                      method="POST"
                                      class="d-inline"
                                      onsubmit="return confirm('¿Seguro que deseas eliminar este sabor?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">
                                        Eliminar
                                    </button>
                                </form>

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                No hay sabores registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>

        </div>
    </div>

    {{-- Paginación --}}
    <div class="mt-3">
        {{ $sabores->links() }}
    </div>

</div>
@endsection
