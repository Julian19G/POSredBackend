<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS - Sistema de Ventas</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
        <!-- ✅ Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet" />

    
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm sticky-top">
    <div class="container">

        {{-- Logo --}}
        <a class="navbar-brand fw-bold" href="{{ url('/') }}">
            POS
        </a>

        {{-- Botón responsive --}}
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarPOS"
            aria-controls="navbarPOS" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        {{-- Links --}}
        <div class="collapse navbar-collapse" id="navbarPOS">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('ventas.*') ? 'active fw-semibold' : '' }}"
                       href="{{ route('ventas.index') }}">
                        Ventas
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('productos.*') ? 'active fw-semibold' : '' }}"
                       href="{{ route('productos.index') }}">
                        Productos
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('clientes.*') ? 'active fw-semibold' : '' }}"
                       href="{{ route('clientes.index') }}">
                        Clientes
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('detalle_ventas.*') ? 'active fw-semibold' : '' }}"
                       href="{{ route('detalle_ventas.index') }}">
                        Detalle Ventas
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('categorias.*') ? 'active fw-semibold' : '' }}"
                       href="{{ route('categorias.index') }}">
                        Categorías
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('pedidos.*') ? 'active fw-semibold' : '' }}"
                       href="{{ route('pedidos.index') }}">
                        Pedidos
                    </a>
                </li>


                  <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('descuentos.*') ? 'active fw-semibold' : '' }}"
                       href="{{ route('descuentos.index') }}">
                        Descuentos
                    </a>
                </li>

                  <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('domicilios.*') ? 'active fw-semibold' : '' }}"
                       href="{{ route('domicilios.index') }}">
                        Domicilios
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

    <main class="container">
        @yield('content')
    </main> 
<!-- ✅ jQuery (SIEMPRE primero) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- ✅ Select2 JS (DESPUÉS de jQuery) -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>
</body>
</html>
