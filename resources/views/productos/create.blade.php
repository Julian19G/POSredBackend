@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Crear Producto</h1>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('productos.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" name="nombre" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea name="descripcion" class="form-control"></textarea>
        </div>

        <div class="mb-3">
            <label for="precio" class="form-label">Precio</label>
            <input type="number" step="0.01" name="precio" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="stock" class="form-label">Stock</label>
            <input type="number" name="stock" class="form-control" required>
        </div>

        <div class="mb-3"> 
            <label for= "categoria_id" class="form-label">Categoria</label>
            <select name="categoria_id" class="form-select">
                <option value=""> --Selecciona una categoria -- </option>
                @foreach($categorias as $categoria)
                <option value="{{$categoria->id }}" {{ old('categoria_id') == $categoria->id ? 'selected' : '' }}>
                    {{$categoria->nombre}}
                </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for = "imagen" class="form-label">Imagen </label>
            <input type="file" name="imagen" class="form-control" accept="imagen/*">
        </div>

<div class="mb-3">
    <label for="activo" class="form-label">Estado</label>
    <select class="form-select" name="activo" id="activo">
        <option value="1" selected>Activo</option>
        <option value="0">Inactivo</option>
    </select>
</div>




        <button type="submit" class="btn btn-success">Guardar</button>
        <a href="{{ route('productos.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
