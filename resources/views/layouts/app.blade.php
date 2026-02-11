<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS - Sistema de Ventas</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
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
                    <a class="nav-link {{ request()->routeIs('efectos.*') ? 'active fw-semibold' : '' }}"
                       href="{{ route('efectos.index') }}">
                        Efectos
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('colores.*') ? 'active fw-semibold' : '' }}"
                       href="{{ route('colores.index') }}">
                        Colores
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('sabores.*') ? 'active fw-semibold' : '' }}"
                       href="{{ route('sabores.index') }}">
                        Sabores
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('pivotes.*') ? 'active fw-semibold' : '' }}"
                       href="{{ route('pivotes.index') }}">
                        Pivotes
                    </a>
                </li>

                  <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('descuentos.*') ? 'active fw-semibold' : '' }}"
                       href="{{ route('descuentos.index') }}">
                        Descuentos
                    </a>
                </li>

            </ul>
        </div>
    </div>
</nav>

    <main class="container">
        @yield('content')
    </main> 
</body>
</html>
