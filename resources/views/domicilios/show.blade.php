@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">👁️ Detalle del Domicilio</h1>

    <div class="card">
        <div class="card-body">
            <p><strong>ID:</strong> {{ $domicilio->id }}</p>
            <p><strong>Cliente:</strong> {{ $domicilio->cliente->nombre ?? '—' }}</p>
            <p><strong>Dirección:</strong> {{ $domicilio->direccion }}</p>
            <p><strong>Ciudad:</strong> {{ $domicilio->ciudad ?? '—' }}</p>
            <p><strong>Departamento:</strong> {{ $domicilio->departamento ?? '—' }}</p>
            <p><strong>País:</strong> {{ $domicilio->pais ?? '—' }}</p>
            <p><strong>Teléfono:</strong> {{ $domicilio->telefono ?? '—' }}</p>
            <p><strong>Estado:</strong> {{ ucfirst($domicilio->estado) }}</p>
            <p><strong>Costo envío:</strong> ${{ number_format($domicilio->costo_envio ?? 0, 0) }}</p>
            <p><strong>Fecha envío:</strong>
                {{ optional($domicilio->fecha_envio)->format('Y-m-d') ?? '—' }}
            </p>
            <p><strong>Fecha entrega:</strong>
                {{ optional($domicilio->fecha_entrega)->format('Y-m-d') ?? '—' }}
            </p>
            <p><strong>Comentarios:</strong> {{ $domicilio->comentarios ?? '—' }}</p>
        </div>
    </div>

    <div class="mt-3">
        <a href="{{ route('domicilios.edit', $domicilio) }}" class="btn btn-warning">Editar</a>
        <a href="{{ route('domicilios.index') }}" class="btn btn-secondary">Volver</a>
    </div>
</div>
@endsection
