@extends('layouts.app')
@section('content')
<div class="container py-4">

    {{-- ── Navegación ── --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('ventas.index') }}">Ventas</a></li>
                <li class="breadcrumb-item"><a href="{{ route('pedidos.index') }}">Pedidos</a></li>
                <li class="breadcrumb-item active">Pedido #{{ $pedido->id }}</li>
            </ol>
        </nav>
        <a href="{{ route('pedidos.index') }}" class="btn btn-outline-secondary btn-sm">
            ← Volver a Pedidos
        </a>
    </div>

<div class="row g-4">

    {{-- ── Columna izquierda: info de la venta ── --}}
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3">📦 Pedido #{{ $pedido->id }}</h5>
                <p class="mb-1 text-muted small">Venta #{{ $pedido->venta->id }} —
                    {{ $pedido->venta->created_at->format('d/m/Y H:i') }}</p>

                {{-- Cliente --}}
                <div class="mt-3">
                    <strong>👤 Cliente</strong>
                    <p class="mb-0">{{ $pedido->venta->cliente->nombre }}</p>
                    <p class="mb-0 text-muted small">{{ $pedido->venta->cliente->telefono }}</p>
                </div>

                {{-- Productos --}}
                <div class="mt-3">
                    <strong>🛍 Productos</strong>
                    <table class="table table-sm mt-2">
                        <thead class="table-light">
                            <tr><th>Producto</th><th>Cant.</th><th class="text-end">Subtotal</th></tr>
                        </thead>
                        <tbody>
                        @foreach($pedido->venta->detalles as $d)
                            <tr>
                                <td>{{ $d->nombre_producto }}</td>
                                <td>{{ $d->cantidad }}</td>
                                <td class="text-end">${{ number_format($d->subtotal, 2, ',', '.') }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Totales --}}
                <div class="d-flex flex-column align-items-end mt-2">
                    <span class="text-muted small">Subtotal: ${{ number_format($pedido->venta->subtotal, 2, ',', '.') }}</span>
                    @if($pedido->venta->descuento_manual > 0)
                    <span class="text-danger small">Descuento: −${{ number_format($pedido->venta->descuento_manual, 2, ',', '.') }}</span>
                    @endif
                    @if($pedido->venta->costo_envio > 0)
                    <span class="text-muted small">Envío: ${{ number_format($pedido->venta->costo_envio, 2, ',', '.') }}</span>
                    @endif
                    <strong class="fs-5 mt-1">Total: ${{ number_format($pedido->venta->total, 2, ',', '.') }}</strong>
                </div>
            </div>
        </div>

        {{-- Domicilio --}}
        @if($pedido->venta->domicilio)
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <h6 class="fw-bold">🚚 Domicilio</h6>
                <p class="mb-1">{{ $pedido->venta->domicilio->direccion }}</p>
                <p class="mb-1 text-muted small">
                    {{ $pedido->venta->domicilio->ciudad }},
                    {{ $pedido->venta->domicilio->departamento }},
                    {{ $pedido->venta->domicilio->pais }}
                </p>
                @if($pedido->venta->domicilio->comentarios)
                <p class="text-muted small fst-italic">{{ $pedido->venta->domicilio->comentarios }}</p>
                @endif
                <span class="badge bg-{{ $pedido->venta->domicilio->estado === 'entregado' ? 'success' : ($pedido->venta->domicilio->estado === 'cancelado' ? 'danger' : 'warning') }}">
                    {{ ucfirst($pedido->venta->domicilio->estado) }}
                </span>
            </div>
        </div>
        @endif
    </div>

    {{-- ── Columna derecha: acciones ── --}}
    <div class="col-lg-5">

        {{-- Estado actual --}}
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3">📋 Estado del Pedido</h6>

                {{-- Timeline --}}
                @php
                    $pasos = ['nuevo','en_preparacion','despachado','entregado'];
                    $estados = \App\Models\Pedido::estadosLabel();
                    $estadoActual = $pedido->estado;
                    $indexActual = array_search($estadoActual, $pasos);
                @endphp
                <div class="d-flex flex-column gap-2 mb-3">
                @foreach($pasos as $i => $paso)
                    @php $done = $indexActual !== false && $i <= $indexActual; @endphp
                    <div class="d-flex align-items-center gap-2">
                        <span class="rounded-circle d-inline-flex align-items-center justify-content-center fw-bold"
                            style="width:28px;height:28px;font-size:.75rem;
                                   background:{{ $done ? '#198754' : '#dee2e6' }};
                                   color:{{ $done ? '#fff' : '#666' }}">
                            {{ $i + 1 }}
                        </span>
                        <span class="{{ $done ? 'fw-semibold' : 'text-muted' }}">
                            {{ $estados[$paso]['label'] }}
                        </span>
                        @if($paso === 'despachado' && $pedido->fecha_despacho)
                            <small class="text-muted ms-auto">{{ $pedido->fecha_despacho->format('d/m H:i') }}</small>
                        @endif
                        @if($paso === 'entregado' && $pedido->fecha_entrega)
                            <small class="text-muted ms-auto">{{ $pedido->fecha_entrega->format('d/m H:i') }}</small>
                        @endif
                    </div>
                @endforeach
                @if($estadoActual === 'cancelado')
                    <div class="d-flex align-items-center gap-2">
                        <span class="rounded-circle d-inline-flex align-items-center justify-content-center fw-bold"
                            style="width:28px;height:28px;font-size:.75rem;background:#dc3545;color:#fff">✖</span>
                        <span class="fw-semibold text-danger">Cancelado</span>
                    </div>
                @endif
                </div>

                {{-- Formulario cambio de estado --}}
                @if(!in_array($pedido->estado, ['entregado','cancelado']))
                <form action="{{ route('pedidos.estado', $pedido->id) }}" method="POST">
                    @csrf @method('PATCH')
                    <div class="mb-2">
                        <label class="form-label small fw-semibold">Cambiar estado</label>
                        <select name="estado" class="form-select form-select-sm">
                            @foreach(\App\Models\Pedido::estadosLabel() as $val => $info)
                                <option value="{{ $val }}" {{ $pedido->estado === $val ? 'selected' : '' }}>
                                    {{ $info['label'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-2">
                        <label class="form-label small fw-semibold">Notas internas</label>
                        <textarea name="notas" class="form-control form-control-sm" rows="2"
                                  placeholder="Ej: cliente confirmó recepción…">{{ $pedido->notas }}</textarea>
                    </div>
                    <button class="btn btn-primary btn-sm w-100">Actualizar estado</button>
                </form>
                @else
                    <div class="alert alert-{{ $pedido->estado === 'entregado' ? 'success' : 'danger' }} mb-0 py-2 small">
                        Pedido {{ $pedido->estado === 'entregado' ? 'entregado ✅' : 'cancelado ❌' }}
                    </div>
                @endif
            </div>
        </div>

        {{-- Método de pago --}}
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3">💰 Pago</h6>
                <p class="mb-1">
                    Estado:
                    <span class="badge bg-{{ $pedido->estado_pago === 'pagado' ? 'success' : ($pedido->estado_pago === 'reembolsado' ? 'warning' : 'secondary') }}">
                        {{ ucfirst($pedido->estado_pago) }}
                    </span>
                </p>
                @if($pedido->metodo_pago)
                    <p class="mb-3">Método: <strong>{{ \App\Models\Pedido::metodosLabel()[$pedido->metodo_pago] }}</strong></p>
                @endif

                @if($pedido->estado_pago !== 'pagado')
                <form action="{{ route('pedidos.pago', $pedido->id) }}" method="POST">
                    @csrf @method('PATCH')
                    <div class="mb-2">
                        <label class="form-label small fw-semibold">Método de pago</label>
                        <select name="metodo_pago" class="form-select form-select-sm" required>
                            <option value="">Seleccione…</option>
                            @foreach(\App\Models\Pedido::metodosLabel() as $val => $label)
                                <option value="{{ $val }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button class="btn btn-success btn-sm w-100">✅ Confirmar pago</button>
                </form>
                @endif
            </div>
        </div>

        {{-- Acciones rápidas --}}
        <div class="d-grid gap-2">
            <a href="{{ route('pedidos.index') }}" class="btn btn-outline-secondary">
                📋 Ver todos los pedidos
            </a>
            <a href="{{ route('ventas.create') }}" class="btn btn-outline-primary">
                🛒 Nueva venta
            </a>
            <a href="{{ route('ventas.show', $pedido->venta->id) }}" class="btn btn-outline-dark">
                🧾 Ver venta #{{ $pedido->venta->id }}
            </a>
        </div>

    </div>
</div>
</div>

@if(session('success'))
<div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div class="toast show align-items-center text-bg-success border-0">
        <div class="d-flex">
            <div class="toast-body">{{ session('success') }}</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>
@endif
@endsection