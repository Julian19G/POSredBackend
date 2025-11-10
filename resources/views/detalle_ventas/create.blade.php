@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow-lg border-0 rounded-3">
        <div class="card-header bg-dark text-white">
            <h4 class="mb-0">🧾 Registrar Detalle de Venta</h4>
        </div>
        <div class="card-body">
            {{-- Errores de validación --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>¡Ups!</strong> Hay algunos errores en el formulario.
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('detalle_ventas.store') }}" method="POST" id="detalleVentaForm">
                @csrf

                {{-- Selección de Venta --}}
                <div class="mb-3">
                    <label for="venta_id" class="form-label fw-bold">Venta asociada</label>
                    <select name="venta_id" id="venta_id" class="form-select" required>
                        <option value="">Seleccione una venta...</option>
                        @foreach($ventas as $venta)
                            <option value="{{ $venta->id }}" {{ old('venta_id') == $venta->id ? 'selected' : '' }}>
                                Venta #{{ $venta->id }} — {{ $venta->created_at->format('d/m/Y') }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Selección de Producto --}}
                <div class="mb-3">
                    <label for="producto_id" class="form-label fw-bold">Producto</label>
                    <select name="producto_id" id="producto_id" class="form-select" required>
                        <option value="">Seleccione un producto...</option>
                        @foreach($productos as $producto)
                            <option value="{{ $producto->id }}" data-precio="{{ $producto->precio }}">
                                {{ $producto->nombre }} — ${{ number_format($producto->precio, 2, ',', '.') }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="row">
                    {{-- Cantidad --}}
                    <div class="col-md-4 mb-3">
                        <label for="cantidad" class="form-label fw-bold">Cantidad</label>
                        <input type="number" name="cantidad" id="cantidad" class="form-control" min="1" value="{{ old('cantidad', 1) }}" required>
                    </div>

                    {{-- Precio Unitario --}}
                    <div class="col-md-4 mb-3">
                        <label for="precio_unitario" class="form-label fw-bold">Precio Unitario</label>
                        <input type="number" name="precio_unitario" id="precio_unitario" class="form-control" step="0.01" min="0" value="{{ old('precio_unitario') }}" required>
                    </div>

                    {{-- Descuento --}}
                    <div class="col-md-4 mb-3">
                        <label for="descuento_aplicado" class="form-label fw-bold">Descuento</label>
                        <input type="number" name="descuento_aplicado" id="descuento_aplicado" class="form-control" step="0.01" min="0" value="{{ old('descuento_aplicado', 0) }}">
                    </div>
                </div>

                <div class="row">
                    {{-- Impuesto --}}
                    <div class="col-md-4 mb-3">
                        <label for="impuesto" class="form-label fw-bold">Impuesto</label>
                        <input type="number" name="impuesto" id="impuesto" class="form-control" step="0.01" min="0" value="{{ old('impuesto', 0) }}">
                    </div>

                    {{-- Subtotal --}}
                    <div class="col-md-8 mb-3">
                        <label for="subtotal" class="form-label fw-bold">Subtotal (automático)</label>
                        <input type="number" name="subtotal" id="subtotal" class="form-control bg-light" step="0.01" min="0" readonly value="{{ old('subtotal') }}">
                        <small class="text-muted">Subtotal = (Cantidad × Precio) - Descuento + Impuesto</small>
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('detalle_ventas.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Cancelar
                    </a>
                    <button type="submit" class="btn btn-dark">
                        <i class="bi bi-save"></i> Guardar Detalle
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Script para cálculo automático --}}
<script>
    const cantidadEl = document.getElementById('cantidad');
    const precioEl = document.getElementById('precio_unitario');
    const descuentoEl = document.getElementById('descuento_aplicado');
    const impuestoEl = document.getElementById('impuesto');
    const subtotalEl = document.getElementById('subtotal');
    const productoSelect = document.getElementById('producto_id');

    productoSelect.addEventListener('change', function() {
        const precio = this.selectedOptions[0].getAttribute('data-precio') || 0;
        precioEl.value = parseFloat(precio).toFixed(2);
        calcularSubtotal();
    });

    [cantidadEl, precioEl, descuentoEl, impuestoEl].forEach(el => {
        el.addEventListener('input', calcularSubtotal);
    });

    function calcularSubtotal() {
        const cantidad = parseFloat(cantidadEl.value) || 0;
        const precio = parseFloat(precioEl.value) || 0;
        const descuento = parseFloat(descuentoEl.value) || 0;
        const impuesto = parseFloat(impuestoEl.value) || 0;

        const subtotal = (cantidad * precio) - descuento + impuesto;
        subtotalEl.value = subtotal.toFixed(2);
    }
</script>
@endsection
