@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mt-3 mb-4">
        <div>
            <h1 class="mb-0">{{ $d->nombre }}</h1>
            <small class="text-muted">{{ $d->vehiculoLabel() }} &bull; {{ $d->user->email }}</small>
        </div>
        @if(auth()->user()->isAdmin())
            <div class="d-flex gap-2">
                <a href="{{ route('domiciliarios.edit', $d) }}" class="btn btn-warning">Editar</a>
                <a href="{{ route('domiciliarios.index') }}" class="btn btn-outline-secondary">Volver</a>
            </div>
        @endif
    </div>

    {{-- Métricas rápidas --}}
    <div class="row g-3 mb-4">
        <div class="col-sm-4">
            <div class="card border-0 shadow-sm text-center py-3">
                <div class="fs-2 fw-bold text-primary">{{ $d->entregas_count }}</div>
                <div class="text-muted small">Entregas totales</div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="card border-0 shadow-sm text-center py-3">
                <div class="fs-2 fw-bold text-success">{{ $d->entregasMes() }}</div>
                <div class="text-muted small">Este mes</div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="card border-0 shadow-sm text-center py-3">
                <div class="fs-2 fw-bold {{ $d->activo ? 'text-success' : 'text-secondary' }}">
                    {{ $d->activo ? 'Activo' : 'Inactivo' }}
                </div>
                <div class="text-muted small">Estado</div>
            </div>
        </div>
    </div>

    {{-- Rutas recientes --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white fw-semibold">Últimas rutas</div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-light text-center">
                    <tr>
                        <th>ID</th>
                        <th>Tipo</th>
                        <th>Estado</th>
                        <th>Domicilios</th>
                        <th>Fecha</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                @forelse($d->rutas as $ruta)
                    <tr class="text-center">
                        <td>#{{ $ruta->id }}</td>
                        <td>{{ $ruta->tipoLabel() }}</td>
                        <td><span class="badge bg-{{ $ruta->estadoColor() }}">{{ ucfirst($ruta->estado) }}</span></td>
                        <td>{{ $ruta->domicilios_count ?? '—' }}</td>
                        <td><small>{{ $ruta->created_at->format('d/m/Y H:i') }}</small></td>
                        <td><a href="{{ route('rutas.show', $ruta) }}" class="btn btn-xs btn-sm btn-outline-info">Ver</a></td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-3">Sin rutas registradas.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
