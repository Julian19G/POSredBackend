@extends('layouts.app')

@section('content')
<div class="container">
    <h1>✏️ Editar Venta #{{ $venta->id }}</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('ventas.update', $venta->id) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Cliente --}}
        <div class="mb-3">
            <label for="cliente_id" class="form-label">Cliente</label>
            <select name="cliente_id" id="cliente_id" class="form-select" required>
                @foreach(\App\Models\Cliente::all() as $cliente)
                    <option value="{{ $cliente->id }}" {{ $venta->cliente_id == $cliente->id ? 'selected' : '' }}>
                        {{ $cliente->nombre }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Productos --}}
        <div id="productos-container">
            @foreach($venta->detalles as $detalle)
            <div class="producto-row row mb-3">
                <div class="col-md-6">
                    <label>Producto</label>
                    <select name="productos[]" class="form-select" required>
                        <option value="">Seleccione un producto</option>
                        @foreach($productos as $producto)
                            <option value="{{ $producto->id }}" {{ $detalle->producto_id == $producto->id ? 'selected' : '' }}>
                                {{ $producto->nombre }} - ${{ number_format($producto->precio, 2) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label>Cantidad</label>
                    <input type="number" name="cantidades[]" class="form-control" min="1" value="{{ $detalle->cantidad }}" required>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="button" class="btn btn-danger btn-remove">X</button>
                </div>
            </div>
            @endforeach
        </div>
        <button type="button" id="add-producto" class="btn btn-secondary mb-3">Agregar otro producto</button>

        {{-- Descuento manual y motivo --}}
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="descuento_manual" class="form-label">Descuento manual</label>
                <input type="number" name="descuento_manual" id="descuento_manual" class="form-control" value="{{ $venta->descuento_manual }}" step="0.01" min="0">
            </div>
            <div class="col-md-6">
                <label for="motivo_descuento" class="form-label">Motivo del descuento</label>
                <input type="text" name="motivo_descuento" id="motivo_descuento" class="form-control" value="{{ $venta->motivo_descuento }}">
            </div>
        </div>

        {{-- Costo de envío y dirección --}}
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="costo_envio" class="form-label">Costo de envío</label>
                <input type="number" name="costo_envio" id="costo_envio" class="form-control" value="{{ $venta->costo_envio }}" step="0.01" min="0">
            </div>
            <div class="col-md-6">
                <label for="direccion_envio" class="form-label">Dirección de envío</label>
                <input type="text" name="direccion_envio" id="direccion_envio" class="form-control" value="{{ $venta->direccion_envio }}">
            </div>
        </div>

        {{-- Estado de la venta --}}
        <div class="mb-3">
            <label for="estado" class="form-label">Estado</label>
            <select name="estado" id="estado" class="form-select" required>
                <option value="pendiente" {{ $venta->estado == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                <option value="pagada" {{ $venta->estado == 'pagada' ? 'selected' : '' }}>Pagada</option>
                <option value="cancelada" {{ $venta->estado == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Actualizar Venta</button>
    </form>
</div>

{{-- Script para agregar/quitar productos --}}
<script>
    document.getElementById('add-producto').addEventListener('click', function () {
        const container = document.getElementById('productos-container');
        const firstRow = container.querySelector('.producto-row');
        const newRow = firstRow.cloneNode(true);
        newRow.querySelector('select').selectedIndex = 0;
        newRow.querySelector('input').value = 1;
        container.appendChild(newRow);
    });

    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('btn-remove')) {
            const row = e.target.closest('.producto-row');
            if (document.querySelectorAll('.producto-row').length > 1) {
                row.remove();
            }
        }
    });
</script>
@endsection
