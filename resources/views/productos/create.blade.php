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

    <form action="{{ route('productos.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Nombre --}}
        <div class="mb-3">
            <label class="form-label">Nombre</label>
            <input type="text" name="nombre" class="form-control" value="{{ old('nombre') }}" required>
        </div>

        {{-- Descripción --}}
        <div class="mb-3">
            <label class="form-label">Descripción</label>
            <textarea name="descripcion" class="form-control">{{ old('descripcion') }}</textarea>
        </div>

        {{-- Precio --}}
        <div class="mb-3">
            <label class="form-label">Precio</label>
            <input type="number" step="0.01" name="precio" class="form-control" value="{{ old('precio') }}" required>
        </div>

        {{-- Stock --}}
        <div class="mb-3">
            <label class="form-label">Stock</label>
            <input type="number" name="stock" class="form-control" value="{{ old('stock') }}" required>
        </div>

        {{-- Categoría --}}
        <div class="mb-3">
            <label class="form-label">Categoría</label>
            <select name="categoria_id" class="form-select">
                <option value="">-- Selecciona una categoría --</option>
                @foreach($categorias as $categoria)
                    <option value="{{ $categoria->id }}"
                        {{ old('categoria_id') == $categoria->id ? 'selected' : '' }}>
                        {{ $categoria->nombre }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Imagen --}}
        <div class="mb-3">
            <label class="form-label">Imagen</label>
            <input type="file" name="imagen" class="form-control" accept="image/*">
        </div>

        {{-- Estado --}}
        <div class="mb-3">
            <label class="form-label">Estado</label>
            <select class="form-select" name="activo">
                <option value="1" {{ old('activo', 1) == 1 ? 'selected' : '' }}>Activo</option>
                <option value="0" {{ old('activo') == 0 ? 'selected' : '' }}>Inactivo</option>
            </select>
        </div>

            <div class="mb-3">
                <label class="form-label">Sabores</label>

                <div id="sabores-container">
                    <div class="d-flex gap-2 mb-2">
                        <select name="sabores[]" class="form-select">
                            @foreach($sabores as $sabor)
                                <option value="{{ $sabor->id }}">{{ $sabor->nombre }}</option>
                            @endforeach
                        </select>
                        <button type="button" class="btn btn-danger remove-sabor">X</button>
                    </div>
                </div>

                <button type="button" id="add-sabor" class="btn btn-primary btn-sm">
                    Agregar sabor
                </button>
            </div>

            <script>
            document.getElementById('add-sabor').addEventListener('click', function () {
                let container = document.getElementById('sabores-container');

                let row = document.createElement('div');
                row.classList.add('d-flex', 'gap-2', 'mb-2');

                row.innerHTML = `
                    <select name="sabores[]" class="form-select">
                        @foreach($sabores as $sabor)
                            <option value="{{ $sabor->id }}">{{ $sabor->nombre }}</option>
                        @endforeach
                    </select>
                    <button type="button" class="btn btn-danger remove-sabor">X</button>
                `;

                container.appendChild(row);

                row.querySelector('.remove-sabor').addEventListener('click', function () {
                    row.remove();
                });
            });

            document.querySelectorAll('.remove-sabor').forEach(btn => {
                btn.addEventListener('click', function () {
                    this.parentElement.remove();
                });
            });
            </script>


            {{-- EFECTOS --}}
            <div class="mb-3">
                <label class="form-label">Efectos</label>

                <div id="efectos-container">
                    <div class="d-flex gap-2 mb-2">
                        <select name="efectos[]" class="form-select">
                            @foreach($efectos as $efecto)
                                <option value="{{ $efecto->id }}">{{ $efecto->nombre }}</option>
                            @endforeach
                        </select>
                        <button type="button" class="btn btn-danger remove-efecto">X</button>
                    </div>
                </div>

                <button type="button" id="add-efecto" class="btn btn-primary btn-sm">
                    Agregar efecto
                </button>
            </div>

            <script>
            document.getElementById('add-efecto').addEventListener('click', function () {
                let container = document.getElementById('efectos-container');

                let row = document.createElement('div');
                row.classList.add('d-flex', 'gap-2', 'mb-2');

                row.innerHTML = `
                    <select name="efectos[]" class="form-select">
                        @foreach($efectos as $efecto)
                            <option value="{{ $efecto->id }}">{{ $efecto->nombre }}</option>
                        @endforeach
                    </select>
                    <button type="button" class="btn btn-danger remove-efecto">X</button>
                `;

                container.appendChild(row);

                row.querySelector('.remove-efecto').addEventListener('click', function () {
                    row.remove();
                });
            });

            document.querySelectorAll('.remove-efecto').forEach(btn => {
                btn.addEventListener('click', function () {
                    this.parentElement.remove();
                });
            });
            </script>


                {{-- COLORES --}}
                <div class="mb-3">
                    <label class="form-label">Colores</label>

                    <div id="colores-container">
                        <div class="d-flex gap-2 mb-2">
                            <select name="colores[]" class="form-select">
                                @foreach($colores as $color)
                                    <option value="{{ $color->id }}">{{ $color->nombre }}</option>
                                @endforeach
                            </select>
                            <button type="button" class="btn btn-danger remove-color">X</button>
                        </div>
                    </div>

                    <button type="button" id="add-color" class="btn btn-primary btn-sm">
                        Agregar color
                    </button>
                </div>

                <script>
                document.getElementById('add-color').addEventListener('click', function () {
                    let container = document.getElementById('colores-container');

                    let row = document.createElement('div');
                    row.classList.add('d-flex', 'gap-2', 'mb-2');

                    row.innerHTML = `
                        <select name="colores[]" class="form-select">
                            @foreach($colores as $color)
                                <option value="{{ $color->id }}">{{ $color->nombre }}</option>
                            @endforeach
                        </select>
                        <button type="button" class="btn btn-danger remove-color">X</button>
                    `;

                    container.appendChild(row);

                    row.querySelector('.remove-color').addEventListener('click', function () {
                        row.remove();
                    });
                });

                document.querySelectorAll('.remove-color').forEach(btn => {
                    btn.addEventListener('click', function () {
                        this.parentElement.remove();
                    });
                });
                </script>


        <button type="submit" class="btn btn-success">Guardar</button>
        <a href="{{ route('productos.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
