@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Detalle de Venta #{{ $venta->id }}</h1>

    {{-- Información general de la venta --}}
    <div class="card mb-4 shadow-sm border-0 rounded-4">
        <div class="card-body">
            <h5>Cliente:</h5>
            <p>{{ $venta->cliente->nombre ?? 'Sin cliente' }}</p>

            <h5>Dirección de envío:</h5>
            <p>{{ $venta->direccion_envio ?? 'No definida' }}</p>

            <h5>Estado:</h5>
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

            <h5>Fecha:</h5>
            <p>{{ $venta->created_at->format('d/m/Y H:i') }}</p>
        </div>
    </div>

    {{-- Productos de la venta --}}
    <div class="card mb-4 shadow-sm border-0 rounded-4">
        <div class="card-body">
            <h4 class="mb-3">Productos</h4>
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Producto</th>
                            <th>Precio Unitario</th>
                            <th>Cantidad</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($venta->detalles as $detalle)
                            <tr>
                                <td>{{ $detalle->producto->nombre ?? 'Producto eliminado' }}</td>
                                <td>${{ number_format($detalle->precio_unitario, 2, ',', '.') }}</td>
                                <td>{{ $detalle->cantidad }}</td>
                                <td>${{ number_format($detalle->subtotal, 2, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Totales y descuentos --}}
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body">
            <h4>Totales</h4>
            <p>Subtotal: ${{ number_format($venta->subtotal, 2, ',', '.') }}</p>
            <p>Descuento manual: 
                @if($venta->descuento_manual > 0)
                    <span class="text-danger">- ${{ number_format($venta->descuento_manual, 2, ',', '.') }}</span>
                    ({{ $venta->motivo_descuento ?? '' }})
                @else
                    <span class="text-muted">—</span>
                @endif
            </p>
            <p>Costo de envío: ${{ number_format($venta->costo_envio, 2, ',', '.') }}</p>
            <h5>Total final: <strong>${{ number_format($venta->total, 2, ',', '.') }}</strong></h5>
        </div>
    </div>

    <a href="{{ route('ventas.index') }}" class="btn btn-secondary mt-3">⬅ Volver al listado</a>
</div>
@endsection
