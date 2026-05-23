<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS - Sistema de Ventas</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet" />
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm sticky-top">
    <div class="container-fluid px-4">

        <a class="navbar-brand fw-bold" href="{{ route('dashboard') }}">🏪 POS</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarPOS">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarPOS">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active fw-semibold' : '' }}"
                       href="{{ route('dashboard') }}">Dashboard</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('ventas.*') ? 'active fw-semibold' : '' }}"
                       href="{{ route('ventas.index') }}">Ventas</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('pedidos.*') ? 'active fw-semibold' : '' }}"
                       href="{{ route('pedidos.index') }}">Pedidos</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('clientes.*') ? 'active fw-semibold' : '' }}"
                       href="{{ route('clientes.index') }}">Clientes</a>
                </li>

                {{-- Dropdown: Catálogo --}}
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ request()->routeIs('productos.*') || request()->routeIs('inventarios.*') || request()->routeIs('categorias.*') ? 'active fw-semibold' : '' }}"
                       href="#" role="button" data-bs-toggle="dropdown">
                        Catálogo
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('productos.index') }}">📦 Productos</a></li>
                        <li><a class="dropdown-item" href="{{ route('productos.index', ['stock_bajo' => 1]) }}">⚠ Stock bajo</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="{{ route('categorias.index') }}">🗂 Categorías</a></li>
                    </ul>
                </li>

                {{-- Dropdown: Equipo --}}
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ request()->routeIs('vendedores.*') ? 'active fw-semibold' : '' }}"
                       href="#" role="button" data-bs-toggle="dropdown">
                        Equipo
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('vendedores.index') }}">👥 Vendedores</a></li>
                    </ul>
                </li>

                {{-- Dropdown: Domicilios --}}
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ request()->routeIs('domicilios.*') ? 'active fw-semibold' : '' }}"
                       href="#" role="button" data-bs-toggle="dropdown">
                        Domicilios
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('domicilios.index') }}">📋 Lista</a></li>
                        <li><a class="dropdown-item" href="{{ route('domicilios.mapa') }}">🗺 Mapa de rutas</a></li>
                    </ul>
                </li>

                {{-- Dropdown: Config --}}
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ request()->routeIs('descuentos.*') ? 'active fw-semibold' : '' }}"
                       href="#" role="button" data-bs-toggle="dropdown">
                        Config
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('descuentos.index') }}">🎟 Descuentos</a></li>
                        <li><a class="dropdown-item" href="{{ route('categorias.index') }}">🗂 Categorías</a></li>
                    </ul>
                </li>

            </ul>

            {{-- Botón rápido --}}
            <a href="{{ route('ventas.create') }}" class="btn btn-success btn-sm">➕ Nueva venta</a>
        </div>
    </div>
</nav>

<main class="py-3">
    <div class="container-fluid px-4">
        @yield('content')
    </div>
</main>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>
</body>
</html>
