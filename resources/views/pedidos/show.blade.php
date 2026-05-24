@extends('layouts.app')
@section('content')
<div class="container py-4">

    <div class="d-flex align-items-center justify-content-between mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('ventas.index') }}">Ventas</a></li>
                <li class="breadcrumb-item"><a href="{{ route('pedidos.index') }}">Pedidos</a></li>
                <li class="breadcrumb-item active">Pedido #{{ $pedido->id }}</li>
            </ol>
        </nav>
        <a href="{{ route('pedidos.index') }}" class="btn btn-outline-secondary btn-sm">← Volver</a>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

<div class="row g-4">

    {{-- ── Columna izquierda: info de la venta ── --}}
    <div class="col-lg-7">

        {{-- Info venta --}}
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-1">📦 Pedido #{{ $pedido->id }}</h5>
                <p class="mb-2 text-muted small">Venta #{{ $pedido->venta->id }} — {{ $pedido->venta->created_at->format('d/m/Y H:i') }}</p>

                <div class="mb-3">
                    <strong>👤 Cliente</strong>
                    <p class="mb-0">{{ $pedido->venta->cliente->nombre }}</p>
                    <p class="mb-0 text-muted small">
                        📞 {{ $pedido->venta->cliente->telefono }}
                        @if($pedido->venta->cliente->whatsapp)
                            · 💬 {{ $pedido->venta->cliente->whatsapp }}
                        @endif
                    </p>
                </div>

                <strong>🛍 Productos</strong>
                <table class="table table-sm mt-2 mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Producto</th>
                            <th>Variante</th>
                            <th class="text-center">Cant.</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($pedido->venta->detalles as $d)
                        <tr>
                            <td>{{ $d->nombre_producto }}</td>
                            <td class="text-muted small">{{ $d->nombre_variante ?? '—' }}</td>
                            <td class="text-center">{{ $d->cantidad }}</td>
                            <td class="text-end">${{ number_format($d->subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                <div class="d-flex flex-column align-items-end mt-3 border-top pt-2">
                    <span class="text-muted small">Subtotal: ${{ number_format($pedido->venta->subtotal, 0, ',', '.') }}</span>
                    @if($pedido->venta->descuento_manual > 0)
                    <span class="text-danger small">Descuento: −${{ number_format($pedido->venta->descuento_manual, 0, ',', '.') }}</span>
                    @endif
                    @if($pedido->venta->costo_envio > 0)
                    <span class="text-muted small">Envío: ${{ number_format($pedido->venta->costo_envio, 0, ',', '.') }}</span>
                    @endif
                    <strong class="fs-5 mt-1">Total: ${{ number_format($pedido->venta->total, 0, ',', '.') }}</strong>
                </div>
            </div>
        </div>

        {{-- Domicilio --}}
        @if($pedido->venta->domicilio)
        @php $dom = $pedido->venta->domicilio; @endphp
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <h6 class="fw-bold">🚚 Domicilio</h6>
                    <a href="{{ route('domicilios.show', $dom->id) }}" class="btn btn-outline-secondary btn-sm">Ver en mapa</a>
                </div>
                <p class="mb-1 fw-semibold">{{ $dom->direccion }}</p>
                @if($dom->referencia_ubicacion)
                <p class="mb-1 text-muted small">📍 {{ $dom->referencia_ubicacion }}</p>
                @endif
                @if($dom->zona)
                <p class="mb-1 text-muted small">🗺 {{ $dom->zona->nombre }}</p>
                @endif
                <p class="mb-2 text-muted small">{{ $dom->ciudad }}, {{ $dom->departamento }}</p>
                @if($dom->comentarios)
                <p class="text-muted small fst-italic mb-2">{{ $dom->comentarios }}</p>
                @endif
                <span class="badge bg-{{ $dom->estado === 'entregado' ? 'success' : ($dom->estado === 'cancelado' ? 'danger' : 'warning') }}">
                    {{ ucfirst($dom->estado) }}
                </span>
            </div>
        </div>
        @endif

        {{-- ══ COMPROBANTES DE PAGO ══ --}}
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3">📎 Comprobantes de pago</h6>

                @forelse($pedido->comprobantes as $comp)
                <div class="border rounded-3 p-3 mb-3 {{ $comp->estado === 'verificado' ? 'border-success bg-success-subtle' : ($comp->estado === 'rechazado' ? 'border-danger bg-danger-subtle' : 'border-warning bg-warning-subtle') }}">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <span class="badge bg-{{ $comp->badge_estado }} mb-1">{{ ucfirst($comp->estado) }}</span>
                            <p class="mb-0 fw-semibold">{{ \App\Models\Comprobante::tiposLabel()[$comp->tipo] }}
                                — ${{ number_format($comp->monto, 0, ',', '.') }}</p>
                            @if($comp->referencia)
                            <p class="mb-0 text-muted small">Ref: {{ $comp->referencia }}</p>
                            @endif
                            @if($comp->notas)
                            <p class="mb-0 text-muted small fst-italic">{{ $comp->notas }}</p>
                            @endif
                            @if($comp->verificado_en)
                            <p class="mb-0 text-muted small">Verificado: {{ $comp->verificado_en->format('d/m/Y H:i') }}</p>
                            @endif
                        </div>
                        <div class="d-flex flex-column gap-1 ms-3">
                            @if($comp->imagen_path)
                            <a href="{{ asset('storage/' . $comp->imagen_path) }}" target="_blank"
                               class="btn btn-outline-secondary btn-sm">🖼 Ver imagen</a>
                            @endif
                            @if($comp->estado === 'pendiente')
                            <form action="{{ route('pedidos.comprobante.verificar', [$pedido->id, $comp->id]) }}" method="POST">
                                @csrf @method('PATCH')
                                <button class="btn btn-success btn-sm w-100"
                                        onclick="return confirm('¿Verificar este comprobante y marcar la venta como pagada?')">
                                    ✅ Verificar
                                </button>
                            </form>
                            <form action="{{ route('pedidos.comprobante.rechazar', [$pedido->id, $comp->id]) }}" method="POST">
                                @csrf @method('PATCH')
                                <button class="btn btn-outline-danger btn-sm w-100"
                                        onclick="return confirm('¿Rechazar este comprobante?')">
                                    ❌ Rechazar
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <p class="text-muted small mb-3">Aún no hay comprobantes registrados.</p>
                @endforelse

                {{-- Formulario para agregar comprobante --}}
                @if($pedido->estado_pago !== 'pagado')
                <details class="mt-2">
                    <summary class="btn btn-outline-primary btn-sm">➕ Registrar comprobante</summary>
                    <form action="{{ route('pedidos.comprobante.subir', $pedido->id) }}" method="POST"
                          enctype="multipart/form-data" class="mt-3">
                        @csrf
                        <div class="row g-2">
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Tipo de pago</label>
                                <select name="tipo" class="form-select form-select-sm" required>
                                    <option value="">Seleccione…</option>
                                    @foreach(\App\Models\Comprobante::tiposLabel() as $val => $label)
                                        <option value="{{ $val }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Monto recibido ($)</label>
                                <input type="number" name="monto" class="form-control form-control-sm"
                                       min="0" step="1000" placeholder="0"
                                       value="{{ $pedido->venta->total }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-semibold">Referencia / N° transacción</label>
                                <input type="text" name="referencia" class="form-control form-control-sm"
                                       placeholder="Nro. transferencia, hash cripto, etc.">
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-semibold">Imagen del comprobante</label>
                                <input type="file" name="imagen" class="form-control form-control-sm"
                                       accept="image/*,.pdf">
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-semibold">Notas</label>
                                <input type="text" name="notas" class="form-control form-control-sm"
                                       placeholder="Observaciones opcionales…">
                            </div>
                            <div class="col-12">
                                <button class="btn btn-primary btn-sm">📎 Guardar comprobante</button>
                            </div>
                        </div>
                    </form>
                </details>
                @endif

            </div>
        </div>

    </div>

    {{-- ── Columna derecha: estado + acciones ── --}}
    <div class="col-lg-5">

        {{-- Estado pedido --}}
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3">📋 Estado del Pedido</h6>

                @php
                    $pasos = ['nuevo','en_preparacion','despachado','entregado'];
                    $estados = \App\Models\Pedido::estadosLabel();
                    $estadoActual = $pedido->estado;
                    $indexActual  = array_search($estadoActual, $pasos);
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
                        @elseif($paso === 'entregado' && $pedido->fecha_entrega)
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
                        <textarea name="notas" class="form-control form-control-sm" rows="2">{{ $pedido->notas }}</textarea>
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

        {{-- Pago --}}
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3">💰 Estado de pago</h6>
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge bg-{{ $pedido->estado_pago === 'pagado' ? 'success' : 'secondary' }} fs-6">
                        {{ $pedido->estado_pago === 'pagado' ? '✅ Pagado' : '⏳ Pendiente' }}
                    </span>
                    @if($pedido->metodo_pago)
                    <span class="badge bg-light text-dark">
                        {{ \App\Models\Pedido::metodosLabel()[$pedido->metodo_pago] }}
                    </span>
                    @endif
                </div>

                @if($pedido->estado_pago !== 'pagado')
                <p class="text-muted small mb-3">
                    Registra un comprobante en el panel izquierdo y verifícalo para marcar la venta como pagada.
                </p>
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
                    <div class="mb-2">
                        <label class="form-label small fw-semibold">Monto (dejar vacío = total)</label>
                        <input type="number" name="monto" class="form-control form-control-sm"
                               min="0" step="1000" placeholder="{{ $pedido->venta->total }}">
                    </div>
                    <div class="mb-2">
                        <label class="form-label small fw-semibold">Referencia (opcional)</label>
                        <input type="text" name="referencia" class="form-control form-control-sm"
                               placeholder="Nro. transacción, recibo, etc.">
                    </div>
                    <button class="btn btn-success btn-sm w-100">✅ Confirmar pago</button>
                </form>
                @endif
            </div>
        </div>

        {{-- Acciones --}}
        <div class="d-grid gap-2">
            <a href="{{ route('ventas.recibo', $pedido->venta->id) }}" target="_blank"
               class="btn btn-outline-dark">🖨 Imprimir recibo</a>
            <a href="{{ route('ventas.show', $pedido->venta->id) }}" class="btn btn-outline-secondary">
                🧾 Ver venta #{{ $pedido->venta->id }}
            </a>
        </div>
    </div>

</div>
</div>
@endsection
