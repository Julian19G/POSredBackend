@extends('layouts.app')
@section('content')

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>

<div class="container py-3">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">🚚 Domicilio #{{ $domicilio->id }}</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('domicilios.edit', $domicilio) }}" class="btn btn-warning btn-sm">✏️ Editar</a>
            <a href="{{ route('domicilios.index') }}" class="btn btn-outline-secondary btn-sm">← Volver</a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="row g-4">

        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3">📍 Información del domicilio</h6>

                    <dl class="row mb-0">
                        <dt class="col-sm-4 text-muted small">Cliente</dt>
                        <dd class="col-sm-8">
                            @if($domicilio->venta->cliente ?? null)
                                <a href="{{ route('clientes.show', $domicilio->venta->cliente_id) }}">
                                    {{ $domicilio->venta->cliente->nombre }}
                                </a>
                                <br><small class="text-muted">{{ $domicilio->venta->cliente->telefono }}</small>
                            @else —
                            @endif
                        </dd>

                        <dt class="col-sm-4 text-muted small">Dirección</dt>
                        <dd class="col-sm-8 fw-semibold">{{ $domicilio->direccion }}</dd>

                        @if($domicilio->referencia_ubicacion)
                        <dt class="col-sm-4 text-muted small">Referencia</dt>
                        <dd class="col-sm-8">{{ $domicilio->referencia_ubicacion }}</dd>
                        @endif

                        @if($domicilio->zona)
                        <dt class="col-sm-4 text-muted small">Zona</dt>
                        <dd class="col-sm-8">{{ $domicilio->zona->nombre }}</dd>
                        @endif

                        <dt class="col-sm-4 text-muted small">Ciudad</dt>
                        <dd class="col-sm-8">{{ $domicilio->ciudad ?? '—' }}, {{ $domicilio->departamento ?? '' }}</dd>

                        <dt class="col-sm-4 text-muted small">Comentarios</dt>
                        <dd class="col-sm-8 fst-italic">{{ $domicilio->comentarios ?? '—' }}</dd>

                        <dt class="col-sm-4 text-muted small">Estado</dt>
                        <dd class="col-sm-8">
                            <span class="badge bg-{{ $domicilio->estado === 'entregado' ? 'success' : ($domicilio->estado === 'cancelado' ? 'danger' : 'warning') }}">
                                {{ ucfirst($domicilio->estado) }}
                            </span>
                        </dd>

                        <dt class="col-sm-4 text-muted small">Costo envío</dt>
                        <dd class="col-sm-8">${{ number_format($domicilio->costo_envio ?? 0, 0, ',', '.') }}</dd>

                        @if($domicilio->fecha_envio)
                        <dt class="col-sm-4 text-muted small">Enviado</dt>
                        <dd class="col-sm-8">{{ $domicilio->fecha_envio->format('d/m/Y H:i') }}</dd>
                        @endif

                        @if($domicilio->fecha_entrega)
                        <dt class="col-sm-4 text-muted small">Entregado</dt>
                        <dd class="col-sm-8">{{ $domicilio->fecha_entrega->format('d/m/Y H:i') }}</dd>
                        @endif

                        <dt class="col-sm-4 text-muted small">Venta</dt>
                        <dd class="col-sm-8">
                            <a href="{{ route('ventas.show', $domicilio->venta_id) }}">#{{ $domicilio->venta_id }}</a>
                        </dd>
                    </dl>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            {{-- Mapa --}}
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-2">🗺 Ubicación</h6>
                    @if($domicilio->tieneUbicacion())
                    <div id="mapa-show" style="height:280px;border-radius:10px"></div>
                    @else
                    <div class="d-flex flex-column align-items-center justify-content-center text-muted"
                         style="height:280px;background:#f8f9fa;border-radius:10px">
                        <span style="font-size:2rem">📌</span>
                        <p class="mb-2">Sin coordenadas registradas</p>
                        <a href="{{ route('domicilios.edit', $domicilio) }}" class="btn btn-sm btn-outline-primary">
                            Agregar ubicación
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>

    </div>

</div>

@if($domicilio->tieneUbicacion())
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
const map = L.map('mapa-show').setView([{{ $domicilio->latitud }}, {{ $domicilio->longitud }}], 16);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap'
}).addTo(map);
L.marker([{{ $domicilio->latitud }}, {{ $domicilio->longitud }}])
    .addTo(map)
    .bindPopup(`<strong>{{ addslashes($domicilio->direccion) }}</strong><br>{{ addslashes($domicilio->venta->cliente->nombre ?? '') }}`)
    .openPopup();
</script>
@endif
@endsection
