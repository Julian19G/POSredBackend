@extends('layouts.app')

@section('content')
<style>
    :root {
        --panel-bg: #fffdf9;
        --panel-bg-alt: #f4efe5;
        --panel-border: var(--pg-line);
        --text-primary: var(--pg-ink);
        --text-muted: var(--pg-muted);
        --accent: var(--pg-gold);
        --accent-soft: rgba(205,168,106,.18);
    }

    body { background:#faf8f3; }

    h1 { font-weight:700; letter-spacing:-.02em; color: var(--pg-ink); }

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
        background: #fffdf9 !important;
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
    .legacy-products-table-wrap {
        border:1px solid var(--panel-border);
        border-radius:16px;
        overflow:hidden;
        box-shadow:0 20px 50px -25px rgba(0,0,0,.6);
    }
    .legacy-products-table {
        color: var(--text-primary) !important;
        background: #fffdf9 !important;
        margin-bottom:0 !important;
    }
    .legacy-products-table thead {
        background: #f4efe5 !important;
    }
    .legacy-products-table thead th {
        color: var(--text-muted) !important;
        font-size:.72rem;
        text-transform:uppercase;
        letter-spacing:.08em;
        font-weight:700;
        border-color: var(--panel-border) !important;
        padding:.9rem .75rem;
    }
    .legacy-products-table tbody td {
        border-color: var(--panel-border) !important;
        background: #fffdf9 !important;
        vertical-align: middle;
        padding:.75rem;
    }
    .legacy-products-table tbody tr {
        transition: background .12s ease;
    }
    .legacy-products-table.table-hover tbody tr:hover td {
        background: #f7f3ea !important;
    }
    .legacy-products-table small.text-muted { color: var(--pg-muted) !important; }

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

<div class="catalog-page">
<div class="catalog-shell">

    @if(session('success'))
        <div class="rounded-xl border border-emerald-500/30 bg-emerald-500/10 p-4 text-emerald-300">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="rounded-xl border border-amber-500/30 bg-amber-500/10 p-4 text-amber-300">
            {{ session('error') }}
        </div>
    @endif

    <div class="catalog-header">
        <div>
            <h1 class="catalog-title">📦 Productos</h1>
            <small class="catalog-subtitle">Gestiona el catálogo, stock y presentaciones.</small>
        </div>
        @if($esAdmin)
            <a href="{{ route('productos.create') }}" class="catalog-button catalog-cta">➕ Nuevo producto</a>
        @endif
    </div>

    <form method="GET" action="{{ route('productos.index') }}" class="catalog-layout">
        <aside class="catalog-filter" aria-label="Filtros de productos">
            <div class="catalog-filter-heading">
                <span>Filtros</span>
                <span class="catalog-filter-count">{{ $productos->total() }}</span>
            </div>
            <div class="catalog-filter-section">
                <label class="catalog-label" for="categoria_id">Categoría</label>
                <select name="categoria_id" id="categoria_id" class="catalog-input">
                    <option value="">Todas las categorías</option>
                    @foreach($categorias as $cat)
                        <option value="{{ $cat->id }}" {{ request('categoria_id') == $cat->id ? 'selected' : '' }}>{{ $cat->nombre }}</option>
                    @endforeach
                </select>
            </div>
            @if($esAdmin)
            <div class="catalog-filter-section">
                <label class="catalog-label" for="activo">Estado</label>
                <select name="activo" id="activo" class="catalog-input">
                    <option value="">Todos</option>
                    <option value="1" {{ request('activo') === '1' ? 'selected' : '' }}>Activos</option>
                    <option value="0" {{ request('activo') === '0' ? 'selected' : '' }}>Inactivos</option>
                </select>
            </div>
            <div class="catalog-filter-section">
                <label class="catalog-check-label">
                    <input class="catalog-check" type="checkbox" name="stock_bajo" id="stock_bajo" {{ request()->has('stock_bajo') ? 'checked' : '' }}>
                    <span>Stock bajo <small>10 unidades o menos</small></span>
                </label>
            </div>
            @endif
            <div class="catalog-filter-actions">
                <button type="submit" class="catalog-button">Aplicar filtros</button>
                <a href="{{ route('productos.index') }}" class="catalog-button-muted">Limpiar</a>
            </div>
        </aside>

        <section class="catalog-content">
            <div class="catalog-search">
                <svg aria-hidden="true" viewBox="0 0 24 24" fill="none"><circle cx="11" cy="11" r="6.5" stroke="currentColor" stroke-width="1.8"/><path d="m16 16 4.5 4.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
                <input type="text" name="buscar" class="catalog-input" placeholder="Buscar producto..." value="{{ request('buscar') }}" aria-label="Buscar producto">
                <button type="submit" class="catalog-search-button">Buscar</button>
            </div>

            <div class="catalog-table-wrap">
        <table class="catalog-table vendedor-table product-catalog-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Imagen</th>
                    <th class="catalog-cell-name">Nombre</th>
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
                    <td data-label="ID">{{ $producto->id }}</td>
                    <td class="catalog-image-cell" data-label="Imagen">
                        @if($producto->imagen)
                            <img src="{{ asset('storage/' . $producto->imagen) }}"
                                 alt="{{ $producto->nombre }}"
                                 class="catalog-image">
                        @else
                            <span class="catalog-muted">—</span>
                        @endif
                    </td>
                    <td class="catalog-cell-name" data-label="Nombre">
                        <strong>{{ $producto->nombre }}</strong>
                        @if($producto->descripcion)
                            <br><small class="catalog-muted">{{ Str::limit($producto->descripcion, 40) }}</small>
                        @endif
                    </td>
                    <td data-label="Categoría">{{ $producto->categoria->nombre ?? '—' }}</td>
                    <td class="catalog-variants" data-label="Variantes / precios">
                        @forelse($producto->variantes as $v)
                            <span class="catalog-badge mb-1">{{ $v->nombre }} — ${{ number_format($v->precio, 0, ',', '.') }}
                                @if($v->stock <= 5) <span class="catalog-stock-warning">⚠{{ $v->stock }}</span>
                                @else ({{ $v->stock }})
                                @endif
                            </span><br>
                        @empty
                            <span class="catalog-muted">Sin variantes</span>
                        @endforelse
                    </td>
                    <td data-label="Stock base">
                        @if($producto->stock <= 10)
                            <span class="catalog-low-stock">⚠ {{ $producto->stock }}</span>
                        @else
                            {{ $producto->stock }}
                        @endif
                    </td>
                    <td data-label="Estado">
                        <span class="catalog-status {{ $producto->activo ? 'catalog-status-active' : 'catalog-status-inactive' }}">
                            {{ $producto->activo ? 'Activo' : 'Inactivo' }}
                        </span>
                    </td>
                    <td class="catalog-actions-cell"><div class="catalog-actions">
                        <a href="{{ route('productos.show', $producto) }}" class="catalog-action catalog-action-info" title="Ver producto" aria-label="Ver producto">
                            <svg aria-hidden="true" viewBox="0 0 24 24" fill="none"><path d="M2.5 12s3.5-6 9.5-6 9.5 6 9.5 6-3.5 6-9.5 6-9.5-6-9.5-6Z" stroke="currentColor" stroke-width="1.8"/><circle cx="12" cy="12" r="2.5" stroke="currentColor" stroke-width="1.8"/></svg>
                        </a>
                        @if($esAdmin)
                            <a href="{{ route('inventarios.create', $producto) }}" class="catalog-action catalog-action-stock" title="Agregar stock" aria-label="Agregar stock">
                                <svg aria-hidden="true" viewBox="0 0 24 24" fill="none"><path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
                            </a>
                            <a href="{{ route('productos.edit', $producto) }}" class="catalog-action catalog-action-edit" title="Editar producto" aria-label="Editar producto">
                                <svg aria-hidden="true" viewBox="0 0 24 24" fill="none"><path d="m4 16.5-.8 3.3 3.3-.8L18.8 6.7a2.1 2.1 0 0 0-3-3L4 16.5Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/></svg>
                            </a>
                            <form action="{{ route('productos.destroy', $producto) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="submit" class="catalog-action catalog-action-delete" title="Eliminar producto" aria-label="Eliminar producto"
                                        data-confirm="Se eliminará este producto permanentemente.">
                                    <svg aria-hidden="true" viewBox="0 0 24 24" fill="none"><path d="M5 7h14M10 11v6M14 11v6M8 7l.7-2h6.6l.7 2m-9 0 .7 13h8.6l.7-13" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </button>
                            </form></div>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="catalog-empty">No hay productos.</td>
                </tr>
                @endforelse
            </tbody>
                </table>
            </div>

            @if($productos->hasPages())
                <div class="catalog-footer">
                    {{ $productos->links() }}
                    <small>{{ $productos->total() }} producto(s)</small>
                </div>
            @endif
        </section>
    </form>
</div></div>
@endsection