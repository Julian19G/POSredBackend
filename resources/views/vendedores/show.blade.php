@extends('layouts.app')
@section('content')
<div class="container py-3">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="mb-0">👤 {{ $vendedor->nombre }}</h1>
            <small class="text-muted">{{ $vendedor->activo ? 'Activo' : 'Inactivo' }} · {{ $vendedor->comision_porcentaje }}% de comisión</small>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('vendedores.edit', $vendedor) }}" class="btn btn-warning btn-sm">✏️ Editar</a>
            <a href="{{ route('vendedores.index') }}" class="btn btn-outline-secondary btn-sm">← Volver</a>
        </div>
    </div>

    @if(!$vendedor->user_id)
        <div class="alert alert-warning">
            Este vendedor no tiene una cuenta de usuario vinculada. Vincúlala desde <strong>Usuarios</strong> para que pueda iniciar sesión, tomar domicilios y ver sus ganancias.
        </div>
    @elseif($vendedor->domiciliario)
        <div class="alert alert-success">Este vendedor también está habilitado como domiciliario ({{ $vendedor->domiciliario->vehiculoLabel() }}).</div>
    @endif

    <div class="card border-0 shadow-sm rounded-4 mb-4 bg-dark text-white">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-start gap-3 flex-wrap">
                <div>
                    <div class="small text-white-50 text-uppercase fw-semibold">Ganancia total acumulada</div>
                    <div class="display-6 fw-bold text-warning">${{ number_format($stats['total_ganado'], 0, ',', '.') }}</div>
                    <div class="small text-white-50">Comisiones de ventas + domicilios entregados</div>
                </div>
                <div class="text-md-end small">
                    <div>Ventas: <strong>${{ number_format($stats['total_comisionado'], 0, ',', '.') }}</strong></div>
                    <div>Domicilios: <strong>${{ number_format($stats['ganancia_domicilios'], 0, ',', '.') }}</strong></div>
                    <div class="mt-2 text-warning">Pendiente de pago: <strong>${{ number_format($stats['saldo_pendiente'], 0, ',', '.') }}</strong></div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Balance cards ─────────────────────────────────────── --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-4 text-center py-3">
                <div class="fs-4 fw-bold text-primary">${{ number_format($stats['total_comisionado'], 0, ',', '.') }}</div>
                <div class="small text-muted">Total comisionado</div>
            </div>
        </div>
        @if($vendedor->domiciliario)
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-4 text-center py-3">
                <div class="fs-4 fw-bold text-success">${{ number_format($stats['ganancia_domicilios'], 0, ',', '.') }}</div>
                <div class="small text-muted">Ganancias domicilios</div>
                <div class="small text-muted">{{ $stats['domicilios_entregados'] }} entregados</div>
            </div>
        </div>
        @endif
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-4 text-center py-3">
                <div class="fs-4 fw-bold text-success">${{ number_format($stats['total_pagado'], 0, ',', '.') }}</div>
                <div class="small text-muted">Ya pagado</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-4 text-center py-3">
                <div class="fs-4 fw-bold text-warning">${{ number_format($stats['saldo_pendiente'], 0, ',', '.') }}</div>
                <div class="small text-muted">Saldo pendiente</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-4 text-center py-3">
                <div class="fs-4 fw-bold text-secondary">{{ $stats['total_ventas'] }}</div>
                <div class="small text-muted">Ventas totales</div>
            </div>
        </div>
    </div>

    <div class="row g-4">

        {{-- ── Columna izquierda: datos + comisiones pendientes ── --}}
        <div class="col-lg-5">

            {{-- Info del vendedor --}}
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3">Datos de contacto</h6>
                    <dl class="row mb-0 small">
                        @if($vendedor->telefono)
                        <dt class="col-5 text-muted">Teléfono</dt>
                        <dd class="col-7">📞 {{ $vendedor->telefono }}</dd>
                        @endif
                        @if($vendedor->whatsapp)
                        <dt class="col-5 text-muted">WhatsApp</dt>
                        <dd class="col-7">💬 <a href="https://wa.me/57{{ preg_replace('/\D/', '', $vendedor->whatsapp) }}" target="_blank">{{ $vendedor->whatsapp }}</a></dd>
                        @endif
                        @if($vendedor->email)
                        <dt class="col-5 text-muted">Email</dt>
                        <dd class="col-7">{{ $vendedor->email }}</dd>
                        @endif
                        @if($vendedor->instagram)
                        <dt class="col-5 text-muted">Instagram</dt>
                        <dd class="col-7">@{{ $vendedor->instagram }}</dd>
                        @endif
                        @if($vendedor->notas)
                        <dt class="col-5 text-muted">Notas</dt>
                        <dd class="col-7 fst-italic">{{ $vendedor->notas }}</dd>
                        @endif
                    </dl>
                </div>
            </div>

            {{-- Link de referido --}}
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-2">🔗 Link de referido</h6>
                    <p class="text-muted small mb-2">
                        Comparte este link. Las ventas que se hagan a través de él se le atribuyen a {{ $vendedor->nombre }}.
                    </p>
                    <div class="input-group input-group-sm">
                        <input type="text" id="enlace-referido" class="form-control"
                               value="{{ $vendedor->enlace_referido }}" readonly>
                        <button type="button" class="btn btn-outline-primary" id="btn-copiar-enlace">
                            Copiar
                        </button>
                    </div>
                    <div class="small text-muted mt-2">
                        Código: <code>{{ $vendedor->codigo }}</code>
                    </div>
                </div>
            </div>

            <script>
                document.getElementById('btn-copiar-enlace').addEventListener('click', function () {
                    const input = document.getElementById('enlace-referido');
                    navigator.clipboard.writeText(input.value).then(() => {
                        const btn = this;
                        const original = btn.textContent;
                        btn.textContent = '¡Copiado!';
                        btn.classList.replace('btn-outline-primary', 'btn-success');
                        setTimeout(() => {
                            btn.textContent = original;
                            btn.classList.replace('btn-success', 'btn-outline-primary');
                        }, 1500);
                    }).catch(() => {
                        // Fallback si clipboard no está disponible (http no seguro)
                        input.select();
                        document.execCommand('copy');
                    });
                });
            </script>

            {{-- Comisiones pendientes + formulario de pago --}}
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 pt-3 pb-0">
                    <h6 class="fw-bold mb-0">💰 Comisiones pendientes</h6>
                </div>
                <div class="card-body">
                    @if($comisionesPendientes->isEmpty())
                        <p class="text-muted small mb-0">Sin comisiones pendientes.</p>
                    @else
                        <form action="{{ route('liquidaciones.store', $vendedor) }}" method="POST" id="form-liquidacion">
                            @csrf

                            <div class="table-responsive mb-3">
                                <table class="table table-sm table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width:32px">
                                                <input type="checkbox" id="check-all" class="form-check-input" checked>
                                            </th>
                                            <th>Venta</th>
                                            <th class="text-end">Venta total</th>
                                            <th class="text-end">Comisión</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($comisionesPendientes as $com)
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="comision_ids[]"
                                                   value="{{ $com->id }}"
                                                   class="form-check-input comision-check"
                                                   data-monto="{{ $com->monto_comision }}"
                                                   checked>
                                        </td>
                                        <td>
                                            <a href="{{ route('ventas.show', $com->venta_id) }}" class="text-decoration-none small">
                                                #{{ $com->venta_id }}
                                            </a>
                                            <br><span class="text-muted" style="font-size:.78rem">{{ $com->venta->cliente->nombre ?? '—' }}</span>
                                        </td>
                                        <td class="text-end small">${{ number_format($com->monto_venta, 0, ',', '.') }}</td>
                                        <td class="text-end fw-semibold small text-success">
                                            $<span class="monto-com">{{ number_format($com->monto_comision, 0, ',', '.') }}</span>
                                            <br><span class="text-muted" style="font-size:.75rem">{{ $com->porcentaje }}%</span>
                                        </td>
                                    </tr>
                                    @endforeach
                                    </tbody>
                                    <tfoot class="table-light">
                                        <tr>
                                            <td colspan="3" class="fw-semibold small">Total a pagar:</td>
                                            <td class="text-end fw-bold text-success" id="total-liquidar">
                                                ${{ number_format($comisionesPendientes->sum('monto_comision'), 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                            <div class="row g-2 mb-2">
                                <div class="col-sm-6">
                                    <label class="form-label small mb-1">Método de pago</label>
                                    <select name="metodo_pago" class="form-select form-select-sm" required>
                                        <option value="efectivo">💵 Efectivo</option>
                                        <option value="transferencia">🏦 Transferencia</option>
                                        <option value="cripto">₿ Cripto</option>
                                        <option value="tarjeta">💳 Tarjeta</option>
                                        <option value="otro">📦 Otro</option>
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label small mb-1">Fecha de pago</label>
                                    <input type="date" name="fecha_pago" class="form-control form-control-sm"
                                           value="{{ now()->toDateString() }}" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label small mb-1">Referencia / comprobante</label>
                                    <input type="text" name="referencia" class="form-control form-control-sm"
                                           placeholder="Nro. de transferencia, recibo, etc.">
                                </div>
                                <div class="col-12">
                                    <label class="form-label small mb-1">Notas</label>
                                    <textarea name="notas" class="form-control form-control-sm" rows="2"></textarea>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-success btn-sm w-100"
                                    data-confirm="Se registrará el pago de las comisiones seleccionadas."
                                    data-confirm-icon="success"
                                    data-confirm-ok="💸 Registrar pago">
                                💸 Registrar pago
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        {{-- ── Columna derecha: ventas + historial liquidaciones ── --}}
        <div class="col-lg-7">

            {{-- Historial de liquidaciones --}}
            @if($liquidaciones->isNotEmpty())
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-0 pt-3 pb-0">
                    <h6 class="fw-bold mb-0">📋 Historial de pagos</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-sm mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">Fecha</th>
                                    <th>Método</th>
                                    <th class="text-center">Comisiones</th>
                                    <th class="text-end">Monto</th>
                                    <th class="text-end pe-3">Ver</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($liquidaciones as $liq)
                            <tr>
                                <td class="ps-3 small">{{ $liq->fecha_pago->format('d/m/Y') }}</td>
                                <td class="small">{{ ucfirst($liq->metodo_pago) }}</td>
                                <td class="text-center small">{{ $liq->comisiones_count }}</td>
                                <td class="text-end fw-semibold small text-success">
                                    ${{ number_format($liq->monto_total, 0, ',', '.') }}
                                </td>
                                <td class="text-end pe-3">
                                    <a href="{{ route('liquidaciones.show', [$vendedor, $liq]) }}" class="btn btn-xs btn-outline-secondary btn-sm">Ver</a>
                                </td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            {{-- Ventas realizadas --}}
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 pt-3 pb-0">
                    <h6 class="mb-0 fw-bold">🛒 Ventas realizadas</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-sm mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">#</th>
                                    <th>Cliente</th>
                                    <th class="text-end">Total</th>
                                    <th>Estado</th>
                                    <th>Fecha</th>
                                </tr>
                            </thead>
                            <tbody>
                            @forelse($ventas as $v)
                            <tr>
                                <td class="ps-3 text-muted small">
                                    <a href="{{ route('ventas.show', $v->id) }}" class="text-decoration-none">{{ $v->id }}</a>
                                </td>
                                <td class="small">{{ $v->cliente->nombre ?? '—' }}</td>
                                <td class="text-end small">${{ number_format($v->total, 0, ',', '.') }}</td>
                                <td>
                                    @switch($v->estado)
                                        @case('pagada')    <span class="badge bg-success">Pagada</span>    @break
                                        @case('pendiente') <span class="badge bg-warning text-dark">Pendiente</span> @break
                                        @case('cancelada') <span class="badge bg-danger">Cancelada</span>  @break
                                    @endswitch
                                </td>
                                <td><small>{{ $v->created_at->format('d/m/Y') }}</small></td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center text-muted py-3 small">Sin ventas aún.</td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($ventas->hasPages())
                <div class="card-footer bg-white">{{ $ventas->links() }}</div>
                @endif
            </div>

            @if($vendedor->domiciliario)
            <div class="card border-0 shadow-sm rounded-4 mt-4">
                <div class="card-header bg-white border-0 pt-3 pb-0">
                    <h6 class="mb-0 fw-bold">🛵 Domicilios realizados</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-sm mb-0 align-middle">
                            <thead class="table-light"><tr><th class="ps-3">#</th><th>Cliente</th><th>Estado</th><th class="text-end pe-3">Ganancia</th></tr></thead>
                            <tbody>
                            @forelse($domicilios as $domicilio)
                                <tr><td class="ps-3">#{{ $domicilio->id }}</td><td>{{ $domicilio->venta->cliente->nombre ?? '—' }}</td><td>{{ ucfirst($domicilio->estado) }}</td><td class="text-end pe-3">${{ number_format($domicilio->estado === 'entregado' ? $domicilio->tarifa_monto : 0, 0, ',', '.') }}</td></tr>
                            @empty
                                <tr><td colspan="4" class="text-center text-muted py-3">Sin domicilios asignados.</td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

        </div>
    </div>
</div>

<script>
// Recalcular total al marcar/desmarcar comisiones
const checkAll  = document.getElementById('check-all');
const checks    = document.querySelectorAll('.comision-check');
const totalEl   = document.getElementById('total-liquidar');

function recalcularTotal() {
    let total = 0;
    checks.forEach(cb => {
        if (cb.checked) total += parseFloat(cb.dataset.monto || 0);
    });
    if (totalEl) totalEl.textContent = '$' + total.toLocaleString('es-CO', { maximumFractionDigits: 0 });
}

if (checkAll) {
    checkAll.addEventListener('change', () => {
        checks.forEach(cb => cb.checked = checkAll.checked);
        recalcularTotal();
    });
}
checks.forEach(cb => cb.addEventListener('change', recalcularTotal));
</script>
@endsection
