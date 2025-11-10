@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">🧾 Detalle de Venta #{{ $venta->id }}</h1>

    {{-- Información general --}}
    <div class="card mb-4 shadow-sm border-0 rounded-4">
        <div class="card-body">
            <h5 class="mb-3">📋 Información general</h5>
            <div class="row">
                <div class="col-md-6 mb-2">
                    <strong>Cliente:</strong><br>
                    {{ $venta->cliente->nombre ?? 'Sin cliente asignado' }}
                </div>
                <div class="col-md-6 mb-2">
                    <strong>Teléfono:</strong><br>
                    {{ $venta->cliente->telefono ?? '—' }}
                </div>
                <div class="col-md-6 mb-2">
                    <strong>Dirección de envío:</strong><br>
                    {{ $venta->direccion_envio ?? 'No aplica' }}
                </div>
                <div class="col-md-6 mb-2">
                    <strong>Envío:</strong><br>
                    {{ $venta->envio ? 'Sí' : 'No' }}
                </div>
                <div class="col-md-6 mb-2">
                    <strong>Estado:</strong><br>
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
                </div>
                <div class="col-md-6 mb-2">
                    <strong>Fecha de registro:</strong><br>
                    {{ $venta->created_at->format('d/m/Y H:i') }}
                </div>
            </div>
        </div>
    </div>

    {{-- Productos de la venta --}}
    <div class="card mb-4 shadow-sm border-0 rounded-4">
        <div class="card-body">
            <h5 class="mb-3">🛍️ Productos vendidos</h5>
            @if($venta->detalles->isEmpty())
                <p class="text-muted">No hay productos registrados en esta venta.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>Producto</th>
                                <th>Precio Unitario</th>
                                <th>Cantidad</th>
                                <th>Descuento</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($venta->detalles as $detalle)
                                <tr>
                                    <td>{{ $detalle->producto->nombre ?? 'Producto eliminado' }}</td>
                                    <td>${{ number_format($detalle->precio_unitario, 2, ',', '.') }}</td>
                                    <td>{{ $detalle->cantidad }}</td>
                                    <td>
                                        @if($detalle->descuento_manual > 0)
                                            <span class="text-danger">-${{ number_format($detalle->descuento_manual, 2, ',', '.') }}</span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>${{ number_format(($detalle->precio_unitario * $detalle->cantidad) - $detalle->descuento_manual, 2, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    {{-- Totales y descuentos --}}
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body">
            <h5 class="mb-3">💰 Totales</h5>
            <div class="row">
                <div class="col-md-6 mb-2">
                    <strong>Subtotal:</strong><br>
                    ${{ number_format($venta->subtotal, 2, ',', '.') }}
                </div>
                <div class="col-md-6 mb-2">
                    <strong>Descuento total manual:</strong><br>
                    @if($venta->descuento_manual > 0)
                        <span class="text-danger">-${{ number_format($venta->descuento_manual, 2, ',', '.') }}</span>
                        <small class="text-muted">({{ $venta->motivo_descuento ?? 'Sin motivo registrado' }})</small>
                    @else
                        <span class="text-muted">—</span>
                    @endif
                </div>
                <div class="col-md-6 mb-2">
                    <strong>Costo de envío:</strong><br>
                    ${{ number_format($venta->costo_envio, 2, ',', '.') }}
                </div>
                <div class="col-md-6 mb-2">
                    <strong>Total final:</strong><br>
                    <span class="fs-5 fw-bold text-primary">${{ number_format($venta->total, 2, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>

    <a href="{{ route('ventas.index') }}" class="btn btn-secondary mt-4">⬅ Volver al listado</a>
</div>
@endsection
