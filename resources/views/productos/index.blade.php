@extends('layouts.app')

@section('content')
<div class="container">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mt-3">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-3 mt-3">
        <h1 class="mb-0">Productos</h1>
        <a href="{{ route('productos.create') }}" class="btn btn-primary">➕ Nuevo Producto</a>
    </div>

    {{-- FILTROS --}}
    <form method="GET" action="{{ route('productos.index') }}" class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-body py-3">
            <div class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label class="form-label small mb-1">Buscar</label>
                    <input type="text" name="buscar" class="form-control form-control-sm"
                           placeholder="Nombre del producto…" value="{{ request('buscar') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label small mb-1">Categoría</label>
                    <select name="categoria_id" class="form-select form-select-sm">
                        <option value="">Todas</option>
                        @foreach($categorias as $cat)
                            <option value="{{ $cat->id }}" {{ request('categoria_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small mb-1">Estado</label>
                    <select name="activo" class="form-select form-select-sm">
                        <option value="">Todos</option>
                        <option value="1" {{ request('activo') === '1' ? 'selected' : '' }}>Activos</option>
                        <option value="0" {{ request('activo') === '0' ? 'selected' : '' }}>Inactivos</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2 align-items-end">
                    <div class="form-check mb-0 me-2">
                        <input class="form-check-input" type="checkbox" name="stock_bajo" id="stock_bajo"
                               {{ request()->has('stock_bajo') ? 'checked' : '' }}>
                        <label class="form-check-label small" for="stock_bajo">Stock bajo (≤10)</label>
                    </div>
                    <button type="submit" class="btn btn-sm btn-primary">Filtrar</button>
                    <a href="{{ route('productos.index') }}" class="btn btn-sm btn-outline-secondary">✕</a>
                </div>
            </div>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle text-center">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Imagen</th>
                    <th class="text-start">Nombre</th>
                    <th>Categoría</th>
                    <th>Variantes / Precios</th>
                    <th>Stock base</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($productos as $producto)
                <tr>
                    <td>{{ $producto->id }}</td>
                    <td>
                        @if($producto->imagen)
                            <img src="{{ asset('storage/' . $producto->imagen) }}"
                                 alt="{{ $producto->nombre }}"
                                 style="width:50px;height:50px;object-fit:cover;border-radius:6px">
                        @else
                            <span class="text-muted small">—</span>
                        @endif
                    </td>
                    <td class="text-start">
                        <strong>{{ $producto->nombre }}</strong>
                        @if($producto->descripcion)
                            <br><small class="text-muted">{{ Str::limit($producto->descripcion, 40) }}</small>
                        @endif
                    </td>
                    <td>{{ $producto->categoria->nombre ?? '—' }}</td>
                    <td class="text-start" style="max-width:180px">
                        @forelse($producto->variantes as $v)
                            <span class="badge bg-dark mb-1">{{ $v->nombre }} — ${{ number_format($v->precio, 0, ',', '.') }}
                                @if($v->stock <= 5) <span class="text-warning">⚠{{ $v->stock }}</span>
                                @else ({{ $v->stock }})
                                @endif
                            </span><br>
                        @empty
                            <span class="text-muted small">Sin variantes</span>
                        @endforelse
                    </td>
                    <td>
                        @if($producto->stock <= 10)
                            <span class="badge bg-warning text-dark">⚠ {{ $producto->stock }}</span>
                        @else
                            {{ $producto->stock }}
                        @endif
                    </td>
                    <td>
                        <span class="badge {{ $producto->activo ? 'bg-success' : 'bg-secondary' }}">
                            {{ $producto->activo ? 'Activo' : 'Inactivo' }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('productos.show', $producto) }}" class="btn btn-info btn-sm">Ver</a>
                        <a href="{{ route('inventarios.create', $producto) }}" class="btn btn-sm btn-outline-success" title="Agregar stock">+Stock</a>
                        <a href="{{ route('productos.edit', $producto) }}" class="btn btn-warning btn-sm">Editar</a>
                        <form action="{{ route('productos.destroy', $producto) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm"
                                    data-confirm="Se eliminará este producto permanentemente.">Eliminar</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-muted py-4">No hay productos.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($productos->hasPages())
        <div class="d-flex justify-content-between align-items-center mt-3">
            {{ $productos->links() }}
            <small class="text-muted">{{ $productos->total() }} producto(s)</small>
        </div>
    @endif
</div>
@endsection
