<!DOCTYPE html>

<style>
    :root {
        --panel-bg: #14161c;
        --panel-bg-alt: #1b1e26;
        --panel-border: #262a35;
        --text-primary: #e8eaf0;
        --text-muted: #8b92a3;
        --accent: #7c6cf0;
        --accent-hover: #6a5ae0;
        --accent-soft: rgba(124,108,240,.15);
        --page-bg: #0b0c10;
    }

    html { transition: background-color .2s ease; }
    body { transition: background-color .2s ease, color .2s ease; }

    /* ── Modo oscuro: paleta propia sobre las variables de Bootstrap ── */
    [data-bs-theme="dark"] {
        --bs-body-bg: var(--page-bg);
        --bs-body-color: var(--text-primary);
        --bs-border-color: var(--panel-border);
        --bs-secondary-bg: var(--panel-bg);
        --bs-tertiary-bg: var(--panel-bg-alt);
    }
    [data-bs-theme="dark"] body {
        background:
            radial-gradient(1200px 600px at 15% -10%, rgba(124,108,240,.07), transparent 60%),
            radial-gradient(900px 500px at 100% 0%, rgba(124,108,240,.04), transparent 55%),
            var(--page-bg);
        background-attachment: fixed;
    }

    [data-bs-theme="dark"] .bg-white { background-color: var(--panel-bg) !important; }
    [data-bs-theme="dark"] .bg-light { background-color: var(--panel-bg-alt) !important; }

    /* ── Card premium reutilizable (mismo lenguaje del prod-card) ──────── */
    [data-bs-theme="dark"] .card,
    .app-card {
        border:1px solid var(--panel-border);
        border-radius:18px;
        background:
            radial-gradient(120% 120% at 0% 0%, rgba(124,108,240,.06), transparent 60%),
            var(--panel-bg);
        box-shadow:0 20px 50px -25px rgba(0,0,0,.6), 0 0 0 1px rgba(255,255,255,.02) inset;
        color: var(--text-primary);
    }
    .app-card .card-body { padding: 1.75rem; }

    /* Label de sección tipo "prod-sec", disponible en cualquier vista */
    .section-label {
        font-size:.72rem;
        text-transform:uppercase;
        letter-spacing:.12em;
        color: var(--accent);
        font-weight:700;
        margin:0 0 1.1rem;
        display:flex; align-items:center; gap:.5rem;
    }
    .section-label.divider {
        margin-top:2rem;
        padding-top:1.4rem;
        border-top:1px solid var(--panel-border);
    }

    h1, h2, h3, h4, h5 { letter-spacing:-.02em; }
    h1.h3, h1.mb-0 { font-weight:700; }

    /* ── Inputs / selects ────────────────────────────────────────────── */
    [data-bs-theme="dark"] .form-control,
    [data-bs-theme="dark"] .form-select {
        background-color: var(--panel-bg-alt);
        border:1px solid var(--panel-border);
        border-radius:10px;
        color: var(--text-primary);
        padding:.6rem .85rem;
        transition: border-color .15s ease, box-shadow .15s ease;
    }
    [data-bs-theme="dark"] .form-control:hover,
    [data-bs-theme="dark"] .form-select:hover { border-color:#38404f; }
    [data-bs-theme="dark"] .form-control:focus,
    [data-bs-theme="dark"] .form-select:focus {
        background-color: var(--panel-bg-alt);
        color: var(--text-primary);
        border-color: var(--accent);
        box-shadow: 0 0 0 .2rem var(--accent-soft);
    }
    [data-bs-theme="dark"] .form-control::placeholder { color: #4b5160; }
    [data-bs-theme="dark"] .form-label { color: var(--text-muted); font-weight:600; font-size:.85rem; }

    /* ── Botones ─────────────────────────────────────────────────────── */
    [data-bs-theme="dark"] .btn { border-radius:10px; font-weight:600; transition: all .15s ease; }
    [data-bs-theme="dark"] .btn-primary {
        background-color: var(--accent);
        border-color: var(--accent);
        box-shadow:0 8px 20px -10px var(--accent-soft);
    }
    [data-bs-theme="dark"] .btn-primary:hover {
        background-color: var(--accent-hover);
        border-color: var(--accent-hover);
        box-shadow:0 10px 24px -8px var(--accent-soft);
        transform: translateY(-1px);
    }
    [data-bs-theme="dark"] .btn-success {
        box-shadow:0 8px 20px -10px rgba(29,122,74,.5);
    }
    [data-bs-theme="dark"] .btn-success:hover { transform: translateY(-1px); }
    [data-bs-theme="dark"] .btn-outline-secondary {
        color: var(--text-muted);
        border-color: var(--panel-border);
    }
    [data-bs-theme="dark"] .btn-outline-secondary:hover {
        background-color: var(--panel-bg-alt);
        color: var(--text-primary);
        border-color:#38404f;
    }

    /* ── Tablas ──────────────────────────────────────────────────────── */
    [data-bs-theme="dark"] .table { color: var(--bs-body-color); border-color: var(--bs-border-color); }
    [data-bs-theme="dark"] .table > :not(caption) > * > * { border-color: var(--bs-border-color); }
    [data-bs-theme="dark"] .table-dark {
        --bs-table-bg: var(--panel-bg-alt);
        --bs-table-color: var(--text-muted);
    }
    [data-bs-theme="dark"] .table thead th {
        font-size:.72rem;
        text-transform:uppercase;
        letter-spacing:.08em;
        font-weight:700;
        color: var(--text-muted);
    }
    [data-bs-theme="dark"] .table-light,
    [data-bs-theme="dark"] thead.table-light th,
    [data-bs-theme="dark"] .table-light > tr > th,
    [data-bs-theme="dark"] .table-light > tr > td {
        --bs-table-bg: var(--panel-bg-alt);
        --bs-table-color: var(--text-primary);
        background-color: var(--panel-bg-alt) !important;
        color: var(--text-primary) !important;
    }
    [data-bs-theme="dark"] .table-hover > tbody > tr:hover > * {
        --bs-table-accent-bg: var(--panel-bg-alt);
        color: var(--text-primary);
    }
    [data-bs-theme="dark"] .table-responsive {
        border-radius:16px;
        overflow:hidden;
        box-shadow:0 20px 50px -28px rgba(0,0,0,.6);
    }

    [data-bs-theme="dark"] .card .text-dark:not(.badge) { color: var(--bs-body-color) !important; }

    /* ── Dropdowns ───────────────────────────────────────────────────── */
    [data-bs-theme="dark"] .dropdown-menu {
        background-color: var(--panel-bg);
        border:1px solid var(--panel-border);
        border-radius:14px;
        box-shadow:0 20px 50px -20px rgba(0,0,0,.65);
        padding:.5rem;
    }
    [data-bs-theme="dark"] .dropdown-item {
        color: var(--text-primary);
        border-radius:8px;
        padding:.5rem .75rem;
    }
    [data-bs-theme="dark"] .dropdown-item:hover,
    [data-bs-theme="dark"] .dropdown-item:focus {
        background-color: var(--panel-bg-alt);
        color: var(--text-primary);
    }
    [data-bs-theme="dark"] .dropdown-header { color: var(--text-muted); font-size:.7rem; text-transform:uppercase; letter-spacing:.08em; }
    [data-bs-theme="dark"] .dropdown-divider { border-color: var(--panel-border); }

    /* ── Paginación ──────────────────────────────────────────────────── */
    [data-bs-theme="dark"] .pagination .page-link {
        background-color: var(--panel-bg-alt);
        border-color: var(--panel-border);
        color: var(--text-primary);
        border-radius:8px;
        margin:0 2px;
    }
    [data-bs-theme="dark"] .pagination .page-item.active .page-link {
        background-color: var(--accent);
        border-color: var(--accent);
    }
    [data-bs-theme="dark"] .pagination .page-item.disabled .page-link {
        background-color: var(--panel-bg);
        color: #4b5160;
    }

    /* ── Select2 ─────────────────────────────────────────────────────── */
    [data-bs-theme="dark"] .select2-container--default .select2-selection--single,
    [data-bs-theme="dark"] .select2-container--default .select2-selection--multiple {
        background-color: var(--panel-bg-alt);
        border-color: var(--panel-border);
        color: var(--text-primary);
        border-radius:10px;
    }
    [data-bs-theme="dark"] .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: var(--text-primary);
    }
    [data-bs-theme="dark"] .select2-dropdown {
        background-color: var(--panel-bg-alt);
        border-color: var(--panel-border);
        border-radius:10px;
    }
    [data-bs-theme="dark"] .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: var(--accent);
    }
    [data-bs-theme="dark"] .select2-search__field {
        background-color: var(--panel-bg);
        color: var(--text-primary);
    }

    /* ── SweetAlert2 ─────────────────────────────────────────────────── */
    [data-bs-theme="dark"] .swal2-popup {
        background: var(--panel-bg) !important;
        color: var(--text-primary) !important;
        border-radius:18px !important;
        border:1px solid var(--panel-border);
    }
    [data-bs-theme="dark"] .swal2-html-container,
    [data-bs-theme="dark"] .swal2-title { color: var(--text-primary) !important; }

    /* ── Navbar premium: glass + glow ───────────────────────────────── */
    .navbar.bg-dark {
        background: rgba(14,15,19,.85) !important;
        backdrop-filter: blur(14px);
        -webkit-backdrop-filter: blur(14px);
        border-bottom: 1px solid var(--panel-border);
    }
    .navbar-brand { letter-spacing:-.02em; }
    .navbar .nav-link {
        color: var(--text-muted) !important;
        font-weight:500;
        transition: color .15s ease;
    }
    .navbar .nav-link:hover { color: var(--text-primary) !important; }
    .navbar .nav-link.active {
        color: #fff !important;
        position: relative;
    }
    .navbar .nav-link.active::after {
        content: '';
        position: absolute;
        left: .5rem; right: .5rem; bottom: -2px;
        height: 2px;
        background: var(--accent);
        border-radius: 2px;
        box-shadow: 0 0 8px var(--accent);
    }

    /* Botón de tema y campana: glow sutil en hover */
    #theme-toggle, #notif-bell {
        border-color: var(--panel-border) !important;
        transition: all .15s ease;
    }
    #theme-toggle:hover, #notif-bell:hover {
        background-color: var(--accent-soft) !important;
        border-color: var(--accent) !important;
        box-shadow:0 0 0 3px rgba(124,108,240,.12);
    }
    #notif-badge { box-shadow:0 0 8px rgba(220,53,69,.6); }

    /* Badge de rol en el dropdown de usuario */
    [data-bs-theme="dark"] .badge.bg-info { background-color:#2f7fb8 !important; }
</style>

<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>POS - Sistema de Ventas</title>

    
    <script>
        (function () {
            try {
                var t = localStorage.getItem('pos_theme') || 'light';
                document.documentElement.setAttribute('data-bs-theme', t);
            } catch (e) {}
        })();
    </script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <style>
        html { transition: background-color .2s ease; }
        body { transition: background-color .2s ease, color .2s ease; }

        /* En modo oscuro, los fondos blancos/claros fijos de las vistas siguen el tema */
        [data-bs-theme="dark"] .bg-white { background-color: var(--bs-secondary-bg) !important; }
        [data-bs-theme="dark"] .bg-light { background-color: var(--bs-tertiary-bg) !important; }

        /* Tablas en oscuro: encabezados claros pasan a oscuros y con buen contraste */
        [data-bs-theme="dark"] .table { color: var(--bs-body-color); border-color: var(--bs-border-color); }
        [data-bs-theme="dark"] .table > :not(caption) > * > * { border-color: var(--bs-border-color); }
        [data-bs-theme="dark"] .table-light,
        [data-bs-theme="dark"] thead.table-light th,
        [data-bs-theme="dark"] .table-light > tr > th,
        [data-bs-theme="dark"] .table-light > tr > td {
            --bs-table-bg: #2b3035;
            --bs-table-color: #e9ecef;
            background-color: #2b3035 !important;
            color: #e9ecef !important;
        }
        /* Texto fijo oscuro que quede ilegible sobre superficies oscuras
           (no afecta badges de color, que llevan su propio fondo) */
        [data-bs-theme="dark"] .card .text-dark:not(.badge) { color: var(--bs-body-color) !important; }

        /* Botón de tema */
        #theme-toggle { line-height: 1; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm sticky-top">
    <div class="container-fluid px-4">

        <a class="navbar-brand fw-bold" href="<?php echo e(route('dashboard')); ?>">🏪 POS</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarPOS">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarPOS">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                <li class="nav-item">
                    <a class="nav-link <?php echo e(request()->routeIs('dashboard') ? 'active fw-semibold' : ''); ?>"
                       href="<?php echo e(route('dashboard')); ?>">📊 Dashboard</a>
                </li>

                <?php if(auth()->guard()->check()): ?>
                <?php if(!auth()->user()->isDomiciliario()): ?>

                <li class="nav-item">
                    <a class="nav-link <?php echo e(request()->routeIs('ventas.*') ? 'active fw-semibold' : ''); ?>"
                       href="<?php echo e(route('ventas.index')); ?>">🧾 Ventas</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?php echo e(request()->routeIs('pedidos.*') ? 'active fw-semibold' : ''); ?>"
                       href="<?php echo e(route('pedidos.index')); ?>">🚚 Pedidos</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?php echo e(request()->routeIs('clientes.*') ? 'active fw-semibold' : ''); ?>"
                       href="<?php echo e(route('clientes.index')); ?>">👤 Clientes</a>
                </li>

                
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?php echo e(request()->routeIs('productos.*') || request()->routeIs('inventarios.*') || request()->routeIs('categorias.*') || request()->routeIs('presentaciones.*') || request()->routeIs('tipos-flor.*') ? 'active fw-semibold' : ''); ?>"
                       href="#" role="button" data-bs-toggle="dropdown">
                        🗃️ Catálogo
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="<?php echo e(route('productos.index')); ?>">📦 Productos</a></li>
                        <?php if(auth()->user()->isAdmin()): ?>
                            <li><a class="dropdown-item" href="<?php echo e(route('productos.index', ['stock_bajo' => 1])); ?>">⚠️ Stock bajo</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><h6 class="dropdown-header">Configuración del catálogo</h6></li>
                            <li><a class="dropdown-item" href="<?php echo e(route('categorias.index')); ?>">🗂️ Categorías</a></li>
                            <li><a class="dropdown-item" href="<?php echo e(route('presentaciones.index')); ?>">📦 Presentaciones</a></li>
                            <li><a class="dropdown-item" href="<?php echo e(route('tipos-flor.index')); ?>">🌸 Tipos de flor</a></li>
                        <?php endif; ?>
                    </ul>
                </li>

                <?php endif; ?> 

                
                <?php if(auth()->user()->isDomiciliario()): ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo e(request()->routeIs('rutas.disponibles') ? 'active fw-semibold' : ''); ?>"
                           href="<?php echo e(route('rutas.disponibles')); ?>">📦 Disponibles</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo e(request()->routeIs('rutas.*') && !request()->routeIs('rutas.disponibles') ? 'active fw-semibold' : ''); ?>"
                           href="<?php echo e(route('rutas.index')); ?>">🗺 Mis Rutas</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle <?php echo e(request()->routeIs('domicilios.*') || request()->routeIs('rutas.*') ? 'active fw-semibold' : ''); ?>"
                           href="#" role="button" data-bs-toggle="dropdown">
                            🛵 Domicilios
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="<?php echo e(route('domicilios.index')); ?>">📋 Lista</a></li>
                            <li><a class="dropdown-item" href="<?php echo e(route('domicilios.mapa')); ?>">🗺 Mapa</a></li>
                            <li><a class="dropdown-item" href="<?php echo e(route('rutas.disponibles')); ?>">📦 Disponibles</a></li>
                            <?php if(auth()->user()->isAdmin()): ?>
                                <li><a class="dropdown-item" href="<?php echo e(route('rutas.index')); ?>">🛵 Todas las rutas</a></li>
                            <?php endif; ?>
                        </ul>
                    </li>
                <?php endif; ?>

                
                <?php if(auth()->user()->isAdmin()): ?>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?php echo e(request()->routeIs('vendedores.*') || request()->routeIs('domiciliarios.*') ? 'active fw-semibold' : ''); ?>"
                       href="#" role="button" data-bs-toggle="dropdown">
                        👥 Equipo
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="<?php echo e(route('vendedores.index')); ?>">👥 Vendedores</a></li>
                        <li><a class="dropdown-item" href="<?php echo e(route('domiciliarios.index')); ?>">🛵 Domiciliarios</a></li>
                    </ul>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?php echo e(request()->routeIs('descuentos.*') || request()->routeIs('users.*') || request()->routeIs('tunnel.*') ? 'active fw-semibold' : ''); ?>"
                       href="#" role="button" data-bs-toggle="dropdown">
                        ⚙️ Administración
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="<?php echo e(route('descuentos.index')); ?>">🎟️ Descuentos</a></li>
                        <li><a class="dropdown-item" href="<?php echo e(route('tunnel.index')); ?>">🌐 Publicar tienda (túnel)</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><h6 class="dropdown-header">Usuarios</h6></li>
                        <li><a class="dropdown-item" href="<?php echo e(route('users.index')); ?>">👥 Usuarios</a></li>
                        <li><a class="dropdown-item" href="<?php echo e(route('register')); ?>">➕ Nuevo usuario</a></li>
                    </ul>
                </li>

                <?php endif; ?> 
                <?php endif; ?>

            </ul>

            
            <div class="d-flex align-items-center gap-2">

                
                <button id="theme-toggle" type="button" class="btn btn-sm btn-outline-light"
                        title="Cambiar entre modo claro y oscuro" aria-label="Cambiar tema">
                    <span id="theme-ico">🌙</span>
                </button>

                
                <?php if(auth()->guard()->check()): ?>
                <div class="dropdown">
                    <button id="notif-bell" type="button" class="btn btn-sm btn-outline-light position-relative"
                            data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-label="Notificaciones">
                        🔔
                        <span id="notif-badge"
                              class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                              style="display:none; font-size:.6rem;">0</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow" id="notif-list"
                        style="width:330px; max-height:420px; overflow:auto;">
                        <li><h6 class="dropdown-header">Notificaciones</h6></li>
                        <li id="notif-empty"><span class="dropdown-item-text text-muted small">Sin notificaciones</span></li>
                    </ul>
                </div>
                <?php endif; ?>

                <?php if(auth()->guard()->check()): ?>
                <?php if(!auth()->user()->isDomiciliario()): ?>
                    <a href="<?php echo e(route('ventas.create')); ?>" class="btn btn-success btn-sm">➕ Nueva venta</a>
                <?php endif; ?>
                <?php endif; ?>

                <?php if(auth()->guard()->check()): ?>
                <div class="dropdown">
                    <a class="nav-link dropdown-toggle text-white d-flex align-items-center gap-1 px-2"
                       href="#" role="button" data-bs-toggle="dropdown">
                        <span><?php echo e(auth()->user()->name); ?></span>
                        <span class="badge <?php echo e(auth()->user()->isAdmin() ? 'bg-danger' : 'bg-info text-dark'); ?> ms-1" style="font-size:.7rem">
                            <?php echo e(auth()->user()->role); ?>

                        </span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li class="px-3 py-1">
                            <small class="text-muted"><?php echo e(auth()->user()->email); ?></small>
                        </li>
                        <li><hr class="dropdown-divider my-1"></li>
                        <li><a class="dropdown-item" href="<?php echo e(route('profile.edit')); ?>">⚙️ Mi perfil</a></li>
                        <li><hr class="dropdown-divider my-1"></li>
                        <li>
                            <form action="<?php echo e(route('logout')); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="dropdown-item text-danger">🚪 Cerrar sesión</button>
                            </form>
                        </li>
                    </ul>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<main class="py-3">
    <div class="container-fluid px-4">
        <?php echo $__env->yieldContent('content'); ?>
    </div>
</main>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
(function () {
    // ── Toast helper ───────────────────────────────────────────────
    function toast(icon, title, timer) {
        timer = timer || (icon === 'error' ? 5000 : 3500);
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: timer,
            timerProgressBar: true,
            didOpen: function (el) {
                el.addEventListener('mouseenter', Swal.stopTimer);
                el.addEventListener('mouseleave', Swal.resumeTimer);
            }
        });
        Toast.fire({ icon: icon, title: title });
    }

    // ── Disparar flashes de sesión PHP ─────────────────────────────
    document.addEventListener('DOMContentLoaded', function () {

        <?php if(session('success')): ?>
            toast('success', <?php echo json_encode(session('success'), 15, 512) ?>);
        <?php endif; ?>

        <?php if(session('warning')): ?>
            toast('warning', <?php echo json_encode(session('warning'), 15, 512) ?>, 4500);
        <?php endif; ?>

        <?php if(session('info')): ?>
            toast('info', <?php echo json_encode(session('info'), 15, 512) ?>);
        <?php endif; ?>

        <?php if(session('success_password')): ?>
            toast('success', <?php echo json_encode(session('success_password'), 15, 512) ?>);
        <?php endif; ?>

        // Errores de sesión o validación → modal (para que el texto largo sea legible)
        <?php if(session('error')): ?>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: <?php echo json_encode(session('error'), 15, 512) ?>,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Entendido',
            });
        <?php elseif($errors->any()): ?>
            var _errs = <?php echo json_encode($errors->all(), 15, 512) ?>;
            Swal.fire({
                icon: 'error',
                title: _errs.length === 1 ? 'Ups, hay un problema' : 'Hay ' + _errs.length + ' errores',
                html: _errs.map(function(e){ return '<div class="text-start py-1 small">• ' + e + '</div>'; }).join(''),
                confirmButtonColor: '#d33',
                confirmButtonText: 'Entendido',
                customClass: { htmlContainer: 'text-start' },
            });
        <?php endif; ?>

        // Eliminar TODOS los bloques Bootstrap alert de la página
        // (ya los manejamos con SweetAlert2 arriba)
        document.querySelectorAll('.alert').forEach(function (el) { el.remove(); });

        // ── Interceptar botones con data-confirm ───────────────────
        document.addEventListener('click', function (e) {
            const btn = e.target.closest('[data-confirm]');
            if (!btn) return;

            e.preventDefault();
            e.stopPropagation();

            const msg    = btn.dataset.confirm || '¿Estás seguro?';
            const icon   = btn.dataset.confirmIcon || 'warning';
            const okText = btn.dataset.confirmOk   || 'Sí, continuar';
            const form   = btn.closest('form');

            Swal.fire({
                title: '¿Confirmar acción?',
                text: msg,
                icon: icon,
                showCancelButton: true,
                confirmButtonColor: icon === 'warning' ? '#d33' : '#198754',
                cancelButtonColor: '#6c757d',
                confirmButtonText: okText,
                cancelButtonText: 'Cancelar',
                reverseButtons: true,
            }).then(function (result) {
                if (result.isConfirmed && form) {
                    // Si el botón tiene name/value, agregarlo al form antes de submit
                    if (btn.name && btn.value) {
                        var input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = btn.name;
                        input.value = btn.value;
                        form.appendChild(input);
                    }
                    form.submit();
                }
            });
        }, true);
    });

    // Exponer toast globalmente para scripts en vistas
    window.posToast = toast;
})();
</script>


<script>
(function () {
    var btn = document.getElementById('theme-toggle');
    var ico = document.getElementById('theme-ico');

    function aplicar(tema) {
        document.documentElement.setAttribute('data-bs-theme', tema);
        if (ico) ico.textContent = tema === 'dark' ? '☀️' : '🌙';
        try { localStorage.setItem('pos_theme', tema); } catch (e) {}
    }

    // Sincroniza el ícono con el tema ya aplicado en el <head>
    aplicar(document.documentElement.getAttribute('data-bs-theme') === 'dark' ? 'dark' : 'light');

    if (btn) {
        btn.addEventListener('click', function () {
            var actual = document.documentElement.getAttribute('data-bs-theme') === 'dark' ? 'dark' : 'light';
            aplicar(actual === 'dark' ? 'light' : 'dark');
        });
    }
})();
</script>

<?php if(auth()->guard()->check()): ?>
<script>
(function () {
    const bell  = document.getElementById('notif-bell');
    const badge = document.getElementById('notif-badge');
    const list  = document.getElementById('notif-list');
    if (!bell) return;

    const CSRF = document.querySelector('meta[name="csrf-token"]')?.content;
    const KEY_VISTO = 'pos_admin_notif_visto';
    let primeraCarga = true;

    function beep() {
        try {
            const ctx = new (window.AudioContext || window.webkitAudioContext)();
            const o = ctx.createOscillator(), g = ctx.createGain();
            o.connect(g); g.connect(ctx.destination);
            o.type = 'sine'; o.frequency.value = 880;
            g.gain.setValueAtTime(0.06, ctx.currentTime);
            o.start(); o.stop(ctx.currentTime + 0.15);
        } catch (e) {}
    }

    function pintar(data) {
        // Badge
        if (data.no_leidas > 0) {
            badge.textContent = data.no_leidas > 99 ? '99+' : data.no_leidas;
            badge.style.display = '';
        } else {
            badge.style.display = 'none';
        }

        // Lista
        const header = '<li><h6 class="dropdown-header">Notificaciones</h6></li>';
        if (!data.items.length) {
            list.innerHTML = header + '<li><span class="dropdown-item-text text-muted small">Sin notificaciones</span></li>';
        } else {
            list.innerHTML = header + data.items.map(function (n) {
                const url = n.data.url || '#';
                const peso = n.leida ? '' : 'fw-semibold';
                return '<li><a class="dropdown-item ' + peso + '" href="' + url + '" style="white-space:normal">'
                     + '<div class="small">' + (n.data.mensaje || '') + '</div>'
                     + '<div class="text-muted" style="font-size:.72rem">' + n.fecha + '</div></a></li>';
            }).join('');
        }
    }

    function consultar() {
        fetch('<?php echo e(route('notificaciones.index')); ?>', { headers: { 'Accept': 'application/json' } })
            .then(r => r.ok ? r.json() : null)
            .then(data => {
                if (!data) return;
                pintar(data);

                const visto = localStorage.getItem(KEY_VISTO);
                if (data.ultima_id && data.ultima_id !== visto) {
                    if (!primeraCarga && data.no_leidas > 0) {
                        const msg = data.items[0]?.data?.mensaje || 'Nueva notificación';
                        if (window.posToast) window.posToast('info', msg);
                        mostrarSO('Nueva venta', msg);
                        beep();
                    }
                    localStorage.setItem(KEY_VISTO, data.ultima_id);
                }
                primeraCarga = false;
            })
            .catch(() => {});
    }

    function mostrarSO(titulo, cuerpo) {
        if (!('Notification' in window)) return;
        if (Notification.permission === 'granted') {
            new Notification(titulo, { body: cuerpo, icon: '/favicon.ico' });
        }
    }

    // Pedir permiso al primer click en la campana (gesto del usuario)
    bell.addEventListener('click', function () {
        if ('Notification' in window && Notification.permission === 'default') {
            Notification.requestPermission();
        }
        // Marcar todas como leídas al abrir
        fetch('<?php echo e(route('notificaciones.leer')); ?>', {
            method: 'POST',
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF },
        }).then(r => r.ok ? r.json() : null).then(() => { badge.style.display = 'none'; }).catch(() => {});
    });

    consultar();
    setInterval(consultar, 20000);
})();
</script>
<?php endif; ?>

<?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\Users\Usuario\Documents\My Web Sites\POS\Backend\POSRed\resources\views\layouts\app.blade.php ENDPATH**/ ?>