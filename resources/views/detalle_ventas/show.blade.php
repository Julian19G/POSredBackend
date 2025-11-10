@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">🧾 Detalle de Venta #{{ $detalleVenta->id }}</h1>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <h5 class="card-title mb-3 text-primary">Información General</h5>
            <ul class="list-group list-group-flush">
                <li class="list-group-item">
                    <strong>Venta asociada:</strong>
                    Venta #{{ $detalleVenta->venta->id ?? 'No disponible' }}
                </li>
                <li class="list-group-item">
                    <strong>Fecha de creación:</strong>
                    {{ $detalleVenta->created_at->format('d/m/Y H:i') }}
                </li>
                <li class="list-group-item">
                    <strong>Última actualización:</strong>
                    {{ $detalleVenta->updated_at->format('d/m/Y H:i') }}
                </li>
            </ul>
        </div>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <h5 class="card-title mb-3 text-success">📦 Información del Producto</h5>
            <ul class="list-group list-group-flush">
                <li class="list-group-item">
                    <strong>Producto:</strong>
                    {{ $detalleVenta->producto->nombre ?? $detalleVenta->nombre_producto }}
                </li>
                <li class="list-group-item">
                    <strong>Código del producto:</strong>
                    {{ $detalleVenta->codigo_producto ?? 'No especificado' }}
                </li>
                <li class="list-group-item">
                    <strong>Cantidad vendida:</strong>
                    {{ $detalleVenta->cantidad }}
                </li>
                <li class="list-group-item">
                    <strong>Precio unitario:</strong>
                    ${{ number_format($detalleVenta->precio_unitario, 2) }}
                </li>
            </ul>
        </div>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <h5 class="card-title mb-3 text-danger">💰 Detalles Financieros</h5>
            <ul class="list-group list-group-flush">
                <li class="list-group-item">
                    <strong>Descuento aplicado:</strong>
                    ${{ number_format($detalleVenta->descuento_aplicado, 2) }}
                </li>
                <li class="list-group-item">
                    <strong>Impuesto:</strong>
                    ${{ number_format($detalleVenta->impuesto, 2) }}
                </li>
                <li class="list-group-item bg-light fw-semibold">
                    <strong>Subtotal final:</strong>
                    ${{ number_format($detalleVenta->subtotal, 2) }}
                </li>
            </ul>
        </div>
    </div>

    <div class="d-flex justify-content-between">
        <a href="{{ route('detalle_ventas.edit', $detalleVenta) }}" class="btn btn-warning">
            ✏️ Editar Detalle
        </a>
        <a href="{{ route('detalle_ventas.index') }}" class="btn btn-secondary">
            ← Volver al listado
        </a>
    </div>
</div>
@endsection
