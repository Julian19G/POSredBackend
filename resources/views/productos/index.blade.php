@extends('layouts.app')

@section('content')
<style>
    :root {
        --panel-bg: #14161c;
        --panel-bg-alt: #1b1e26;
        --panel-border: #262a35;
        --text-primary: #e8eaf0;
        --text-muted: #8b92a3;
        --accent: #7c6cf0;
        --accent-soft: rgba(124,108,240,.15);
    }

    body { background:#0b0c10; }

    h1 { font-weight:700; letter-spacing:-.02em; color: var(--text-primary); }

    /* Alertas */
    .alert-success {
        background: rgba(29,122,74,.12);
        border:1px solid rgba(29,122,74,.35);
        color:#8fe0b4;
        border-radius:12px;
    }
    .alert-warning {
        background: rgba(163,49,63,.12);
        border:1px solid rgba(163,49,63,.35);
        color:#f3a4ac;
        border-radius:12px;
    }
    .alert .btn-close { filter: invert(1) grayscale(1) brightness(1.4); }

    /* Card de filtros */
    .filtros-card {
        border:1px solid var(--panel-border) !important;
        border-radius:16px !important;
        background: var(--panel-bg) !important;
        box-shadow:0 10px 30px -15px rgba(0,0,0,.5) !important;
    }
    .filtros-card .form-label {
        color: var(--text-muted);
        font-weight:600;
        letter-spacing:.03em;
    }
    .filtros-card .form-control,
    .filtros-card .form-select {
        background: var(--panel-bg-alt);
        color: var(--text-primary);
        border:1px solid var(--panel-border);
        border-radius:9px;
    }
    .filtros-card .form-control::placeholder { color:#4b5160; }
    .filtros-card .form-control:focus,
    .filtros-card .form-select:focus {
        background: var(--panel-bg-alt);
        color: var(--text-primary);
        border-color: var(--accent);
        box-shadow:0 0 0 .2rem var(--accent-soft);
    }
    .filtros-card .form-check-input {
        background-color: var(--panel-bg-alt);
        border-color: var(--panel-border);
    }
    .filtros-card .form-check-input:checked {
        background-color: var(--accent);
        border-color: var(--accent);
    }
    .filtros-card .form-check-label { color: var(--text-muted); }

    /* Botones generales */
    .btn-primary {
        background: var(--accent);
        border:none;
        border-radius:9px;
        font-weight:600;
    }
    .btn-primary:hover { background:#6a5ae0; }
    .btn-outline-secondary {
        border-color: var(--panel-border);
        color: var(--text-muted);
        border-radius:9px;
    }
    .btn-outline-secondary:hover {
        background: var(--panel-bg-alt);
        color: var(--text-primary);
        border-color: var(--panel-border);
    }

    /* Tabla */
    .table-responsive {
        border:1px solid var(--panel-border);
        border-radius:16px;
        overflow:hidden;
        box-shadow:0 20px 50px -25px rgba(0,0,0,.6);
    }
    table {
        color: var(--text-primary) !important;
        background: var(--panel-bg) !important;
        margin-bottom:0 !important;
    }
    table thead {
        background: var(--panel-bg-alt) !important;
    }
    table thead th {
        color: var(--text-muted) !important;
        font-size:.72rem;
        text-transform:uppercase;
        letter-spacing:.08em;
        font-weight:700;
        border-color: var(--panel-border) !important;
        padding:.9rem .75rem;
    }
    table tbody td {
        border-color: var(--panel-border) !important;
        background: transparent !important;
        vertical-align: middle;
        padding:.75rem;
    }
    table tbody tr {
        transition: background .12s ease;
    }
    table.table-hover tbody tr:hover td {
        background: var(--panel-bg-alt) !important;
    }
    table small.text-muted { color:#5f6675 !important; }

    /* Badges */
    .badge.bg-dark { background: var(--panel-bg-alt) !important; border:1px solid var(--panel-border); font-weight:500; }
    .badge.bg-success { background:#1d7a4a !important; }
    .badge.bg-secondary { background:#3a4050 !important; }
    .badge.bg-warning { background:#c9932e !important; color:#1a1200 !important; }
    .badge.bg-info { background:#2f7fb8 !important; }

    /* Botones de acción en tabla */
    .btn-info { background:#2f7fb8; border:none; color:#fff; border-radius:7px; }
    .btn-info:hover { background:#3a91cf; color:#fff; }
    .btn-warning { background:var(--accent); border:none; color:#fff; border-radius:7px; }
    .btn-warning:hover { background:#6a5ae0; color:#fff; }
    .btn-outline-success { border-color:#1d7a4a; color:#5fd08c; border-radius:7px; }
    .btn-outline-success:hover { background:#1d7a4a; color:#fff; }
    .btn-danger { background:#a3313f; border:none; border-radius:7px; }
    .btn-danger:hover { background:#c23a4c; }
    td .btn { margin:0 2px; }

    /* Paginación */
    .pagination .page-link {
        background: var(--panel-bg-alt);
        border:1px solid var(--panel-border);
        color: var(--text-primary);
    }
    .pagination .page-link:hover {
        background: var(--panel-border);
        color: var(--text-primary);
    }
    .pagination .page-item.active .page-link {
        background: var(--accent);
        border-color: var(--accent);
        color:#fff;
    }
    .pagination .page-item.disabled .page-link {
        background: var(--panel-bg);
        border-color: var(--panel-border);
        color:#4b5160;
    }
</style>

<div class="container py-4">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mt-3">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-warning alert-dismissible fade show mt-3">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-3 mt-3">
        <div>
            <h1 class="h3 mb-0">📦 Productos</h1>
            <small class="text-muted">Gestiona el catálogo, stock y presentaciones.</small>
        </div>
        @if($esAdmin)
            <a href="{{ route('productos.create') }}" class="btn btn-primary">➕ Nuevo producto</a>
        @endif
    </div>

    {{-- FILTROS --}}
    <form method="GET" action="{{ route('productos.index') }}" class="card filtros-card mb-4">
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
                @if($esAdmin)
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
                @else
                <div class="col-md-5 d-flex gap-2 align-items-end">
                    <button type="submit" class="btn btn-sm btn-primary">Filtrar</button>
                    <a href="{{ route('productos.index') }}" class="btn btn-sm btn-outline-secondary">✕</a>
                </div>
                @endif
            </div>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-hover align-middle text-center mb-0">
            <thead>
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
                                 style="width:50px;height:50px;object-fit:cover;border-radius:8px;border:1px solid var(--panel-border)">
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
                            <span class="badge bg-warning">⚠ {{ $producto->stock }}</span>
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
                        @if($esAdmin)
                            <a href="{{ route('inventarios.create', $producto) }}" class="btn btn-sm btn-outline-success" title="Agregar stock">+Stock</a>
                            <a href="{{ route('productos.edit', $producto) }}" class="btn btn-warning btn-sm">Editar</a>
                            <form action="{{ route('productos.destroy', $producto) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"
                                        data-confirm="Se eliminará este producto permanentemente.">Eliminar</button>
                            </form>
                        @endif
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