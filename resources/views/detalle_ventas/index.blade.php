@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Detalles de Venta</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('detalle_ventas.create') }}" class="btn btn-primary mb-3">Crear Detalle de Venta</a>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Venta ID</th>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio Unitario</th>
                <th>Subtotal</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($detalleVentas as $detalle)
                <tr>
                    <td>{{ $detalle->id }}</td>
                    <td>{{ $detalle->venta_id }}</td>
                    <td>{{ $detalle->producto->nombre ?? 'Producto eliminado' }}</td>
                    <td>{{ $detalle->cantidad }}</td>
                    <td>${{ number_format($detalle->precio_unitario, 2) }}</td>
                    <td>${{ number_format($detalle->subtotal, 2) }}</td>
                    <td>
                        <a href="{{ route('detalle_ventas.show', $detalle->id) }}" class="btn btn-info btn-sm">Ver</a>
                        <a href="{{ route('detalle_ventas.edit', $detalle->id) }}" class="btn btn-warning btn-sm">Editar</a>
                        <form action="{{ route('detalle_ventas.destroy', $detalle->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Seguro que quieres eliminar este detalle?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $detalleVentas->links() }}
</div>
@endsection
