@extends('layouts.app')

@section('content')
<div class="container">

    <div class="d-flex justify-content-between align-items-center mt-3 mb-4">
        <h1 class="mb-0">
            {{ auth()->user()->isAdmin() ? 'Todas las Rutas' : 'Mis Rutas' }}
        </h1>
        @if(auth()->user()->isDomiciliario())
            <a href="{{ route('rutas.disponibles') }}" class="btn btn-success">
                📦 Ver domicilios disponibles
            </a>
        @endif
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-bordered align-middle">
            <thead class="table-dark text-center">
                <tr>
                    <th>ID</th>
                    @if(auth()->user()->isAdmin())
                        <th class="text-start">Domiciliario</th>
                    @endif
                    <th>Tipo</th>
                    <th>Estado</th>
                    <th>Domicilios</th>
                    <th>Creada</th>
                    <th>Completada</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            @forelse($rutas as $ruta)
                <tr class="text-center">
                    <td>#{{ $ruta->id }}</td>
                    @if(auth()->user()->isAdmin())
                        <td class="text-start">{{ $ruta->domiciliario->nombre ?? '—' }}</td>
                    @endif
                    <td>{{ $ruta->tipoLabel() }}</td>
                    <td><span class="badge bg-{{ $ruta->estadoColor() }}">{{ ucfirst($ruta->estado) }}</span></td>
                    <td>
                        @php
                            $total      = $ruta->domicilios->count();
                            $entregados = $ruta->domicilios->where('estado', 'entregado')->count();
                        @endphp
                        <div class="progress" style="height:6px;min-width:80px" title="{{ $entregados }}/{{ $total }}">
                            <div class="progress-bar bg-success"
                                 style="width:{{ $total > 0 ? ($entregados / $total * 100) : 0 }}%"></div>
                        </div>
                        <small class="text-muted">{{ $entregados }}/{{ $total }}</small>
                    </td>
                    <td><small>{{ $ruta->created_at->format('d/m/Y H:i') }}</small></td>
                    <td>
                        @if($ruta->fecha_completada)
                            <small class="text-success">{{ $ruta->fecha_completada->format('d/m/Y H:i') }}</small>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('rutas.show', $ruta) }}" class="btn btn-sm btn-info">Ver</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="{{ auth()->user()->isAdmin() ? 8 : 7 }}" class="text-center text-muted py-5">
                        @if(auth()->user()->isDomiciliario())
                            <div class="fs-2 mb-2">🛵</div>
                            No tienes rutas todavía.<br>
                            <a href="{{ route('rutas.disponibles') }}" class="btn btn-success mt-2">
                                Ver domicilios disponibles
                            </a>
                        @else
                            No hay rutas registradas.
                        @endif
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    {{ $rutas->links() }}
</div>
@endsection
