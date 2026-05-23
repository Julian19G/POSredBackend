@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">✏️ Editar Venta #{{ $venta->id }}</h1>

    {{-- Errores --}}
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

        {{-- Cliente + Vendedor --}}
        <div class="row mb-3 g-3">
            <div class="col-md-7">
                <label for="cliente_id" class="form-label fw-semibold">Cliente</label>
                <select name="cliente_id" id="cliente_id" class="form-select" required>
                    @foreach($clientes as $cliente)
                        <option value="{{ $cliente->id }}" {{ $venta->cliente_id == $cliente->id ? 'selected' : '' }}>
                            {{ $cliente->nombre }}{{ $cliente->telefono ? ' - ' . $cliente->telefono : '' }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-5">
                <label class="form-label fw-semibold">Vendedor</label>
                <select name="vendedor_id" class="form-select">
                    <option value="">— Sin asignar —</option>
                    @foreach($vendedores as $v)
                        <option value="{{ $v->id }}" {{ $venta->vendedor_id == $v->id ? 'selected' : '' }}>
                            {{ $v->nombre }}
                            @if($v->comision_porcentaje > 0) ({{ $v->comision_porcentaje }}%) @endif
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Productos --}}
        <h5 class="mt-4">🧾 Productos</h5>
        <div id="productos-container">
            @foreach($venta->detalles as $detalle)
            <div class="producto-row row mb-2 g-2 align-items-end">
                <input type="hidden" name="productos[]" value="{{ $detalle->producto_id }}">
                <input type="hidden" name="variantes[]"  value="{{ $detalle->variante_id ?? 0 }}">

                <div class="col-md-4">
                    <label class="form-label small">Producto / variante</label>
                    <select name="variante_display[]" class="form-select form-select-sm variante-select" disabled>
                        @foreach($productos as $producto)
                            @foreach($producto->variantes as $variante)
                                <option value="{{ $variante->id }}"
                                    data-precio="{{ $variante->precio }}"
                                    data-producto="{{ $producto->id }}"
                                    {{ $detalle->variante_id == $variante->id ? 'selected' : '' }}>
                                    {{ $producto->nombre }} – {{ $variante->nombre }}
                                    (${{ number_format($variante->precio, 0, ',', '.') }})
                                </option>
                            @endforeach
                        @endforeach
                    </select>
                    <small class="text-muted">
                        {{ $detalle->nombre_producto }}
                        @if($detalle->nombre_variante) – {{ $detalle->nombre_variante }} @endif
                    </small>
                </div>
                <div class="col-md-2">
                    <label class="form-label small">Precio unit.</label>
                    <input type="number" name="precios_manuales[]" class="form-control form-control-sm precio-input"
                           step="1" min="0" value="{{ $detalle->precio_unitario }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label small">Cantidad</label>
                    <input type="number" name="cantidades[]" class="form-control form-control-sm cantidad-input"
                           min="1" value="{{ $detalle->cantidad }}" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label small">Subtotal</label>
                    <input type="text" class="form-control form-control-sm subtotal-input" readonly
                           value="{{ number_format($detalle->precio_unitario * $detalle->cantidad, 0, ',', '.') }}">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="button" class="btn btn-sm btn-outline-danger btn-remove w-100">✖ Quitar</button>
                </div>
            </div>
            @endforeach
        </div>

        <button type="button" id="add-producto" class="btn btn-outline-secondary btn-sm mb-3">➕ Agregar producto</button>

        {{-- Envío --}}
        <h5 class="mt-4">🚚 Envío</h5>
        <div class="row mb-3">
            <div class="col-md-4">
                <label class="form-label">¿Requiere envío?</label>
                <select name="envio" id="envio" class="form-select" required>
                    <option value="0" {{ !$venta->envio ? 'selected' : '' }}>No</option>
                    <option value="1" {{ $venta->envio ? 'selected' : '' }}>Sí</option>
                </select>
            </div>
            <div class="col-md-4">
                <label for="costo_envio" class="form-label">Costo de envío ($)</label>
                <input type="number" name="costo_envio" id="costo_envio" class="form-control" step="0.01" min="0" value="{{ $venta->costo_envio ?? 0 }}">
            </div>
            <div class="col-md-4">
                <label for="direccion_envio" class="form-label">Dirección</label>
                <input type="text" name="direccion_envio" id="direccion_envio" class="form-control" value="{{ $venta->direccion_envio }}">
            </div>
        </div>

        {{-- Descuento total --}}
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="descuento_manual" class="form-label fw-semibold">Descuento total ($)</label>
                <input type="number" name="descuento_manual" id="descuento_manual" class="form-control" step="0.01" min="0" value="{{ $venta->descuento_manual }}">
            </div>
            <div class="col-md-6">
                <label for="motivo_descuento" class="form-label fw-semibold">Motivo del descuento</label>
                <input type="text" name="motivo_descuento" id="motivo_descuento" class="form-control" value="{{ $venta->motivo_descuento }}">
            </div>
        </div>

        {{-- Estado --}}
        <div class="mb-3">
            <label for="estado" class="form-label fw-semibold">Estado de la venta</label>
            <select name="estado" id="estado" class="form-select" required>
                <option value="pendiente" {{ $venta->estado == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                <option value="pagada" {{ $venta->estado == 'pagada' ? 'selected' : '' }}>Pagada</option>
                <option value="cancelada" {{ $venta->estado == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
            </select>
        </div>

        {{-- Totales --}}
        <div class="mt-4 p-3 border rounded bg-light">
            <p class="mb-1"><strong>Subtotal:</strong> $<span id="subtotal">{{ number_format($venta->subtotal, 0, ',', '.') }}</span></p>
            <p class="mb-1"><strong>Descuento total:</strong> $<span id="descuento">{{ number_format($venta->descuento_manual, 0, ',', '.') }}</span></p>
            <p class="mb-1"><strong>Costo de envío:</strong> $<span id="envio_total">{{ number_format($venta->costo_envio, 0, ',', '.') }}</span></p>
            <h4><strong>Total:</strong> $<span id="total">{{ number_format($venta->total, 0, ',', '.') }}</span></h4>
        </div>

        {{-- Botón actualizar --}}
        <button type="submit" class="btn btn-primary mt-3">💾 Actualizar Venta</button>
    </form>
</div>

<script>
const container = document.getElementById('productos-container');

// Recalculate row subtotal and grand total
function recalcular() {
    let subtotal = 0;
    container.querySelectorAll('.producto-row').forEach(row => {
        const precio   = parseFloat(row.querySelector('.precio-input').value)   || 0;
        const cantidad = parseFloat(row.querySelector('.cantidad-input').value)  || 0;
        const sub      = precio * cantidad;
        row.querySelector('.subtotal-input').value = sub.toLocaleString('es-CO');
        subtotal += sub;
    });

    const descuentoTotal = parseFloat(document.getElementById('descuento_manual').value || 0);
    const costoEnvio     = parseFloat(document.getElementById('costo_envio').value      || 0);
    const total          = subtotal - descuentoTotal + costoEnvio;

    document.getElementById('subtotal').textContent    = subtotal.toLocaleString('es-CO');
    document.getElementById('descuento').textContent   = descuentoTotal.toLocaleString('es-CO');
    document.getElementById('envio_total').textContent = costoEnvio.toLocaleString('es-CO');
    document.getElementById('total').textContent       = total.toLocaleString('es-CO');
}

container.addEventListener('input', recalcular);
document.getElementById('descuento_manual').addEventListener('input', recalcular);
document.getElementById('costo_envio').addEventListener('input', recalcular);

document.addEventListener('click', e => {
    if (e.target.classList.contains('btn-remove')) {
        const rows = container.querySelectorAll('.producto-row');
        if (rows.length > 1) { e.target.closest('.producto-row').remove(); recalcular(); }
    }
});

document.getElementById('add-producto').addEventListener('click', () => {
    const firstRow = container.querySelector('.producto-row');
    const newRow   = firstRow.cloneNode(true);
    // Clear variante hidden to 0 and producto hidden to first product
    newRow.querySelector('input[name="variantes[]"]').value  = '0';
    newRow.querySelector('input[name="productos[]"]').value  = '{{ $productos->first()?->id ?? 0 }}';
    newRow.querySelector('.precio-input').value    = '';
    newRow.querySelector('.cantidad-input').value  = '1';
    newRow.querySelector('.subtotal-input').value  = '';
    const label = newRow.querySelector('small');
    if (label) label.textContent = 'Sin variante — ingresa precio manualmente';
    container.appendChild(newRow);
});

recalcular();
</script>
@endsection
