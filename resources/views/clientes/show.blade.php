@extends('layouts.app')

@section('content')
<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-4 mt-3">
        <h1 class="mb-0">{{ $cliente->nombre }}</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('clientes.edit', $cliente) }}" class="btn btn-warning">✏️ Editar</a>
            <a href="{{ route('clientes.index') }}" class="btn btn-secondary">← Volver</a>
        </div>
    </div>

    <div class="row g-4">

        {{-- Info del cliente --}}
        <div class="col-md-5">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body">
                    <h5 class="mb-3">Datos del cliente</h5>
                    <table class="table table-sm table-borderless mb-0">
                        <tr><th>Nombre</th><td>{{ $cliente->nombre }}</td></tr>
                        <tr><th>Email</th><td>{{ $cliente->email ?: '—' }}</td></tr>
                        <tr><th>Teléfono</th><td>{{ $cliente->telefono ?: '—' }}</td></tr>
                        <tr><th>Dirección</th><td>{{ $cliente->direccion ?: '—' }}</td></tr>
                        <tr><th>Referido por</th><td>
                            @if($cliente->referidoPor)
                                <a href="{{ route('clientes.show', $cliente->referidoPor) }}">{{ $cliente->referidoPor->nombre }}</a>
                            @else
                                —
                            @endif
                        </td></tr>
                        @if($cliente->referidos->count())
                        <tr><th>Referidos</th><td>{{ $cliente->referidos->count() }} cliente(s)</td></tr>
                        @endif
                    </table>
                </div>
            </div>

            {{-- Resumen estadístico --}}
            <div class="card border-0 shadow-sm rounded-4 mt-3">
                <div class="card-body">
                    <h6 class="mb-3">Resumen</h6>
                    <div class="row text-center g-2">
                        <div class="col-4">
                            <div class="fs-4 fw-bold text-primary">{{ $ventas->total() }}</div>
                            <div class="small text-muted">Ventas</div>
                        </div>
                        <div class="col-4">
                            <div class="fs-5 fw-bold text-success">${{ number_format($ventas->sum('total'), 0, ',', '.') }}</div>
                            <div class="small text-muted">Total gastado</div>
                        </div>
                        <div class="col-4">
                            <div class="fs-4 fw-bold text-warning">{{ $ventas->where('estado','pendiente')->count() }}</div>
                            <div class="small text-muted">Pendientes</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Historial de ventas --}}
        <div class="col-md-7">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 pt-3 d-flex justify-content-between">
                    <h5 class="mb-0">Historial de compras</h5>
                    <a href="{{ route('ventas.create') }}?cliente={{ $cliente->id }}" class="btn btn-sm btn-primary">+ Nueva venta</a>
                </div>
                <div class="card-body p-0">
                    @if($ventas->isEmpty())
                        <p class="text-muted p-3">Este cliente no tiene ventas aún.</p>
                    @else
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">#</th>
                                    <th>Fecha</th>
                                    <th>Total</th>
                                    <th>Pago</th>
                                    <th>Estado</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($ventas as $v)
                                <tr>
                                    <td class="ps-3 text-muted">{{ $v->id }}</td>
                                    <td><small>{{ $v->created_at->format('d/m/Y') }}</small></td>
                                    <td><strong>${{ number_format($v->total, 0, ',', '.') }}</strong></td>
                                    <td>
                                        @if($v->pedido?->metodo_pago)
                                            <span class="badge bg-light text-dark">{{ Str::ucfirst($v->pedido->metodo_pago) }}</span>
                                        @else <span class="text-muted small">—</span> @endif
                                    </td>
                                    <td>
                                        @switch($v->estado)
                                            @case('pagada')    <span class="badge bg-success">Pagada</span>    @break
                                            @case('pendiente') <span class="badge bg-warning text-dark">Pendiente</span> @break
                                            @case('cancelada') <span class="badge bg-danger">Cancelada</span>  @break
                                        @endswitch
                                    </td>
                                    <td>
                                        <a href="{{ route('ventas.show', $v->id) }}" class="btn btn-xs btn-sm btn-outline-secondary py-0 px-2">Ver</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if($ventas->hasPages())
                        <div class="p-3">{{ $ventas->links() }}</div>
                    @endif
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
