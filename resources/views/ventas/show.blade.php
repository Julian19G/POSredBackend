@extends('layouts.app')

@section('content')
<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-4 mt-3">
        <h1 class="mb-0">Venta #{{ $venta->id }}</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('ventas.recibo', $venta->id) }}" target="_blank" class="btn btn-outline-secondary">🖨 Imprimir</a>
            <a href="{{ route('ventas.edit', $venta->id) }}" class="btn btn-warning">✏️ Editar</a>
            <a href="{{ route('ventas.index') }}" class="btn btn-secondary">← Volver</a>
        </div>
    </div>

    <div class="row g-4">

        {{-- ── Info general ───────────────────────────────────── --}}
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body">
                    <h5 class="mb-3">Información general</h5>
                    <table class="table table-sm table-borderless mb-0">
                        <tr>
                            <th>Cliente</th>
                            <td>
                                <a href="{{ route('clientes.show', $venta->cliente_id) }}" class="text-decoration-none">
                                    {{ $venta->cliente->nombre ?? '—' }}
                                </a>
                            </td>
                        </tr>
                        <tr><th>Teléfono</th><td>{{ $venta->cliente->telefono ?? '—' }}</td></tr>
                        @if($venta->cliente?->whatsapp)
                        <tr>
                            <th>WhatsApp</th>
                            <td>
                                <a href="https://wa.me/57{{ preg_replace('/\D/','',$venta->cliente->whatsapp) }}" target="_blank">
                                    💬 {{ $venta->cliente->whatsapp }}
                                </a>
                            </td>
                        </tr>
                        @endif
                        <tr>
                            <th>Vendedor</th>
                            <td>
                                @if($venta->vendedor)
                                    <a href="{{ route('vendedores.show', $venta->vendedor->id) }}" class="text-decoration-none">
                                        {{ $venta->vendedor->nombre }}
                                    </a>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Estado</th>
                            <td>
                                @switch($venta->estado)
                                    @case('pagada')    <span class="badge bg-success">Pagada</span>    @break
                                    @case('pendiente') <span class="badge bg-warning text-dark">Pendiente</span> @break
                                    @case('cancelada') <span class="badge bg-danger">Cancelada</span>  @break
                                @endswitch
                            </td>
                        </tr>
                        <tr>
                            <th>Método de pago</th>
                            <td>{{ $venta->pedido?->metodo_pago ? Str::ucfirst($venta->pedido->metodo_pago) : '—' }}</td>
                        </tr>
                        <tr><th>Envío</th><td>{{ $venta->envio ? 'Sí' : 'No' }}</td></tr>
                        @if($venta->direccion_envio)
                        <tr><th>Dirección envío</th><td>{{ $venta->direccion_envio }}</td></tr>
                        @endif
                        <tr><th>Fecha</th><td>{{ $venta->created_at->format('d/m/Y H:i') }}</td></tr>
                    </table>
                </div>
            </div>
        </div>

        {{-- ── Totales ─────────────────────────────────────────── --}}
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body">
                    <h5 class="mb-3">Totales</h5>
                    <table class="table table-sm table-borderless mb-0">
                        <tr><th>Subtotal</th><td class="text-end">${{ number_format($venta->subtotal, 0, ',', '.') }}</td></tr>
                        @if($venta->descuento_manual > 0)
                        <tr>
                            <th>Descuento @if($venta->motivo_descuento)<small class="text-muted">({{ $venta->motivo_descuento }})</small>@endif</th>
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

            {{-- ── Pagos confirmados ───────────────────────────── --}}
            @php
                $pagosConfirmados = $venta->pagos->where('estado','confirmado');
                $totalPagado      = $pagosConfirmados->sum('monto');
                $saldoPendiente   = max($venta->total - $totalPagado, 0);
            @endphp
            <div class="card border-0 shadow-sm rounded-4 mt-3">
                <div class="card-header bg-white border-0 pt-3 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold">💰 Pagos recibidos</h6>
                    @if($venta->pedido)
                        <a href="{{ route('pedidos.show', $venta->pedido->id) }}" class="btn btn-sm btn-outline-secondary py-0 px-2 small">
                            Ver pedido
                        </a>
                    @endif
                </div>
                <div class="card-body pt-2">
                    @forelse($pagosConfirmados as $pago)
                    <div class="d-flex justify-content-between align-items-start border-bottom py-2 small">
                        <div>
                            <span class="fw-semibold">{{ \App\Models\Pago::metodosLabel()[$pago->metodo] ?? ucfirst($pago->metodo) }}</span>
                            @if($pago->referencia) — <span class="text-muted">{{ $pago->referencia }}</span> @endif
                            <br>
                            <span class="text-muted" style="font-size:.78rem">
                                {{ $pago->fecha_pago?->format('d/m/Y H:i') ?? $pago->created_at->format('d/m/Y H:i') }}
                                @if($pago->registradoPor) · {{ $pago->registradoPor->name }} @endif
                            </span>
                        </div>
                        <div class="fw-semibold text-success">${{ number_format($pago->monto, 0, ',', '.') }}</div>
                    </div>
                    @empty
                        <p class="text-muted small mb-1">Sin pagos confirmados aún.</p>
                    @endforelse

                    <div class="d-flex justify-content-between pt-2 small fw-semibold border-top mt-1">
                        <span>Total pagado</span>
                        <span class="text-success">${{ number_format($totalPagado, 0, ',', '.') }}</span>
                    </div>
                    @if($saldoPendiente > 0)
                    <div class="d-flex justify-content-between small fw-bold text-danger">
                        <span>Saldo pendiente</span>
                        <span>${{ number_format($saldoPendiente, 0, ',', '.') }}</span>
                    </div>
                    @else
                    <div class="d-flex justify-content-between small fw-bold text-success">
                        <span>✅ Saldo</span><span>Pagado en su totalidad</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- ── Productos vendidos ──────────────────────────────── --}}
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
