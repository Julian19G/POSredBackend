@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between mb-4">
        <h1>🚚 Domicilios</h1>
        <a href="{{ route('domicilios.create') }}" class="btn btn-primary">
            + Nuevo domicilio
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Cliente</th>
                <th>Dirección</th>
                <th>Estado</th>
                <th>Costo</th>
                <th>Fechas</th>
                <th class="text-center">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($domicilios as $domicilio)
                <tr>
                    <td>{{ $domicilio->id }}</td>
                    <td>{{ $domicilio->cliente->nombre ?? '—' }}</td>
                    <td>{{ $domicilio->direccion }}</td>
                    <td>
                        <span class="badge bg-info">
                            {{ ucfirst($domicilio->estado) }}
                        </span>
                    </td>
                    <td>${{ number_format($domicilio->costo_envio ?? 0, 0) }}</td>
                    <td>
                        {{ optional($domicilio->fecha_envio)->format('Y-m-d') ?? '—' }}
                        /
                        {{ optional($domicilio->fecha_entrega)->format('Y-m-d') ?? '—' }}
                    </td>
                    <td class="text-center">
                        <a href="{{ route('domicilios.show', $domicilio) }}" class="btn btn-sm btn-info">Ver</a>
                        <a href="{{ route('domicilios.edit', $domicilio) }}" class="btn btn-sm btn-warning">Editar</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center text-muted">
                        No hay domicilios registrados
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $domicilios->links() }}
</div>
@endsection
