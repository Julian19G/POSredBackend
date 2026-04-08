@extends('layouts.app')
@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>📦 Pedidos</h2>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Cliente</th>
                        <th>Total</th>
                        <th>Estado pedido</th>
                        <th>Pago</th>
                        <th>Domicilio</th>
                        <th>Fecha</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                @forelse($pedidos as $pedido)
                    <tr>
                        <td class="text-muted small">{{ $pedido->id }}</td>
                        <td>
                            <strong>{{ $pedido->venta->cliente->nombre }}</strong>
                            <br><small class="text-muted">{{ $pedido->venta->cliente->telefono }}</small>
                        </td>
                        <td>${{ number_format($pedido->venta->total, 2, ',', '.') }}</td>
                        <td>
                            <span class="badge bg-{{ $pedido->badge_estado }}">
                                {{ $pedido->label_estado }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-{{ $pedido->estado_pago === 'pagado' ? 'success' : 'secondary' }}">
                                {{ ucfirst($pedido->estado_pago) }}
                            </span>
                        </td>
                        <td>
                            @if($pedido->venta->envio)
                                <span class="badge bg-info text-dark">🚚 Sí</span>
                            @else
                                <span class="text-muted small">No</span>
                            @endif
                        </td>
                        <td class="text-muted small">{{ $pedido->created_at->format('d/m/Y') }}</td>
                        <td>
                            <a href="{{ route('pedidos.show', $pedido->id) }}"
                               class="btn btn-sm btn-outline-primary">Ver</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center text-muted py-4">No hay pedidos aún.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $pedidos->links() }}</div>
</div>
@endsection