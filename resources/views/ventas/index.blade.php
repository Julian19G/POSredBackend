@extends('layouts.app')

@section('content')
<div class="container">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mt-3">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mt-3">
            {{ $errors->first() }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-3 mt-3">
        <h1 class="mb-0">Ventas</h1>
        <a href="{{ route('ventas.create') }}" class="btn btn-primary">➕ Nueva Venta</a>
    </div>

    {{-- FILTROS --}}
    <form method="GET" action="{{ route('ventas.index') }}" class="card shadow-sm border-0 rounded-3 mb-4">
        <div class="card-body py-3">
            <div class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small mb-1">Buscar cliente</label>
                    <input type="text" name="buscar" class="form-control form-control-sm"
                           placeholder="Nombre del cliente…" value="{{ request('buscar') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label small mb-1">Estado</label>
                    <select name="estado" class="form-select form-select-sm">
                        <option value="">Todos</option>
                        <option value="pendiente"  {{ request('estado') === 'pendiente'  ? 'selected' : '' }}>Pendiente</option>
                        <option value="pagada"     {{ request('estado') === 'pagada'     ? 'selected' : '' }}>Pagada</option>
                        <option value="cancelada"  {{ request('estado') === 'cancelada'  ? 'selected' : '' }}>Cancelada</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small mb-1">Desde</label>
                    <input type="date" name="fecha_desde" class="form-control form-control-sm"
                           value="{{ request('fecha_desde') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label small mb-1">Hasta</label>
                    <input type="date" name="fecha_hasta" class="form-control form-control-sm"
                           value="{{ request('fecha_hasta') }}">
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-sm btn-primary flex-fill">Filtrar</button>
                    <a href="{{ route('ventas.index') }}" class="btn btn-sm btn-outline-secondary flex-fill">Limpiar</a>
                </div>
            </div>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-hover table-bordered align-middle">
            <thead class="table-dark text-center">
                <tr>
                    <th>ID</th>
                    <th>Cliente</th>
                    <th>Vendedor</th>
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
                    <td class="text-start">{{ $venta->cliente->nombre ?? '—' }}</td>
                    <td class="text-start">{{ $venta->vendedor->nombre ?? '—' }}</td>
                    <td>${{ number_format($venta->subtotal, 0, ',', '.') }}</td>
                    <td>
                        @if($venta->descuento_manual > 0)
                            <span class="text-danger">-${{ number_format($venta->descuento_manual, 0, ',', '.') }}</span>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td><strong>${{ number_format($venta->total, 0, ',', '.') }}</strong></td>
                    <td>
                        @if($venta->costo_envio > 0)
                            <span class="badge bg-primary">Envío</span><br>
                            <small>${{ number_format($venta->costo_envio, 0, ',', '.') }}</small>
                        @else
                            <span class="badge bg-light text-dark">No</span>
                        @endif
                    </td>
                    <td>
                        @switch($venta->estado)
                            @case('pagada')    <span class="badge bg-success">Pagada</span>    @break
                            @case('pendiente') <span class="badge bg-warning text-dark">Pendiente</span> @break
                            @case('cancelada') <span class="badge bg-danger">Cancelada</span>  @break
                        @endswitch
                    </td>
                    <td><small>{{ $venta->created_at?->format('d/m/Y H:i') }}</small></td>
                    <td>
                        <a href="{{ route('ventas.show', $venta->id) }}" class="btn btn-sm btn-info">Ver</a>
                        <a href="{{ route('ventas.recibo', $venta->id) }}" class="btn btn-sm btn-outline-secondary" target="_blank">🖨</a>
                        <a href="{{ route('ventas.edit', $venta->id) }}" class="btn btn-sm btn-warning">Editar</a>
                        <form action="{{ route('ventas.destroy', $venta->id) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger"
                                onclick="return confirm('¿Eliminar esta venta?')">🗑</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center text-muted py-4">No hay ventas registradas.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    @if($ventas->hasPages())
        <div class="d-flex justify-content-between align-items-center mt-3">
            {{ $ventas->links() }}
            <small class="text-muted">
                Mostrando {{ $ventas->firstItem() }}–{{ $ventas->lastItem() }} de {{ $ventas->total() }}
            </small>
        </div>
    @endif
</div>
@endsection
