@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">➕ Crear Domicilio</h1>

    <form action="{{ route('domicilios.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label>Venta ID</label>
            <input type="number" name="venta_id" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Cliente ID</label>
            <input type="number" name="cliente_id" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Dirección</label>
            <input type="text" name="direccion" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Ciudad</label>
            <input type="text" name="ciudad" class="form-control">
        </div>

        <div class="mb-3">
            <label>Departamento</label>
            <input type="text" name="departamento" class="form-control">
        </div>

        <div class="mb-3">
            <label>País</label>
            <input type="text" name="pais" class="form-control">
        </div>

        <div class="mb-3">
            <label>Teléfono</label>
            <input type="text" name="telefono" class="form-control">
        </div>

        <div class="mb-3">
            <label>Costo envío</label>
            <input type="number" step="0.01" name="costo_envio" class="form-control">
        </div>

        <div class="mb-3">
            <label>Comentarios</label>
            <textarea name="comentarios" class="form-control"></textarea>
        </div>

        <button class="btn btn-primary">Guardar</button>
        <a href="{{ route('domicilios.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
