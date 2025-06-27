@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Editar Venta #{{ $venta->id }}</h1>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('ventas.update', $venta->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div id="productos-container">
            @foreach($venta->detalles as $detalle)
                <div class="producto-row row mb-3">
                    <div class="col-md-6">
                        <label>Producto</label>
                        <select name="productos[]" class="form-select" required>
                            @foreach($productos as $producto)
                                <option value="{{ $producto->id }}" {{ $producto->id == $detalle->producto_id ? 'selected' : '' }}>
                                    {{ $producto->nombre }} - ${{ $producto->precio }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label>Cantidad</label>
                        <input type="number" name="cantidades[]" class="form-control" value="{{ $detalle->cantidad }}" min="1" required>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="button" class="btn btn-danger btn-remove">X</button>
                    </div>
                </div>
            @endforeach
        </div>

        <button type="button" id="add-producto" class="btn btn-secondary mb-3">Agregar otro producto</button>
        <button type="submit" class="btn btn-primary">Actualizar Venta</button>
    </form>
</div>
<script>
    document.getElementById('add-producto').addEventListener('click', function () {
        const container = document.getElementById('productos-container');
        const firstRow = container.querySelector('.producto-row');
        const newRow = firstRow.cloneNode(true);
        newRow.querySelector('select').selectedIndex = 0;
        newRow.querySelector('input').value = '';
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
