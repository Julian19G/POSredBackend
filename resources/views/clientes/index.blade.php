@extends('layouts.app')

@section('content')
<div class="container">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mt-3">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-3 mt-3">
        <h1 class="mb-0">Clientes</h1>
        <a href="{{ route('clientes.create') }}" class="btn btn-primary">➕ Nuevo Cliente</a>
    </div>

    {{-- Búsqueda --}}
    <form method="GET" action="{{ route('clientes.index') }}" class="mb-4">
        <div class="input-group">
            <input type="text" name="buscar" class="form-control"
                   placeholder="Buscar por nombre, teléfono o email…"
                   value="{{ request('buscar') }}">
            <button type="submit" class="btn btn-primary">Buscar</button>
            @if(request('buscar'))
                <a href="{{ route('clientes.index') }}" class="btn btn-outline-secondary">✕ Limpiar</a>
            @endif
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Nombre</th>
                    <th>Teléfono</th>
                    <th>Email</th>
                    <th>Referido por</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($clientes as $cliente)
                <tr>
                    <td class="fw-semibold">{{ $cliente->nombre }}</td>
                    <td>{{ $cliente->telefono }}</td>
                    <td>{{ $cliente->email }}</td>
                    <td>{{ $cliente->referidoPor->nombre ?? '—' }}</td>
                    <td class="text-center">
                        <a href="{{ route('clientes.show', $cliente) }}" class="btn btn-sm btn-info">Ver</a>
                        <a href="{{ route('clientes.edit', $cliente) }}" class="btn btn-sm btn-warning">Editar</a>
                        <form action="{{ route('clientes.destroy', $cliente) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button onclick="return confirm('¿Eliminar cliente?')"
                                    class="btn btn-sm btn-danger">Eliminar</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-4">
                        @if(request('buscar'))
                            No se encontraron clientes con "{{ request('buscar') }}"
                        @else
                            No hay clientes registrados.
                        @endif
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($clientes->hasPages())
        <div class="d-flex justify-content-between align-items-center mt-3">
            {{ $clientes->links() }}
            <small class="text-muted">{{ $clientes->total() }} cliente(s)</small>
        </div>
    @endif
</div>
@endsection
