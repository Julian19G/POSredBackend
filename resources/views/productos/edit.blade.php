@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Editar Producto</h1>

    {{-- Mensajes de error --}}
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Formulario --}}
    <form action="{{ route('productos.update', $producto) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- Nombre --}}
        <div class="mb-3">
            <label for="nombre" class="form-label fw-semibold">Nombre</label>
            <input type="text" name="nombre" id="nombre" class="form-control" 
                   value="{{ old('nombre', $producto->nombre) }}" required>
        </div>

        {{-- Descripción --}}
        <div class="mb-3">
            <label for="descripcion" class="form-label fw-semibold">Descripción</label>
            <textarea name="descripcion" id="descripcion" class="form-control" rows="3">{{ old('descripcion', $producto->descripcion) }}</textarea>
        </div>

        {{-- Precio y Stock --}}
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="precio" class="form-label fw-semibold">Precio</label>
                <input type="number" step="0.01" name="precio" id="precio" class="form-control" 
                       value="{{ old('precio', $producto->precio) }}" required>
            </div>

            <div class="col-md-6 mb-3">
                <label for="stock" class="form-label fw-semibold">Stock</label>
                <input type="number" name="stock" id="stock" class="form-control" 
                       value="{{ old('stock', $producto->stock) }}" required>
            </div>
        </div>

        {{-- Categoría --}}
        <div class="mb-3">
            <label for="categoria_id" class="form-label fw-semibold">Categoría</label>
            <select name="categoria_id" id="categoria_id" class="form-select">
                <option value="">-- Seleccionar categoría --</option>
                @foreach($categorias as $categoria)
                    <option value="{{ $categoria->id }}" 
                        {{ old('categoria_id', $producto->categoria_id) == $categoria->id ? 'selected' : '' }}>
                        {{ $categoria->nombre }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Imagen --}}
        <div class="mb-3">
            <label for="imagen" class="form-label fw-semibold">Imagen</label>
            @if($producto->imagen)
                <div class="mb-2">
                    <img src="{{ asset('storage/' . $producto->imagen) }}" alt="Imagen del producto" 
                         class="img-thumbnail shadow-sm" style="max-width: 150px;">
                </div>
            @endif
            <input type="file" name="imagen" id="imagen" class="form-control">
        </div>

        {{-- Estado (Select en lugar de switch) --}}
        <div class="mb-4">
            <label for="activo" class="form-label fw-semibold">Estado</label>
            <select class="form-select" name="activo" id="activo">
                <option value="1" {{ old('activo', $producto->activo) == 1 ? 'selected' : '' }}>Activo</option>
                <option value="0" {{ old('activo', $producto->activo) == 0 ? 'selected' : '' }}>Inactivo</option>
            </select>
        </div>

        {{-- Botones --}}
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-success px-4">Actualizar</button>
            <a href="{{ route('productos.index') }}" class="btn btn-secondary px-4">Cancelar</a>
        </div>
    </form>
</div>
@endsection
