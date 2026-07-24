@extends('layouts.app')

@section('content')
<div class="container" style="max-width:760px">

    {{-- Cabecera de la ruta --}}
    <div class="d-flex justify-content-between align-items-center mt-3 mb-3">
        <div>
            <h1 class="mb-0">{{ $ruta->tipoLabel() }} #{{ $ruta->id }}</h1>
            <small class="text-muted">
                {{ $ruta->domiciliario->nombre ?? '—' }} &bull;
                {{ $ruta->created_at->format('d/m/Y H:i') }}
            </small>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="badge fs-6 bg-{{ $ruta->estadoColor() }}">{{ ucfirst($ruta->estado) }}</span>
            <a href="{{ route('rutas.index') }}" class="btn btn-outline-secondary btn-sm">← Mis rutas</a>
        </div>
    </div>

    {{-- Progreso general --}}
    @php
        $total      = $ruta->domicilios->count();
        $entregados = $ruta->domicilios->where('estado', 'entregado')->count();
        $pct        = $total > 0 ? intval($entregados / $total * 100) : 0;
    @endphp
    <div class="mb-4">
        <div class="d-flex justify-content-between small mb-1">
            <span>Progreso de entregas</span>
            <strong>{{ $entregados }}/{{ $total }}</strong>
        </div>
        <div class="progress" style="height:10px">
            <div class="progress-bar bg-success" style="width:{{ $pct }}%"></div>
        </div>
    </div>

    {{-- Lista de domicilios --}}
    @foreach($ruta->domicilios as $i => $d)
    <div class="card border-0 shadow-sm rounded-3 mb-3">
        <div class="card-body">

            {{-- Encabezado domicilio --}}
            <div class="d-flex justify-content-between align-items-start mb-2">
                <div>
                    <span class="badge bg-secondary me-1">{{ $i + 1 }}</span>
                    <strong class="fs-6">{{ $d->venta->cliente->nombre ?? '—' }}</strong>
                    @if($d->tipo === 'express')
                        <span class="badge bg-warning text-dark ms-1">⚡ Express</span>
                    @endif
                </div>
                <span class="badge bg-{{ $d->estadoColor() }}">{{ $d->estadoLabel() }}</span>
            </div>

            {{-- Instrucciones --}}
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <div class="p-3 rounded-2" style="background:#f8f9fa">
                        <div class="text-muted small fw-bold mb-1">📦 RECOGIDA</div>
                        <div class="small">{{ $d->instrucciones_recogida ?? 'Sin instrucciones' }}</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="p-3 rounded-2" style="background:#e8f5e9">
                        <div class="text-muted small fw-bold mb-1">🏠 ENTREGA</div>
                        <div class="small">{{ $d->instrucciones_entrega ?? $d->direccion }}</div>
                        @if($d->venta->cliente->telefono ?? null)
                            <div class="small text-primary mt-1">
                                📞 {{ $d->venta->cliente->telefono }}
                            </div>
                        @endif
                        @if($d->venta->cliente->whatsapp ?? null)
                            <a href="https://wa.me/57{{ preg_replace('/\D/', '', $d->venta->cliente->whatsapp) }}"
                               target="_blank"
                               class="btn btn-sm btn-outline-success mt-1" style="font-size:.75rem">
                                💬 WhatsApp
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Cobro --}}
            @php $debeCobrarse = $d->venta && $d->venta->estado !== 'pagada'; @endphp
            @if($debeCobrarse)
                <div class="alert alert-warning py-2 small mb-3">
                    <strong>💰 Cobrar al cliente:</strong>
                    ${{ number_format($d->venta->total, 0, ',', '.') }} COP
                </div>
            @else
                <div class="alert alert-success py-2 small mb-3">
                    ✅ <strong>Ya pagado.</strong> Solo entregar, no cobrar.
                </div>
            @endif

            {{-- Acciones según estado --}}
            @if($d->estado === 'aceptado')
                <form action="{{ route('rutas.recoger', $d->id) }}" method="POST">
                    @csrf @method('PATCH')
                    <button type="submit" class="btn btn-primary w-100"
                            data-confirm="¿Confirmás que recogiste el paquete?"
                            data-confirm-icon="question"
                            data-confirm-ok="Sí, lo recogí">
                        📦 Marcar como Recogido
                    </button>
                </form>

            @elseif($d->estado === 'en_camino')
                <button type="button" class="btn btn-success w-100"
                        data-bs-toggle="modal"
                        data-bs-target="#modal-entregar-{{ $d->id }}">
                    ✅ Marcar como Entregado
                </button>

                {{-- Modal de entrega --}}
                <div class="modal fade" id="modal-entregar-{{ $d->id }}" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <form action="{{ route('rutas.entregar', $d->id) }}" method="POST">
                                @csrf @method('PATCH')
                                <div class="modal-header">
                                    <h5 class="modal-title">Confirmar entrega — {{ $d->venta->cliente->nombre ?? '' }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">

                                    @if($debeCobrarse)
                                        <div class="alert alert-warning">
                                            💰 Debes cobrar <strong>${{ number_format($d->venta->total, 0, ',', '.') }}</strong> al cliente.
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Método de cobro <span class="text-danger">*</span></label>
                                            <select name="metodo_cobro" class="form-select" required>
                                                <option value="">Seleccionar…</option>
                                                <option value="efectivo">💵 Efectivo</option>
                                                <option value="transferencia">🏦 Transferencia</option>
                                                <option value="cripto">₿ Cripto</option>
                                                <option value="tarjeta">💳 Tarjeta</option>
                                                <option value="otro">Otro</option>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Monto cobrado (dejar vacío si es el total)</label>
                                            <div class="input-group">
                                                <span class="input-group-text">$</span>
                                                <input type="number" name="monto_cobrado" class="form-control"
                                                       placeholder="{{ number_format($d->venta->total, 0) }}"
                                                       min="0" step="100">
                                            </div>
                                        </div>
                                    @else
                                        <div class="alert alert-success">
                                            ✅ Este pedido ya fue pagado. Solo confirma la entrega.
                                        </div>
                                    @endif

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                    <button type="submit" class="btn btn-success">
                                        ✅ Confirmar entrega
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            @elseif($d->estado === 'entregado')
                <div class="text-center text-success fw-semibold py-2">
                    ✅ Entregado
                    @if($d->fecha_entrega_real)
                        <small class="text-muted d-block">{{ $d->fecha_entrega_real->format('d/m/Y H:i') }}</small>
                    @endif
                </div>

            @elseif($d->estado === 'cancelado')
                <div class="text-center text-muted py-2">❌ Cancelado</div>
            @endif

        </div>
    </div>
    @endforeach

    @if($ruta->estado === 'completada')
        <div class="alert alert-success text-center mt-3">
            🎉 <strong>¡Ruta completada!</strong>
            @if($ruta->fecha_completada)
                Finalizada el {{ $ruta->fecha_completada->format('d/m/Y \a \l\a\s H:i') }}.
            @endif
        </div>
    @endif

</div>
@endsection
