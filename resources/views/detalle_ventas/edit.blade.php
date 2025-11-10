@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">✏️ Editar Detalle de Venta #{{ $detalleVenta->id }}</h1>

    {{-- Mostrar errores de validación --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Se encontraron algunos errores:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Formulario --}}
    <form action="{{ route('detalle_ventas.update', $detalleVenta->id) }}" method="POST" class="card shadow-sm p-4">
        @csrf
        @method('PUT')

        {{-- Venta asociada --}}
        <div class="mb-3">
            <label for="venta_id" class="form-label fw-semibold">Venta</label>
            <select name="venta_id" id="venta_id" class="form-select" required>
                <option value="">Seleccione una venta</option>
                @foreach($ventas as $venta)
                    <option value="{{ $venta->id }}" {{ old('venta_id', $detalleVenta->venta_id) == $venta->id ? 'selected' : '' }}>
                        Venta #{{ $venta->id }} — {{ $venta->created_at->format('d/m/Y') }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Producto --}}
        <div class="mb-3">
            <label for="producto_id" class="form-label fw-semibold">Producto</label>
            <select name="producto_id" id="producto_id" class="form-select" required>
                <option value="">Seleccione un producto</option>
                @foreach($productos as $producto)
                    <option value="{{ $producto->id }}" {{ old('producto_id', $detalleVenta->producto_id) == $producto->id ? 'selected' : '' }}>
                        {{ $producto->nombre }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Nombre y código del producto (solo lectura) --}}
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="nombre_producto" class="form-label fw-semibold">Nombre del Producto</label>
                <input type="text" id="nombre_producto" name="nombre_producto" class="form-control" 
                       value="{{ old('nombre_producto', $detalleVenta->nombre_producto) }}" readonly>
            </div>

            <div class="col-md-6 mb-3">
                <label for="codigo_producto" class="form-label fw-semibold">Código del Producto</label>
                <input type="text" id="codigo_producto" name="codigo_producto" class="form-control" 
                       value="{{ old('codigo_producto', $detalleVenta->codigo_producto) }}" readonly>
            </div>
        </div>

        {{-- Cantidad y precio --}}
        <div class="row">
            <div class="col-md-4 mb-3">
                <label for="cantidad" class="form-label fw-semibold">Cantidad</label>
                <input type="number" name="cantidad" id="cantidad" class="form-control" min="1"
                       value="{{ old('cantidad', $detalleVenta->cantidad) }}" required>
            </div>

            <div class="col-md-4 mb-3">
                <label for="precio_unitario" class="form-label fw-semibold">Precio Unitario</label>
                <input type="number" name="precio_unitario" id="precio_unitario" class="form-control"
                       step="0.01" min="0" value="{{ old('precio_unitario', $detalleVenta->precio_unitario) }}" required>
            </div>

            <div class="col-md-4 mb-3">
                <label for="descuento_aplicado" class="form-label fw-semibold">Descuento (opcional)</label>
                <input type="number" name="descuento_aplicado" id="descuento_aplicado" class="form-control"
                       step="0.01" min="0" value="{{ old('descuento_aplicado', $detalleVenta->descuento_aplicado) }}">
            </div>
        </div>

        {{-- Impuesto y subtotal --}}
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="impuesto" class="form-label fw-semibold">Impuesto (%)</label>
                <input type="number" name="impuesto" id="impuesto" class="form-control"
                       step="0.01" min="0" value="{{ old('impuesto', $detalleVenta->impuesto) }}">
            </div>

            <div class="col-md-6 mb-3">
                <label for="subtotal" class="form-label fw-semibold">Subtotal</label>
                <input type="number" name="subtotal" id="subtotal" class="form-control" 
                       step="0.01" min="0" value="{{ old('subtotal', $detalleVenta->subtotal) }}" readonly>
                <small class="text-muted">Calculado automáticamente (Cantidad × Precio – Descuento + Impuesto)</small>
            </div>
        </div>

        {{-- Botones --}}
        <div class="d-flex justify-content-between mt-4">
            <a href="{{ route('detalle_ventas.index') }}" class="btn btn-outline-secondary">
                Cancelar
            </a>
            <button type="submit" class="btn btn-primary">
                💾 Actualizar Detalle
            </button>
        </div>
    </form>
</div>

{{-- Script para cálculo automático --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
    const cantidad = document.getElementById('cantidad');
    const precio = document.getElementById('precio_unitario');
    const descuento = document.getElementById('descuento_aplicado');
    const impuesto = document.getElementById('impuesto');
    const subtotal = document.getElementById('subtotal');

    function calcularSubtotal() {
        const c = parseFloat(cantidad.value) || 0;
        const p = parseFloat(precio.value) || 0;
        const d = parseFloat(descuento.value) || 0;
        const i = parseFloat(impuesto.value) || 0;

        const base = (c * p) - d;
        const total = base + (base * (i / 100));
        subtotal.value = total.toFixed(2);
    }

    [cantidad, precio, descuento, impuesto].forEach(el => {
        el.addEventListener('input', calcularSubtotal);
    });

    calcularSubtotal();
});
</script>
@endsection
