@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Relaciones Pivote de Productos</h1>

    <table class="table table-bordered table-hover text-center">
        <thead class="table-dark">
            <tr>
                <th>Producto ID</th>
                <th>Nombre Producto</th>
                <th>Colores</th>
                <th>Efectos</th>
                <th>Sabores</th>
            </tr>
        </thead>
        <tbody>
            @foreach($productos as $producto)
                <tr>
                    <td>{{ $producto->id }}</td>
                    <td>{{ $producto->nombre }}</td>

                    <!-- Colores con color real del HEX -->
                    <td>
                        @foreach($producto_colores->where('producto_id', $producto->id) as $pc)
                            <span class="badge text-white" style="background-color: {{ $pc->codigo_hex ?? '#000' }};">
                                {{ $pc->color_nombre }}
                            </span>
                        @endforeach
                    </td>

                    <!-- Efectos -->
                    <td>
                        @foreach($producto_efectos->where('producto_id', $producto->id) as $pe)
                            <span class="badge bg-success">{{ $pe->efecto_nombre }}</span>
                        @endforeach
                    </td>

                    <!-- Sabores -->
                    <td>
                        @foreach($producto_sabores->where('producto_id', $producto->id) as $ps)
                            <span class="badge bg-warning text-dark">{{ $ps->sabor_nombre }}</span>
                        @endforeach
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
