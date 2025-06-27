@extends('layouts.app')

@section('content')
    <h1>Productos</h1>

    <a href="{{ route('productos.create') }}" class="btn btn-success mb-3">Nuevo Producto</a>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Precio</th>
                <th>Stock</th>
                <th>Creado</th>
                <th>Actualizado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($productos as $producto)
            <tr>
                <td>{{ $producto->id }}</td>
                <td>{{ $producto->nombre }}</td>
                <td>{{ $producto->descripcion }}</td>
                <td>${{ number_format($producto->precio, 2) }}</td>
                <td>{{ $producto->stock }}</td>
                <td>{{ $producto->created_at->format('Y-m-d') }}</td>
                <td>{{ $producto->updated_at->format('Y-m-d') }}</td>
                <td>
                    <a href="{{ route('productos.edit', $producto) }}" class="btn btn-sm btn-primary">Editar</a>
                    <form action="{{ route('productos.destroy', $producto) }}" method="POST" style="display:inline-block">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar este producto?')">Eliminar</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
