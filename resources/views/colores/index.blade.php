@extends('layouts.app')

@section('title', 'Lista de Colores')

@section('content')
<div class="container">

    <h1 class="mb-4">Colores</h1>

    <a href="{{ route('colores.create') }}" class="btn btn-primary mb-3">Crear nuevo color</a>

    <div class="card shadow">
        <div class="card-body">

            @if ($colores->count())
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Código HEX</th>
                        <th>Color</th>
                        <th>Activo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($colores as $color)
                    <tr>
                        <td>{{ $color->id }}</td>
                        <td>{{ $color->nombre }}</td>
                        <td>{{ $color->codigo_hex ?? '—' }}</td>

                        <td>
                            @if($color->codigo_hex)
                                <div style="width:35px; height:35px; border-radius:5px; background: {{ $color->codigo_hex }}; border: 1px solid #999;"></div>
                            @else
                                —
                            @endif
                        </td>

                        <td>
                            @if($color->activo)
                                <span class="badge bg-success">Activo</span>
                            @else
                                <span class="badge bg-danger">Inactivo</span>
                            @endif
                        </td>

                        <td>
                            <a href="{{ route('colores.show', $color->id) }}" class="btn btn-info btn-sm">Ver</a>
                            <a href="{{ route('colores.edit', $color->id) }}" class="btn btn-warning btn-sm">Editar</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
                <p class="text-muted">No hay colores registrados aún.</p>
            @endif

        </div>
    </div>
</div>
@endsection
