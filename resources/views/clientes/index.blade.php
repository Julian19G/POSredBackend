@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Clientes</h2>

    @if(session('success'))
        <div class="alert alert-success">{{session('success' )}}</div>
    @endif

    <a href="{{ route('clientes.create') }}" class="btn btn-primary mb-3">+ Nuevo Cleinte </a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Email</th>
                <th>Telefono</th>
                <th>Referido Por</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($clientes as $cliente)
                <tr>
                    <td>{{ $cliente->nombre}}</td>
                    <td>{{ $cliente->email}}</td>
                    <td>{{ $cliente->telefono}}</td>
                    <td>{{ $cliente->referidoPor->nombre ?? 'N/A'}}</td>
                    <td>
                        <a href="{{ route('cliente.show',$cliente) }}" class="btn btn-sm btn-info">Ver</a>
                        <a href="{{ route('cliente.edit', $cliente }}" class="btn btn-sm btn-warning">Editar</a>
                        <form action="{{ route('clientes.destroy', $cliente }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button onclick="return confirm('¿Seguro?')" class="btn btn-sm btn-danger">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
