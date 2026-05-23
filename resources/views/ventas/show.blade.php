@extends('layouts.app')

@section('content')
<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-4 mt-3">
        <h1 class="mb-0">Venta #{{ $venta->id }}</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('ventas.recibo', $venta->id) }}" target="_blank" class="btn btn-outline-secondary">🖨 Imprimir recibo</a>
            <a href="{{ route('ventas.edit', $venta->id) }}" class="btn btn-warning">✏️ Editar</a>
            <a href="{{ route('ventas.index') }}" class="btn btn-secondary">← Volver</a>
        </div>
    </div>

    <div class="row g-4">

        {{-- Info general --}}
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body">
                    <h5 class="mb-3">Información general</h5>
                    <table class="table table-sm table-borderless mb-0">
                        <tr><th>Cliente</th><td>{{ $venta->cliente->nombre ?? '—' }}</td></tr>
                        <tr><th>Teléfono</th><td>{{ $venta->cliente->telefono ?? '—' }}</td></tr>
                        <tr><th>Vendedor</th>
                            <td>
                                @if($venta->vendedor)
                                    <a href="{{ route('vendedores.show', $venta->vendedor->id) }}">{{ $venta->vendedor->nombre }}</a>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                        </tr>
                        <tr><th>Estado</th>
                            <td>
                                @switch($venta->estado)
                                    @case('pagada')    <span class="badge bg-success">Pagada</span>    @break
                                    @case('pendiente') <span class="badge bg-warning text-dark">Pendiente</span> @break
                                    @case('cancelada') <span class="badge bg-danger">Cancelada</span>  @break
                                @endswitch
                            </td>
                        </tr>
                        <tr><th>Método de pago</th>
                            <td>{{ $venta->pedido?->metodo_pago ? Str::ucfirst($venta->pedido->metodo_pago) : '—' }}</td>
                        </tr>
                        <tr><th>Envío</th><td>{{ $venta->envio ? 'Sí' : 'No' }}</td></tr>
                        @if($venta->direccion_envio)
                        <tr><th>Dirección</th><td>{{ $venta->direccion_envio }}</td></tr>
                        @endif
                        <tr><th>Fecha</th><td>{{ $venta->created_at->format('d/m/Y H:i') }}</td></tr>
                    </table>
                </div>
            </div>
        </div>

        {{-- Totales --}}
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body">
                    <h5 class="mb-3">Totales</h5>
                    <table class="table table-sm table-borderless mb-0">
                        <tr><th>Subtotal</th><td class="text-end">${{ number_format($venta->subtotal, 0, ',', '.') }}</td></tr>
                        @if($venta->descuento_manual > 0)
                        <tr>
                            <th>Descuento
                                @if($venta->motivo_descuento)
                                    <small class="text-muted">({{ $venta->motivo_descuento }})</small>
                                @endif
                            </th>
                            <td class="text-end text-danger">-${{ number_format($venta->descuento_manual, 0, ',', '.') }}</td>
                        </tr>
                        @endif
                        @if($venta->costo_envio > 0)
                        <tr><th>Costo envío</th><td class="text-end">${{ number_format($venta->costo_envio, 0, ',', '.') }}</td></tr>
                        @endif
                        <tr class="border-top">
                            <th class="fs-5">Total</th>
                            <td class="text-end fs-5 fw-bold text-primary">${{ number_format($venta->total, 0, ',', '.') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        {{-- Productos vendidos --}}
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body">
                    <h5 class="mb-3">Productos vendidos</h5>
                    @if($venta->detalles->isEmpty())
                        <p class="text-muted">Sin productos.</p>
                    @else
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th>Producto</th>
                                    <th class="text-center">Precio unit.</th>
                                    <th class="text-center">Cant.</th>
                                    <th class="text-center">Descuento</th>
                                    <th class="text-center">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($venta->detalles as $d)
                                <tr>
                                    <td>
                                        {{ $d->nombre_producto ?? 'Producto eliminado' }}
                                        @if($d->nombre_variante)
                                            <br><small class="text-muted">{{ $d->nombre_variante }}</small>
                                        @endif
                                    </td>
                                    <td class="text-center">${{ number_format($d->precio_unitario, 0, ',', '.') }}</td>
                                    <td class="text-center">{{ $d->cantidad }}</td>
                                    <td class="text-center">
                                        @if($d->descuento_aplicado > 0)
                                            <span class="text-danger">-${{ number_format($d->descuento_aplicado, 0, ',', '.') }}</span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td class="text-center">${{ number_format($d->subtotal, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
