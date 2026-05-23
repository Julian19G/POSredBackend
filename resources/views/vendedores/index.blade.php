@extends('layouts.app')
@section('content')
<div class="container">

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mt-3">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4 mt-3">
        <h1 class="mb-0">👥 Vendedores</h1>
        <a href="{{ route('vendedores.create') }}" class="btn btn-primary">➕ Nuevo Vendedor</a>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-bordered align-middle">
            <thead class="table-dark text-center">
                <tr>
                    <th>Nombre</th>
                    <th>Contacto</th>
                    <th>Instagram</th>
                    <th class="text-end">Comisión</th>
                    <th class="text-end">Ventas</th>
                    <th class="text-end">Total vendido</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            @forelse($vendedores as $v)
            <tr>
                <td class="fw-semibold">{{ $v->nombre }}</td>
                <td class="small">
                    @if($v->telefono) 📞 {{ $v->telefono }}<br> @endif
                    @if($v->whatsapp) 💬 {{ $v->whatsapp }}<br> @endif
                    @if($v->email)    ✉️ {{ $v->email }} @endif
                </td>
                <td class="small">{{ $v->instagram ? '@'.$v->instagram : '—' }}</td>
                <td class="text-end">{{ $v->comision_porcentaje }}%</td>
                <td class="text-center">{{ $v->ventas_count }}</td>
                <td class="text-end">${{ number_format($v->ventas_sum_total ?? 0, 0, ',', '.') }}</td>
                <td class="text-center">
                    <span class="badge {{ $v->activo ? 'bg-success' : 'bg-secondary' }}">
                        {{ $v->activo ? 'Activo' : 'Inactivo' }}
                    </span>
                </td>
                <td class="text-center">
                    <a href="{{ route('vendedores.show', $v) }}" class="btn btn-info btn-sm">Ver</a>
                    <a href="{{ route('vendedores.edit', $v) }}" class="btn btn-warning btn-sm">Editar</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center text-muted py-4">No hay vendedores registrados.</td>
            </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
