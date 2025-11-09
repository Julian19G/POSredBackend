@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">🛒 Registrar Nueva Venta</h1>

    {{-- Mensajes de error --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('ventas.store') }}" method="POST">
        @csrf

        {{-- Selección de Cliente --}}
        <div class="mb-3">
            <label for="cliente_id" class="form-label fw-semibold">Cliente</label>
            <select name="cliente_id" id="cliente_id" class="form-select" required>
                <option value="">Seleccione un cliente</option>
                @foreach($clientes as $cliente)
                    <option value="{{ $cliente->id }}">{{ $cliente->nombre }} - {{ $cliente->telefono }}</option>
                @endforeach
            </select>
        </div>

        {{-- Productos --}}
        <h5 class="mt-4">Productos</h5>
        <div id="productos-container">
            <div class="producto-row row mb-3 g-2 align-items-end">
                <div class="col-md-5">
                    <label class="form-label">Producto</label>
                    <select name="productos[]" class="form-select" required>
                        <option value="">Seleccione un producto</option>
                        @foreach($productos as $producto)
                            <option value="{{ $producto->id }}">
                                {{ $producto->nombre }} - ${{ number_format($producto->precio, 2, ',', '.') }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Cantidad</label>
                    <input type="number" name="cantidades[]" class="form-control" min="1" value="1" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Descuento manual ($)</label>
                    <input type="number" name="descuentos[]" class="form-control" min="0" value="0">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="button" class="btn btn-danger btn-remove">✖</button>
                </div>
            </div>
        </div>

        <button type="button" id="add-producto" class="btn btn-secondary mb-3">➕ Agregar otro producto</button>

        {{-- Dirección de envío --}}
        <div class="mb-3">
            <label for="direccion_envio" class="form-label fw-semibold">Dirección de envío</label>
            <input type="text" name="direccion_envio" id="direccion_envio" class="form-control" placeholder="Opcional">
        </div>

        {{-- Estado --}}
        <div class="mb-3">
            <label for="estado" class="form-label fw-semibold">Estado de la venta</label>
            <select name="estado" id="estado" class="form-select" required>
                <option value="pendiente" selected>Pendiente</option>
                <option value="pagada">Pagada</option>
                <option value="cancelada">Cancelada</option>
            </select>
        </div>

        {{-- Motivo de descuento --}}
        <div class="mb-3">
            <label for="motivo_descuento" class="form-label fw-semibold">Motivo de descuento</label>
            <input type="text" name="motivo_descuento" id="motivo_descuento" class="form-control" placeholder="Opcional, por ejemplo: cliente frecuente">
        </div>

        {{-- Botón Registrar --}}
        <button type="submit" class="btn btn-primary">
            💾 Registrar Venta
        </button>
    </form>
</div>

{{-- Script para agregar/quitar productos --}}
<script>
    const addBtn = document.getElementById('add-producto');
    const container = document.getElementById('productos-container');

    addBtn.addEventListener('click', function () {
        const firstRow = container.querySelector('.producto-row');
        const newRow = firstRow.cloneNode(true);

        newRow.querySelectorAll('select, input').forEach(el => {
            if (el.tagName === 'SELECT') el.selectedIndex = 0;
            else el.value = el.getAttribute('min') || '';
        });

        container.appendChild(newRow);
    });

    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('btn-remove')) {
            const rows = document.querySelectorAll('.producto-row');
            if (rows.length > 1) e.target.closest('.producto-row').remove();
        }
    });
</script>
@endsection
