@extends('layouts.app')

@section('content')
<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-3 mt-3">
        <h1 class="mb-0">Domiciliarios</h1>
        <a href="{{ route('domiciliarios.create') }}" class="btn btn-primary">➕ Nuevo Domiciliario</a>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-bordered align-middle">
            <thead class="table-dark text-center">
                <tr>
                    <th>ID</th>
                    <th class="text-start">Nombre</th>
                    <th>Teléfono</th>
                    <th>Vehículo</th>
                    <th>Email</th>
                    <th>Domicilios</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            @forelse($domiciliarios as $d)
                <tr class="text-center">
                    <td>#{{ $d->id }}</td>
                    <td class="text-start fw-semibold">{{ $d->nombre }}</td>
                    <td>{{ $d->telefono ?? '—' }}</td>
                    <td>{{ $d->vehiculoLabel() }}</td>
                    <td class="text-muted small">{{ $d->user->email }}</td>
                    <td>{{ $d->domicilios_count }}</td>
                    <td>
                        <span class="badge {{ $d->activo ? 'bg-success' : 'bg-secondary' }}">
                            {{ $d->activo ? 'Activo' : 'Inactivo' }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('domiciliarios.show', $d) }}" class="btn btn-sm btn-info">Ver</a>
                        <a href="{{ route('domiciliarios.edit', $d) }}" class="btn btn-sm btn-warning">Editar</a>
                        <form action="{{ route('domiciliarios.destroy', $d) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger"
                                data-confirm="Se desactivará este domiciliario. ¿Continuar?">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center text-muted py-4">No hay domiciliarios registrados.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    {{ $domiciliarios->links() }}
</div>
@endsection
