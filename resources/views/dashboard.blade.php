@extends('layouts.app')

@section('content')
<style>
.metric-card { cursor:pointer; transition:transform .15s,box-shadow .15s; color:inherit; }
.metric-card:hover { transform:translateY(-3px); box-shadow:0 .5rem 1.5rem rgba(0,0,0,.12)!important; }
.metric-card:active { transform:translateY(0); }
.comision-bar { height:8px; border-radius:4px; background:#e9ecef; overflow:hidden; }
.comision-bar-fill { height:100%; border-radius:4px; background:linear-gradient(90deg,#0d6efd,#198754); transition:width .6s ease; }
</style>

{{-- ══════════════════════════════════════════════════════
     SIN VENDEDOR VINCULADO
════════════════════════════════════════════════════════ --}}
@if(!$esAdmin && ($sinVendedor ?? false))
<div class="container py-5" style="max-width:600px">
    <div class="card border-0 shadow-sm rounded-4 text-center p-5">
        <div class="fs-1 mb-3">⚠️</div>
        <h4 class="fw-bold mb-2">Tu cuenta no está vinculada a un vendedor</h4>
        <p class="text-muted mb-4">
            Pídele al administrador que vincule tu usuario a un perfil de vendedor
            para poder ver tu panel personalizado.
        </p>
        <a href="{{ route('ventas.create') }}" class="btn btn-primary">➕ Registrar venta</a>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════
     DASHBOARD ADMIN
════════════════════════════════════════════════════════ --}}
@elseif($esAdmin)
<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="mb-0">Dashboard</h1>
            <small class="text-muted">Vista general del negocio</small>
        </div>
        <small class="text-muted">{{ now()->format('d/m/Y H:i') }}</small>
    </div>

    {{-- Métricas --}}
    @php
        $hoyStr    = now()->toDateString();
        $semanaStr = now()->startOfWeek()->toDateString();
        $mesStr    = now()->startOfMonth()->toDateString();
    @endphp
    <div class="row g-3 mb-4">

        <div class="col-6 col-md-4 col-lg-2">
            <a href="{{ route('ventas.index', ['fecha_desde' => $hoyStr, 'fecha_hasta' => $hoyStr]) }}"
               class="card border-0 shadow-sm text-center h-100 text-decoration-none metric-card">
                <div class="card-body py-3">
                    <div class="fs-2 fw-bold text-primary">{{ $ventasHoy }}</div>
                    <div class="small text-muted">Ventas hoy</div>
                </div>
            </a>
        </div>

        <div class="col-6 col-md-4 col-lg-2">
            <a href="{{ route('ventas.index', ['fecha_desde' => $semanaStr, 'fecha_hasta' => $hoyStr]) }}"
               class="card border-0 shadow-sm text-center h-100 text-decoration-none metric-card">
                <div class="card-body py-3">
                    <div class="fs-2 fw-bold text-info">{{ $ventasSemana }}</div>
                    <div class="small text-muted">Esta semana</div>
                </div>
            </a>
        </div>

        <div class="col-6 col-md-4 col-lg-2">
            <a href="{{ route('ventas.index', ['fecha_desde' => $mesStr, 'fecha_hasta' => $hoyStr]) }}"
               class="card border-0 shadow-sm text-center h-100 text-decoration-none metric-card">
                <div class="card-body py-3">
                    <div class="fs-2 fw-bold text-secondary">{{ $ventasMes }}</div>
                    <div class="small text-muted">Este mes</div>
                </div>
            </a>
        </div>

        <div class="col-6 col-md-4 col-lg-2">
            <a href="{{ route('ventas.index', ['fecha_desde' => $mesStr, 'fecha_hasta' => $hoyStr, 'estado' => 'pagada']) }}"
               class="card border-0 shadow-sm text-center h-100 text-decoration-none metric-card">
                <div class="card-body py-3">
                    <div class="fs-6 fw-bold text-success">${{ number_format($ingresosMes, 0, ',', '.') }}</div>
                    <div class="small text-muted">Ingresos mes</div>
                </div>
            </a>
        </div>

        <div class="col-6 col-md-4 col-lg-2">
            <a href="{{ route('ventas.index', ['estado' => 'pendiente']) }}"
               class="card border-0 shadow-sm text-center h-100 text-decoration-none metric-card">
                <div class="card-body py-3">
                    <div class="fs-6 fw-bold text-warning">${{ number_format($pendienteCobro, 0, ',', '.') }}</div>
                    <div class="small text-muted">Por cobrar</div>
                </div>
            </a>
        </div>

        <div class="col-6 col-md-4 col-lg-2">
            <a href="{{ route('pedidos.index') }}"
               class="card border-0 shadow-sm text-center h-100 text-decoration-none metric-card">
                <div class="card-body py-3">
                    <div class="fs-2 fw-bold text-danger">{{ $pedidosPendientes }}</div>
                    <div class="small text-muted">Pedidos activos</div>
                </div>
            </a>
        </div>

    </div>

    {{-- Comisiones pendientes de pago --}}
    @if($comisionesTotales > 0)
    <div class="alert border-0 rounded-3 mb-4 py-2 px-3 d-flex align-items-center gap-2"
         style="background:#fff8e1;">
        <span class="fs-5">💸</span>
        <span class="small">
            Hay <strong>${{ number_format($comisionesTotales, 0, ',', '.') }}</strong>
            en comisiones pendientes de pago a vendedores.
        </span>
        <a href="{{ route('vendedores.index') }}" class="btn btn-sm btn-warning ms-auto">Ver vendedores</a>
    </div>
    @endif

    <div class="row g-4">

        {{-- Ventas recientes --}}
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center pt-3">
                    <h6 class="mb-0 fw-bold">Ventas recientes</h6>
                    <a href="{{ route('ventas.index') }}" class="btn btn-sm btn-outline-primary">Ver todas</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">#</th>
                                    <th>Cliente</th>
                                    <th>Vendedor</th>
                                    <th>Total</th>
                                    <th>Estado</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($ventasRecientes as $v)
                                <tr>
                                    <td class="ps-3 text-muted small">{{ $v->id }}</td>
                                    <td>{{ $v->cliente->nombre ?? '—' }}</td>
                                    <td class="small text-muted">{{ $v->vendedor->nombre ?? '—' }}</td>
                                    <td><strong>${{ number_format($v->total, 0, ',', '.') }}</strong></td>
                                    <td>
                                        @switch($v->estado)
                                            @case('pagada')    <span class="badge bg-success">Pagada</span>    @break
                                            @case('pendiente') <span class="badge bg-warning text-dark">Pendiente</span> @break
                                            @case('cancelada') <span class="badge bg-danger">Cancelada</span>  @break
                                        @endswitch
                                    </td>
                                    <td>
                                        <a href="{{ route('ventas.show', $v->id) }}"
                                           class="btn btn-sm btn-outline-secondary py-0 px-2">Ver</a>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="6" class="text-center text-muted py-3">Sin ventas aún</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Columna derecha --}}
        <div class="col-lg-5 d-flex flex-column gap-4">

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pt-3">
                    <h6 class="mb-0 fw-bold">Top productos vendidos</h6>
                </div>
                <div class="card-body pt-1">
                    @forelse($topProductos as $tp)
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div>
                            <div class="small fw-semibold">{{ $tp->nombre_producto }}</div>
                            <div class="text-muted" style="font-size:.75rem">${{ number_format($tp->total_ingresos, 0, ',', '.') }}</div>
                        </div>
                        <span class="badge bg-primary rounded-pill">{{ $tp->total_vendido }} uds</span>
                    </div>
                    @empty
                    <p class="text-muted small mb-0">Sin datos</p>
                    @endforelse
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pt-3 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold text-danger">⚠ Stock bajo (≤ 5)</h6>
                    <a href="{{ route('productos.index', ['stock_bajo' => 1]) }}"
                       class="btn btn-sm btn-outline-danger">Ver todos</a>
                </div>
                <div class="card-body pt-1">
                    @forelse($stockBajo as $v)
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="small">
                            <span class="fw-semibold">{{ $v->producto->nombre ?? '—' }}</span>
                            <span class="text-muted"> · {{ $v->nombre }}</span>
                        </div>
                        <span class="badge {{ $v->stock === 0 ? 'bg-danger' : 'bg-warning text-dark' }}">
                            {{ $v->stock }} uds
                        </span>
                    </div>
                    @empty
                    <p class="text-success small mb-0">✓ Todo el stock está OK</p>
                    @endforelse
                </div>
            </div>

        </div>
    </div>

    {{-- Accesos rápidos --}}
    <div class="row g-3 mt-2">
        <div class="col-12"><h6 class="text-muted mb-1">Accesos rápidos</h6></div>
        <div class="col-6 col-md-3">
            <a href="{{ route('ventas.create') }}" class="btn btn-primary w-100">➕ Nueva venta</a>
        </div>
        <div class="col-6 col-md-3">
            <a href="{{ route('clientes.create') }}" class="btn btn-outline-secondary w-100">👤 Nuevo cliente</a>
        </div>
        <div class="col-6 col-md-3">
            <a href="{{ route('productos.index') }}" class="btn btn-outline-secondary w-100">📦 Productos</a>
        </div>
        <div class="col-6 col-md-3">
            <a href="{{ route('pedidos.index') }}" class="btn btn-outline-secondary w-100">🚚 Pedidos</a>
        </div>
    </div>

</div>

{{-- ══════════════════════════════════════════════════════
     DASHBOARD VENDEDOR
════════════════════════════════════════════════════════ --}}
@else
<div class="container-fluid py-4">

    {{-- Header personalizado --}}
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h1 class="mb-0">Bienvenido, {{ $vendedor->nombre }} 👋</h1>
            <small class="text-muted">Tu panel personal · {{ now()->format('d/m/Y H:i') }}</small>
        </div>
        <a href="{{ route('ventas.create') }}" class="btn btn-success">➕ Nueva venta</a>
    </div>

    {{-- ── Métricas de ventas ────────────────────────── --}}
    <div class="row g-3 mb-4">

        <div class="col-6 col-md-3 col-lg">
            <div class="card border-0 shadow-sm text-center h-100">
                <div class="card-body py-3">
                    <div class="fs-2 fw-bold text-primary">{{ $ventasHoy }}</div>
                    <div class="small text-muted">Ventas hoy</div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3 col-lg">
            <div class="card border-0 shadow-sm text-center h-100">
                <div class="card-body py-3">
                    <div class="fs-2 fw-bold text-info">{{ $ventasSemana }}</div>
                    <div class="small text-muted">Esta semana</div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3 col-lg">
            <div class="card border-0 shadow-sm text-center h-100">
                <div class="card-body py-3">
                    <div class="fs-2 fw-bold text-secondary">{{ $ventasMes }}</div>
                    <div class="small text-muted">Este mes</div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3 col-lg">
            <div class="card border-0 shadow-sm text-center h-100">
                <div class="card-body py-3">
                    <div class="fw-bold text-success" style="font-size:1.1rem">
                        ${{ number_format($ingresosMes, 0, ',', '.') }}
                    </div>
                    <div class="small text-muted">Ingresos mes</div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3 col-lg">
            <a href="{{ route('pedidos.index') }}"
               class="card border-0 shadow-sm text-center h-100 text-decoration-none metric-card">
                <div class="card-body py-3">
                    <div class="fs-2 fw-bold text-danger">{{ $pedidosActivos }}</div>
                    <div class="small text-muted">Pedidos activos</div>
                </div>
            </a>
        </div>

    </div>

    {{-- ── Comisiones banner ─────────────────────────── --}}
    @if($comisionPendiente > 0)
    <div class="alert border-0 rounded-3 mb-4 py-2 px-3 d-flex align-items-center gap-3"
         style="background:linear-gradient(135deg,#e8f5e9,#f1f8e9);">
        <span class="fs-4">💸</span>
        <div>
            <div class="fw-semibold small">Tienes comisiones pendientes de cobro</div>
            <div class="fw-bold text-success">${{ number_format($comisionPendiente, 0, ',', '.') }}</div>
        </div>
        <a href="{{ route('vendedores.show', $vendedor->id) }}"
           class="btn btn-sm btn-success ms-auto">Ver mis comisiones</a>
    </div>
    @endif

    <div class="row g-4">

        {{-- ── Mis ventas recientes ──────────────────── --}}
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center pt-3">
                    <h6 class="mb-0 fw-bold">Mis ventas recientes</h6>
                    <a href="{{ route('ventas.index') }}" class="btn btn-sm btn-outline-primary">Ver todas</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">#</th>
                                    <th>Cliente</th>
                                    <th>Total</th>
                                    <th>Pago</th>
                                    <th>Estado</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($ventasRecientes as $v)
                                <tr>
                                    <td class="ps-3 text-muted small">{{ $v->id }}</td>
                                    <td>{{ $v->cliente->nombre ?? '—' }}</td>
                                    <td><strong>${{ number_format($v->total, 0, ',', '.') }}</strong></td>
                                    <td>
                                        @if($v->pedido?->metodo_pago)
                                            <span class="badge bg-light text-dark">
                                                {{ Str::ucfirst($v->pedido->metodo_pago) }}
                                            </span>
                                        @else
                                            <span class="text-muted small">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        @switch($v->estado)
                                            @case('pagada')    <span class="badge bg-success">Pagada</span>    @break
                                            @case('pendiente') <span class="badge bg-warning text-dark">Pendiente</span> @break
                                            @case('cancelada') <span class="badge bg-danger">Cancelada</span>  @break
                                        @endswitch
                                    </td>
                                    <td>
                                        <a href="{{ route('ventas.show', $v->id) }}"
                                           class="btn btn-sm btn-outline-secondary py-0 px-2">Ver</a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        Aún no tienes ventas registradas.<br>
                                        <a href="{{ route('ventas.create') }}" class="btn btn-sm btn-primary mt-2">
                                            ➕ Registrar primera venta
                                        </a>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Columna derecha ───────────────────────── --}}
        <div class="col-lg-5 d-flex flex-column gap-4">

            {{-- Resumen de comisiones --}}
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pt-3 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold">💰 Mis comisiones</h6>
                    <a href="{{ route('vendedores.show', $vendedor->id) }}"
                       class="btn btn-sm btn-outline-secondary">Detalle</a>
                </div>
                <div class="card-body">
                    <div class="row text-center g-2 mb-3">
                        <div class="col-4">
                            <div class="fw-bold text-success" style="font-size:1rem">
                                ${{ number_format($comisionTotal, 0, ',', '.') }}
                            </div>
                            <div class="text-muted" style="font-size:.72rem">Total generado</div>
                        </div>
                        <div class="col-4">
                            <div class="fw-bold text-primary" style="font-size:1rem">
                                ${{ number_format($comisionCobrada, 0, ',', '.') }}
                            </div>
                            <div class="text-muted" style="font-size:.72rem">Ya cobrado</div>
                        </div>
                        <div class="col-4">
                            <div class="fw-bold text-warning" style="font-size:1rem">
                                ${{ number_format($comisionPendiente, 0, ',', '.') }}
                            </div>
                            <div class="text-muted" style="font-size:.72rem">Por cobrar</div>
                        </div>
                    </div>

                    @if($comisionTotal > 0)
                    @php $pct = min(round($comisionCobrada / $comisionTotal * 100), 100); @endphp
                    <div class="comision-bar mb-1">
                        <div class="comision-bar-fill" style="width:{{ $pct }}%"></div>
                    </div>
                    <div class="text-end text-muted" style="font-size:.72rem">{{ $pct }}% cobrado</div>
                    @endif

                    {{-- Últimas comisiones --}}
                    @if($ultimasComisiones->isNotEmpty())
                    <hr class="my-2">
                    <div class="small text-muted fw-semibold mb-2">Últimas comisiones</div>
                    @foreach($ultimasComisiones as $com)
                    <div class="d-flex justify-content-between align-items-center py-1 border-bottom small">
                        <span class="text-muted">Venta #{{ $com->venta_id }}</span>
                        <div class="d-flex gap-2 align-items-center">
                            <span class="fw-semibold">${{ number_format($com->monto_comision, 0, ',', '.') }}</span>
                            <span class="badge bg-{{ $com->estado === 'pagada' ? 'success' : ($com->estado === 'anulada' ? 'secondary' : 'warning text-dark') }}">
                                {{ $com->estado }}
                            </span>
                        </div>
                    </div>
                    @endforeach
                    @endif
                </div>
            </div>

            {{-- Top mis productos --}}
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pt-3">
                    <h6 class="mb-0 fw-bold">📦 Mis productos más vendidos</h6>
                </div>
                <div class="card-body pt-1">
                    @forelse($topProductos as $tp)
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div>
                            <div class="small fw-semibold">{{ $tp->nombre_producto }}</div>
                            <div class="text-muted" style="font-size:.75rem">
                                ${{ number_format($tp->total_ingresos, 0, ',', '.') }}
                            </div>
                        </div>
                        <span class="badge bg-primary rounded-pill">{{ $tp->total_vendido }} uds</span>
                    </div>
                    @empty
                    <p class="text-muted small mb-0">Aún no hay ventas registradas.</p>
                    @endforelse
                </div>
            </div>

        </div>
    </div>

    {{-- Accesos rápidos --}}
    <div class="row g-3 mt-2">
        <div class="col-12"><h6 class="text-muted mb-1">Accesos rápidos</h6></div>
        <div class="col-6 col-md-3">
            <a href="{{ route('ventas.create') }}" class="btn btn-primary w-100">➕ Nueva venta</a>
        </div>
        <div class="col-6 col-md-3">
            <a href="{{ route('clientes.create') }}" class="btn btn-outline-secondary w-100">👤 Nuevo cliente</a>
        </div>
        <div class="col-6 col-md-3">
            <a href="{{ route('pedidos.index') }}" class="btn btn-outline-secondary w-100">🚚 Mis pedidos</a>
        </div>
        <div class="col-6 col-md-3">
            <a href="{{ route('vendedores.show', $vendedor->id) }}"
               class="btn btn-outline-success w-100">💸 Mis comisiones</a>
        </div>
    </div>

</div>
@endif
@endsection
