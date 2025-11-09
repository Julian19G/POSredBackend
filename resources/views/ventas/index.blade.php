@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">📦 Listado de Ventas</h1>

    {{-- Mensaje de éxito --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Botón Nueva Venta --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ route('ventas.create') }}" class="btn btn-primary">
            ➕ Nueva Venta
        </a>
        <span class="text-muted">Total de ventas: {{ $ventas->count() }}</span>
    </div>

    {{-- Tabla de ventas --}}
    <div class="table-responsive">
        <table class="table table-hover table-bordered align-middle">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Cliente</th>
                    <th>Subtotal</th>
                    <th>Descuento</th>
                    <th>Costo Envío</th>
                    <th>Total</th>
                    <th>Estado</th>
                    <th>Fecha</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ventas as $venta)
                    <tr>
                        <td>#{{ $venta->id }}</td>
                        <td>{{ $venta->cliente->nombre ?? 'Sin cliente' }}</td>
                        <td>${{ number_format($venta->subtotal, 2, ',', '.') }}</td>
                        <td>
                            @if($venta->descuento_manual > 0)
                                <span class="text-danger">
                                    -${{ number_format($venta->descuento_manual, 2, ',', '.') }}
                                </span>
                                @if($venta->motivo_descuento)
                                    <small>({{ $venta->motivo_descuento }})</small>
                                @endif
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>${{ number_format($venta->costo_envio, 2, ',', '.') }}</td>
                        <td><strong>${{ number_format($venta->total, 2, ',', '.') }}</strong></td>
                        <td>
                            @switch($venta->estado)
                                @case('pagada')
                                    <span class="badge bg-success">Pagada</span>
                                    @break
                                @case('pendiente')
                                    <span class="badge bg-warning text-dark">Pendiente</span>
                                    @break
                                @case('cancelada')
                                    <span class="badge bg-danger">Cancelada</span>
                                    @break
                            @endswitch
                        </td>
                        <td>{{ $venta->created_at->format('d/m/Y H:i') }}</td>
                        <td class="text-center">
                            <a href="{{ route('ventas.show', $venta->id) }}" class="btn btn-sm btn-info">👁 Ver</a>
                            <a href="{{ route('ventas.edit', $venta->id) }}" class="btn btn-sm btn-warning">✏️ Editar</a>
                            <form action="{{ route('ventas.destroy', $venta->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button 
                                    type="submit" 
                                    class="btn btn-sm btn-danger"
                                    onclick="return confirm('¿Seguro que deseas eliminar esta venta?')">
                                    🗑 Eliminar
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted py-3">No hay ventas registradas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
