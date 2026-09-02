@extends('layouts.app')

@section('content')
<div class="container">

    <div class="d-flex justify-content-between align-items-center mt-3 mb-3">
        <div>
            <h1 class="mb-0">Domicilios disponibles</h1>
            @if($tarifa)
                <small class="text-muted">
                    Tarifa vigente:
                    <strong class="text-success">${{ number_format($tarifa->monto, 0, ',', '.') }}</strong>
                    — {{ $tarifa->nombre }}
                    ({{ substr($tarifa->hora_inicio, 0, 5) }} a {{ substr($tarifa->hora_fin, 0, 5) }})
                </small>
            @endif
        </div>
        <a href="{{ route('rutas.index') }}" class="btn btn-outline-secondary btn-sm">Mis rutas</a>
    </div>

    @if($domicilios->isEmpty())
        <div class="text-center py-5">
            <div class="fs-1 mb-2">📦</div>
            <h4 class="text-muted">No hay domicilios disponibles</h4>
            <p class="text-muted">En cuanto los vendedores registren ventas con envío, aparecerán aquí.</p>
        </div>
    @else

    <form action="{{ route('rutas.store') }}" method="POST" id="form-ruta">
        @csrf
        <input type="hidden" name="tipo" id="input-tipo" value="ruta">

        <div class="row g-3 mb-4">
            @foreach($domicilios as $d)
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-3 domicilio-card" data-id="{{ $d->id }}">
                    <div class="card-body">
                        <div class="row align-items-center">

                            {{-- Checkbox --}}
                            <div class="col-auto">
                                <input type="checkbox" name="domicilio_ids[]" value="{{ $d->id }}"
                                       class="form-check-input fs-4 domicilio-check"
                                       id="dcheck{{ $d->id }}">
                            </div>

                            {{-- Info cliente --}}
                            <div class="col">
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <strong class="fs-6">{{ $d->nombre_cliente }}</strong>
                                    @if($d->tipo === 'express')
                                        <span class="badge bg-warning text-dark">⚡ Express</span>
                                    @endif
                                    @if($d->venta && $d->venta->estado !== 'pagada')
                                        <span class="badge bg-danger">💰 Cobrar</span>
                                    @else
                                        <span class="badge bg-success">✅ Ya pagado</span>
                                    @endif
                                </div>
                                <div class="text-muted small">
                                    📍 {{ $d->direccion }}
                                    @if($d->ciudad) — {{ $d->ciudad }} @endif
                                    @if($d->zona) · <span class="text-info">{{ $d->zona->nombre }}</span> @endif
                                </div>
                                @if($d->comentarios)
                                    <div class="text-muted small mt-1">💬 {{ $d->comentarios }}</div>
                                @endif
                            </div>

                            {{-- Monto / tarifa --}}
                            <div class="col-auto text-end">
                                @if($d->venta && $d->venta->estado !== 'pagada')
                                    <div class="fw-bold text-danger fs-5">
                                        ${{ number_format($d->venta->total, 0, ',', '.') }}
                                    </div>
                                    <small class="text-muted">a cobrar</small>
                                @else
                                    <div class="text-success fw-bold">Pagado</div>
                                @endif
                                @if($d->tarifa_monto)
                                    <div class="text-primary small mt-1">
                                        Tarifa: ${{ number_format($d->tarifa_monto, 0, ',', '.') }}
                                    </div>
                                @endif
                                <small class="text-muted">{{ $d->created_at->diffForHumans() }}</small>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Barra de acción fija --}}
        <div class="position-fixed bottom-0 start-0 end-0 bg-white border-top shadow-lg px-4 py-3 d-flex justify-content-between align-items-center"
             id="action-bar" style="display:none!important">
            <div>
                <span class="fw-bold" id="count-label">0 seleccionados</span>
                <small class="text-muted ms-2">Elige el tipo de ruta</small>
            </div>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-outline-secondary btn-sm" id="btn-deselect">
                    Deseleccionar todo
                </button>
                <button type="submit" class="btn btn-warning btn-sm" id="btn-express"
                        title="Toma solo el primero seleccionado como express"
                        onclick="document.getElementById('input-tipo').value='express'">
                    ⚡ Express (1)
                </button>
                <button type="submit" class="btn btn-primary" id="btn-ruta"
                        onclick="document.getElementById('input-tipo').value='ruta'">
                    🗺 Crear Ruta (<span id="ruta-count">0</span>)
                </button>
            </div>
        </div>
    </form>
    @endif
</div>
@endsection

@push('scripts')
<script>
(function () {
    const checks   = document.querySelectorAll('.domicilio-check');
    const bar      = document.getElementById('action-bar');
    const countLbl = document.getElementById('count-label');
    const rutaSpan = document.getElementById('ruta-count');
    const btnExpr  = document.getElementById('btn-express');
    const btnDesel = document.getElementById('btn-deselect');

    function update() {
        const selected = document.querySelectorAll('.domicilio-check:checked').length;
        if (selected > 0) {
            bar.style.display = 'flex';
        } else {
            bar.style.display = 'none';
        }
        countLbl.textContent = selected + ' seleccionado(s)';
        rutaSpan.textContent = selected;
        btnExpr.disabled = selected === 0;
    }

    checks.forEach(function (ch) {
        ch.addEventListener('change', function () {
            const card = ch.closest('.domicilio-card');
            card.classList.toggle('border-primary', ch.checked);
            card.classList.toggle('shadow', ch.checked);
            update();
        });
    });

    btnDesel && btnDesel.addEventListener('click', function () {
        checks.forEach(function (ch) {
            ch.checked = false;
            ch.closest('.domicilio-card').classList.remove('border-primary', 'shadow');
        });
        update();
    });

    update();
})();
</script>
@endpush
