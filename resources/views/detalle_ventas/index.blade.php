@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">📋 Detalles de Ventas</h1>

    {{-- Mensajes de éxito --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    @endif

    {{-- Botón para crear nuevo detalle --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ route('detalle_ventas.create') }}" class="btn btn-primary">
            ➕ Nuevo Detalle de Venta
        </a>
        <form method="GET" action="{{ route('detalle_ventas.index') }}" class="d-flex">
            <input type="text" name="search" class="form-control me-2" placeholder="Buscar por producto o venta..." value="{{ request('search') }}">
            <button class="btn btn-outline-secondary" type="submit">Buscar</button>
        </form>
    </div>

    {{-- Tabla principal --}}
    <div class="table-responsive">
        <table class="table table-hover align-middle text-center">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Venta</th>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio Unitario</th>
                    <th>Descuento</th>
                    <th>Impuesto</th>
                    <th>Subtotal</th>
                    <th>Fecha</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($detalleVentas as $detalle)
                    <tr>
                        <td>{{ $detalle->id }}</td>
                        <td>#{{ $detalle->venta_id }}</td>
                        <td>{{ $detalle->nombre_producto }}</td>
                        <td>{{ $detalle->cantidad }}</td>
                        <td>${{ number_format($detalle->precio_unitario, 2) }}</td>
                        <td>${{ number_format($detalle->descuento_aplicado, 2) }}</td>
                        <td>${{ number_format($detalle->impuesto, 2) }}</td>
                        <td><strong>${{ number_format($detalle->subtotal, 2) }}</strong></td>
                        <td>{{ $detalle->created_at->format('d/m/Y') }}</td>
                        <td>
                           <div class="d-flex gap-1 justify-content-center">
                                <a href="{{ route('detalle_ventas.show', $detalle->id) }}" class="btn btn-sm btn-outline-info">👁 Ver</a>
                                <a href="{{ route('detalle_ventas.edit', $detalle->id) }}" class="btn btn-sm btn-outline-warning">✏️ Editar</a>
                                <form action="{{ route('detalle_ventas.destroy', $detalle->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Seguro que deseas eliminar este registro?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">🗑 Eliminar</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="11" class="text-muted">No hay detalles de ventas registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
{{-- Paginación --}}
<div class="mt-3 text-center">



    <div class="d-flex justify-content-center">
        {{ $detalleVentas->links() }}
    </div>

        <small class="text-muted d-block mb-2">
        Mostrando {{ $detalleVentas->firstItem() }} a {{ $detalleVentas->lastItem() }}
        de {{ $detalleVentas->total() }} resultados
    </small>
    
</div>
@endsection