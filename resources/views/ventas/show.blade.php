@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Detalles de la Venta #{{ $venta->id }}</h1>
    <p><strong>Total:</strong> ${{ number_format($venta->total, 2) }}</p>
    <p><strong>Fecha:</strong> {{ $venta->created_at }}</p>

    <table class="table">
        <thead>
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio Unitario</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($venta->detalles as $detalle)
                <tr>
                    <td>{{ $detalle->producto->nombre }}</td>
                    <td>{{ $detalle->cantidad }}</td>
                    <td>${{ $detalle->precio_unitario }}</td>
                    <td>${{ $detalle->subtotal }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <a href="{{ route('ventas.index') }}" class="btn btn-secondary">Volver</a>
</div>
@endsection