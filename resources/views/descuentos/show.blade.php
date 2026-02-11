    @extends('layouts.app')

    @section('content')
    <div class="container">
        <h1 class="mb-4">👁️ Detalle del Descuento</h1>

        <div class="card">
            <div class="card-body">
                <p><strong>Nombre:</strong> {{ $descuento->nombre }}</p>
                <p><strong>Código:</strong> {{ $descuento->codigo }}</p>
                <p><strong>Tipo:</strong> {{ ucfirst($descuento->tipo) }}</p>
                <p><strong>Valor:</strong>
                    {{ $descuento->tipo === 'porcentaje' ? $descuento->valor.'%' : '$'.number_format($descuento->valor, 0) }}
                </p>
                <p><strong>Activo:</strong> {{ $descuento->activo ? 'Sí' : 'No' }}</p>
                <p><strong>Fecha inicio:</strong> {{ $descuento->fecha_inicio->format('Y-m-d') }}</p>
                <p><strong>Fecha fin:</strong> {{ $descuento->fecha_fin->format('Y-m-d') }}</p>
                <p><strong>Uso máximo:</strong> {{ $descuento->uso_maximo ?? 'Ilimitado' }}</p>
                <p><strong>Uso por cliente:</strong> {{ $descuento->uso_cliente_maximo ?? 'Ilimitado' }}</p>
            </div>
        </div>

        <div class="mt-3">
            <a href="{{ route('descuentos.edit', $descuento) }}" class="btn btn-warning">Editar</a>
            <a href="{{ route('descuentos.index') }}" class="btn btn-secondary">Volver</a>
        </div>
    </div>
    @endsection
