@extends('layouts.app')

@section('title', 'Lista de Efectos')

@section('content')
<div class="container">

    <h1 class="mb-4">Efectos</h1>

    <a href="{{ route('efectos.create') }}" class="btn btn-primary mb-3">Crear nuevo efecto</a>

    <div class="card shadow">
        <div class="card-body">

            @if($efectos->count() > 0)
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Tipo</th>
                        <th>Activo</th>
                        <th>Imagen</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($efectos as $efecto)
                    <tr>
                        <td>{{ $efecto->id }}</td>
                        <td>{{ $efecto->nombre }}</td>
                        <td>
                            <span class="badge 
                                @if($efecto->tipo == 'positivo') bg-success
                                @elseif($efecto->tipo == 'negativo') bg-danger
                                @else bg-secondary @endif
                            ">
                                {{ ucfirst($efecto->tipo) }}
                            </span>
                        </td>

                        <td>
                            @if($efecto->activo)
                                <span class="badge bg-success">Activo</span>
                            @else
                                <span class="badge bg-danger">Inactivo</span>
                            @endif
                        </td>

                        <td>
                            @if($efecto->imagen)
                                <img src="{{ asset('storage/'.$efecto->imagen) }}" width="50" class="rounded">
                            @else
                                —
                            @endif
                        </td>

                        <td>
                            <a href="{{ route('efectos.show', $efecto->id) }}" class="btn btn-info btn-sm">Ver</a>
                            <a href="{{ route('efectos.edit', $efecto->id) }}" class="btn btn-warning btn-sm">Editar</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            @else
                <p class="text-muted">No hay efectos registrados aún.</p>
            @endif

        </div>
    </div>
</div>
@endsection
