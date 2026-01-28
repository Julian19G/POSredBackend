@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">📦 Listado de Ventas</h1>

    {{-- Mensaje de éxito --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Mensaje de error --}}
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            {{ $errors->first() }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ route('ventas.create') }}" class="btn btn-primary">➕ Nueva Venta</a>
        <span class="text-muted">
            Total de ventas: {{ $ventas->total() }}
        </span>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-bordered align-middle">
            <thead class="table-dark text-center">
                <tr>
                    <th>ID</th>
                    <th>Cliente</th>
                    <th>Subtotal</th>
                    <th>Descuento</th>
                    <th>Total</th>
                    <th>Envío</th>
                    <th>Estado</th>
                    <th>Fecha</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>
            @forelse($ventas as $venta)
                <tr class="text-center">
                    <td>#{{ $venta->id }}</td>
                    <td>{{ $venta->cliente->nombre ?? '—' }}</td>

                    <td>${{ number_format($venta->subtotal, 2, ',', '.') }}</td>

                    <td>
                        @if($venta->descuento_manual > 0)
                            <span class="text-danger">
                                -${{ number_format($venta->descuento_manual, 2, ',', '.') }}
                            </span>
                            @if($venta->motivo_descuento)
                                <br><small>({{ $venta->motivo_descuento }})</small>
                            @endif
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>

                    <td>
                        <strong>${{ number_format($venta->total, 2, ',', '.') }}</strong>
                    </td>

                    {{-- ENVÍO --}}
                    <td>
                        @if($venta->costo_envio > 0)
                            <span class="badge bg-primary">Envío</span><br>
                            ${{ number_format($venta->costo_envio, 2, ',', '.') }}
                            @if($venta->direccion_envio)
                                <br><small>{{ $venta->direccion_envio }}</small>
                            @endif
                        @else
                            <span class="badge bg-secondary">No aplica</span>
                        @endif
                    </td>

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
                            @default
                                <span class="badge bg-secondary">Desconocido</span>
                        @endswitch
                    </td>

                    <td>{{ $venta->created_at?->format('d/m/Y H:i') }}</td>

                    <td>
                        <a href="{{ route('ventas.show', $venta->id) }}" class="btn btn-sm btn-info">👁 Ver</a>
                        <a href="{{ route('ventas.edit', $venta->id) }}" class="btn btn-sm btn-warning">✏️ Editar</a>

                        <form action="{{ route('ventas.destroy', $venta->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger"
                                onclick="return confirm('¿Eliminar esta venta?')">
                                🗑
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center text-muted py-4">
                        No hay ventas registradas.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    {{-- PAGINACIÓN --}}
    @if($ventas->hasPages())
        <div class="mt-4">
            <div class="d-flex justify-content-center">
                {{ $ventas->links() }}
            </div>

            <small class="text-muted d-block text-center mt-2">
                Mostrando {{ $ventas->firstItem() }} a {{ $ventas->lastItem() }}
                de {{ $ventas->total() }} ventas
            </small>
        </div>
    @endif
</div>
@endsection
