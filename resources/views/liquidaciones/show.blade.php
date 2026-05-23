@extends('layouts.app')
@section('content')
<div class="container py-3" style="max-width:700px">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="mb-0">Liquidación #{{ $liquidacion->id }}</h1>
            <small class="text-muted">{{ $vendedor->nombre }}</small>
        </div>
        <a href="{{ route('vendedores.show', $vendedor) }}" class="btn btn-outline-secondary btn-sm">← Volver</a>
    </div>

    {{-- Resumen del pago --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-sm-4">
                    <div class="text-muted small">Fecha de pago</div>
                    <div class="fw-semibold">{{ $liquidacion->fecha_pago->format('d/m/Y') }}</div>
                </div>
                <div class="col-sm-4">
                    <div class="text-muted small">Método</div>
                    <div class="fw-semibold">
                        @php
                            $iconos = ['efectivo'=>'💵','transferencia'=>'🏦','cripto'=>'₿','tarjeta'=>'💳','otro'=>'📦'];
                        @endphp
                        {{ ($iconos[$liquidacion->metodo_pago] ?? '') . ' ' . ucfirst($liquidacion->metodo_pago) }}
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="text-muted small">Monto total</div>
                    <div class="fw-bold fs-5 text-success">${{ number_format($liquidacion->monto_total, 0, ',', '.') }}</div>
                </div>
                @if($liquidacion->referencia)
                <div class="col-12">
                    <div class="text-muted small">Referencia</div>
                    <div>{{ $liquidacion->referencia }}</div>
                </div>
                @endif
                @if($liquidacion->notas)
                <div class="col-12">
                    <div class="text-muted small">Notas</div>
                    <div class="fst-italic">{{ $liquidacion->notas }}</div>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Comisiones incluidas --}}
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white border-0 pt-3">
            <h6 class="fw-bold mb-0">Comisiones incluidas</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>Venta</th>
                            <th>Cliente</th>
                            <th class="text-end">Total venta</th>
                            <th class="text-center">%</th>
                            <th class="text-end">Comisión</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($liquidacion->comisiones as $com)
                    <tr>
                        <td>
                            <a href="{{ route('ventas.show', $com->venta_id) }}" class="text-decoration-none">
                                #{{ $com->venta_id }}
                            </a>
                        </td>
                        <td>{{ $com->venta->cliente->nombre ?? '—' }}</td>
                        <td class="text-end">${{ number_format($com->monto_venta, 0, ',', '.') }}</td>
                        <td class="text-center">{{ $com->porcentaje }}%</td>
                        <td class="text-end fw-semibold text-success">${{ number_format($com->monto_comision, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="4" class="fw-bold text-end pe-3">Total pagado:</td>
                            <td class="text-end fw-bold text-success fs-5">${{ number_format($liquidacion->monto_total, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection
