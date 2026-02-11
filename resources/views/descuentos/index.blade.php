@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>🎟️ Descuentos</h1>
        <a href="{{ route('descuentos.create') }}" class="btn btn-primary">
            + Nuevo Descuento
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>Nombre</th>
                <th>Código</th>
                <th>Tipo</th>
                <th>Valor</th>
                <th>Activo</th>
                <th>Vigencia</th>
                <th class="text-center">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($descuentos as $descuento)
                <tr>
                    <td>{{ $descuento->nombre }}</td>
                    <td><strong>{{ $descuento->codigo }}</strong></td>
                    <td>{{ ucfirst($descuento->tipo) }}</td>
                    <td>
                        {{ $descuento->tipo === 'porcentaje' ? $descuento->valor.'%' : '$'.number_format($descuento->valor, 0) }}
                    </td>
                    <td>
                        <span class="badge {{ $descuento->activo ? 'bg-success' : 'bg-secondary' }}">
                            {{ $descuento->activo ? 'Activo' : 'Inactivo' }}
                        </span>
                    </td>
                    <td>
                        {{ optional($descuento->fecha_inicio)->format('Y-m-d') ?? '—' }}
                        →
                        {{ optional($descuento->fecha_fin)->format('Y-m-d') ?? '—' }}

                    </td>
                    <td class="text-center">
                        <a href="{{ route('descuentos.show', $descuento) }}" class="btn btn-sm btn-info">Ver</a>
                        <a href="{{ route('descuentos.edit', $descuento) }}" class="btn btn-sm btn-warning">Editar</a>
                        <form action="{{ route('descuentos.destroy', $descuento) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar descuento?')">
                                Eliminar
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center text-muted">No hay descuentos registrados</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $descuentos->links() }}
</div>
@endsection
