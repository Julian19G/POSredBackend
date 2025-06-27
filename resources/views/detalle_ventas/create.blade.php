@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Crear Detalle de Venta</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('detalle_ventas.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="venta_id" class="form-label">Venta</label>
            <select name="venta_id" id="venta_id" class="form-select" required>
                <option value="">Seleccione una venta</option>
                @foreach($ventas as $venta)
                    <option value="{{ $venta->id }}" {{ old('venta_id') == $venta->id ? 'selected' : '' }}>
                        Venta #{{ $venta->id }} - {{ $venta->created_at->format('d/m/Y') }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="producto_id" class="form-label">Producto</label>
            <select name="producto_id" id="producto_id" class="form-select" required>
                <option value="">Seleccione un producto</option>
                @foreach($productos as $producto)
                    <option value="{{ $producto->id }}" {{ old('producto_id') == $producto->id ? 'selected' : '' }}>
                        {{ $producto->nombre }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="cantidad" class="form-label">Cantidad</label>
            <input type="number" name="cantidad" id="cantidad" class="form-control" min="1" value="{{ old('cantidad', 1) }}" required>
        </div>

        <div class="mb-3">
            <label for="precio_unitario" class="form-label">Precio Unitario</label>
            <input type="number" name="precio_unitario" id="precio_unitario" step="0.01" min="0" class="form-control" value="{{ old('precio_unitario') }}" required>
        </div>

        <div class="mb-3">
            <label for="subtotal" class="form-label">Subtotal</label>
            <input type="number" name="subtotal" id="subtotal" step="0.01" min="0" class="form-control" value="{{ old('subtotal') }}" required>
            <small class="text-muted">Cantidad * Precio Unitario</small>
        </div>

        <button type="submit" class="btn btn-primary">Guardar</button>
        <a href="{{ route('detalle_ventas.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<script>
    // Opcional: calcular subtotal automáticamente
    document.getElementById('cantidad').addEventListener('input', calcularSubtotal);
    document.getElementById('precio_unitario').addEventListener('input', calcularSubtotal);

    function calcularSubtotal() {
        const cantidad = parseFloat(document.getElementById('cantidad').value) || 0;
        const precio = parseFloat(document.getElementById('precio_unitario').value) || 0;
        document.getElementById('subtotal').value = (cantidad * precio).toFixed(2);
    }
</script>
@endsection
