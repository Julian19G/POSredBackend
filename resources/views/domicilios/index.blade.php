@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between mb-4">
        <h1>🚚 Domicilios</h1>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Venta #</th>
                <th>Cliente</th>
                <th>Dirección</th>
                <th>Estado</th>
                <th>Costo envío</th>
                <th>Fecha envío / entrega</th>
                <th class="text-center">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($domicilios as $domicilio)
                @php
                    $cliente = $domicilio->venta->cliente ?? null;
                    $estado  = $domicilio->estado ?? 'pendiente';
                    $badgeColor = match($estado) {
                        'pendiente'  => 'secondary',
                        'enviado'    => 'primary',
                        'entregado'  => 'success',
                        'cancelado'  => 'danger',
                        default      => 'info',
                    };
                @endphp
                <tr>
                    <td>{{ $domicilio->id }}</td>
                    <td>
                        <a href="{{ route('ventas.show', $domicilio->venta_id) }}">
                            #{{ $domicilio->venta_id }}
                        </a>
                    </td>
                    <td>{{ $cliente->nombre ?? '—' }}</td>
                    <td>{{ $domicilio->direccion }}</td>
                    <td>
                        <span class="badge bg-{{ $badgeColor }}">
                            {{ ucfirst($estado) }}
                        </span>
                    </td>
                    <td>${{ number_format($domicilio->costo_envio ?? 0, 0, ',', '.') }}</td>
                    <td>
                        {{ optional($domicilio->fecha_envio)->format('d/m/Y') ?? '—' }}
                        /
                        {{ optional($domicilio->fecha_entrega)->format('d/m/Y') ?? '—' }}
                    </td>
                    <td class="text-center">
                        <a href="{{ route('domicilios.show', $domicilio) }}"
                           class="btn btn-sm btn-info">Ver</a>
                        <a href="{{ route('domicilios.edit', $domicilio) }}"
                           class="btn btn-sm btn-warning">Editar</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center text-muted">
                        No hay domicilios registrados
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $domicilios->links() }}
</div>
@endsection