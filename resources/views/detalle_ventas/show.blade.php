@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Detalle de Venta #{{ $detalleVenta->id }}</h1>

    <div class="mb-3">
        <strong>Venta ID:</strong> {{ $detalleVenta->venta_id }}
    </div>

    <div class="mb-3">
        <strong>Producto:</strong> {{ $detalleVenta->producto->nombre ?? 'Producto eliminado' }}
    </div>

    <div class="mb-3">
        <strong>Cantidad:</strong> {{ $detalleVenta->cantidad }}
    </div>

    <div class="mb-3">
        <strong>Precio Unitario:</strong> ${{ number_format($detalleVenta->precio_unitario, 2) }}
    </div>

    <div class="mb-3">
        <strong>Subtotal:</strong> ${{ number_format($detalleVenta->subtotal, 2) }}
    </div>

    <a href="{{ route('detalle_ventas.index') }}" class="btn btn-secondary">Volver</a>
</div>
@endsection
