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

    .prod-card {
        border:1px solid var(--panel-border);
        border-radius:20px;
        background:
            radial-gradient(120% 120% at 0% 0%, rgba(124,108,240,.06), transparent 60%),
            var(--panel-bg);
        box-shadow:0 20px 50px -20px rgba(0,0,0,.6), 0 0 0 1px rgba(255,255,255,.02) inset;
        color: var(--text-primary);
        overflow:hidden;
    }
    .prod-card .card-body { padding: 2rem; }

    .prod-sec {
        font-size:.72rem;
        text-transform:uppercase;
        letter-spacing:.12em;
        color: var(--accent);
        font-weight:700;
        margin:0 0 1rem;
    }

    .prod-label {
        font-size:.72rem;
        text-transform:uppercase;
        letter-spacing:.08em;
        color: var(--text-muted);
        font-weight:600;
        margin-bottom:.25rem;
        display:block;
    }
    .prod-value { color: var(--text-primary); font-size:.95rem; }

    .prod-image-wrap {
        background: var(--panel-bg-alt);
        border-radius:16px;
        border:1px solid var(--panel-border);
        display:flex;
        align-items:center;
        justify-content:center;
        min-height:280px;
        padding:1rem;
    }

    .badge.bg-success { background:#1d7a4a !important; }
    .badge.bg-danger { background:#a3313f !important; }
    .badge.bg-secondary { background:#3a4050 !important; }

    .prod-card table {
        color: var(--text-primary);
        border-color: var(--panel-border) !important;
        margin-top:.75rem;
    }
    .prod-card table thead {
        background: var(--panel-bg-alt) !important;
    }
    .prod-card table thead th {
        color: var(--text-muted);
        font-size:.75rem;
        text-transform:uppercase;
        letter-spacing:.06em;
        font-weight:700;
        border-color: var(--panel-border) !important;
    }
    .prod-card table td, .prod-card table th {
        border-color: var(--panel-border) !important;
        background: transparent !important;
        vertical-align: middle;
    }
    .prod-card table tbody tr:hover td { background: var(--panel-bg-alt) !important; }

    .prod-card .text-muted { color:#5f6675 !important; }

    h1.h3, h3.card-title { font-weight:700; letter-spacing:-.02em; }

    .prod-actions .btn-warning {
        background: var(--accent);
        border:none;
        color:#fff;
        border-radius:10px;
        font-weight:600;
        padding:.55rem 1.5rem;
        box-shadow:0 8px 20px -8px var(--accent-soft);
    }
    .prod-actions .btn-warning:hover { background:#6a5ae0; color:#fff; }
    .prod-actions .btn-secondary {
        background: var(--panel-bg-alt);
        border:1px solid var(--panel-border);
        color: var(--text-muted);
        border-radius:10px;
        padding:.55rem 1.5rem;
    }
    .prod-actions .btn-secondary:hover {
        background: var(--panel-border);
        color: var(--text-primary);
    }
</style>

<div class="container py-4" style="max-width:960px">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h3 mb-0">📦 Detalle del producto</h1>
            <small class="text-muted">Información completa y presentaciones registradas.</small>
        </div>
        <a href="{{ route('productos.index') }}" class="btn btn-outline-secondary btn-sm">← Volver</a>
    </div>

    <div class="prod-card">
        <div class="card-body">

            <div class="row g-4">
                {{-- Imagen del producto --}}
                <div class="col-md-4">
                    <div class="prod-image-wrap">
                        @if($producto->imagen)
                            <img src="{{ asset('storage/' . $producto->imagen) }}"
                                 alt="{{ $producto->nombre }}"
                                 class="img-fluid rounded-3"
                                 style="max-height: 260px; object-fit: cover;">
                        @else
                            <span class="text-muted small">Sin imagen</span>
                        @endif
                    </div>
                </div>

                {{-- Detalles del producto --}}
                <div class="col-md-8">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <h3 class="card-title mb-0">{{ $producto->nombre }}</h3>
                        @if($producto->activo)
                            <span class="badge bg-success px-3 py-2">Activo</span>
                        @else
                            <span class="badge bg-danger px-3 py-2">Inactivo</span>
                        @endif
                    </div>

                    <div class="mb-3">
                        <span class="prod-label">Descripción</span>
                        <div class="prod-value">{{ $producto->descripcion ?: 'Sin descripción disponible.' }}</div>
                    </div>

                    <div class="row g-3">
                        <div class="col-sm-6">
                            <span class="prod-label">Stock</span>
                            <div class="prod-value">{{ $producto->stock }}</div>
                        </div>
                        <div class="col-sm-6">
                            <span class="prod-label">Categoría</span>
                            <div class="prod-value">{{ $producto->categoria ? $producto->categoria->nombre : 'Sin categoría' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="prod-sec divider mt-4 pt-3" style="border-top:1px solid var(--panel-border)">
                🏷️ Variantes / Presentaciones
            </div>

            @if($producto->variantes->count())
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Cant. por variante</th>
                                <th>Precio</th>
                                <th>Stock</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($producto->variantes as $v)
                                <tr>
                                    <td>{{ $v->nombre }}</td>
                                    <td>{{ $v->cantidad_por_variante }}</td>
                                    <td>${{ number_format($v->precio, 0, ',', '.') }}</td>
                                    <td>{{ $v->stock }}</td>
                                    <td>
                                        @if($v->activo)
                                            <span class="badge bg-success">Activo</span>
                                        @else
                                            <span class="badge bg-secondary">Inactivo</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-muted mb-0">Este producto no tiene variantes registradas.</p>
            @endif

        </div>
    </div>

    {{-- Botones de acción --}}
    <div class="prod-actions d-flex gap-2 mt-3">
        <a href="{{ route('productos.edit', $producto) }}" class="btn btn-warning">
            <i class="bi bi-pencil-square"></i> Editar
        </a>
        <a href="{{ route('productos.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left-circle"></i> Volver
        </a>
    </div>

</div>
@endsection