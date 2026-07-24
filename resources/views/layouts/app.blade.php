<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS - Sistema de Ventas</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
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

                @auth
                @if(!auth()->user()->isDomiciliario())

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

                {{-- Dropdown: Catálogo (no domiciliarios) --}}
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ request()->routeIs('productos.*') || request()->routeIs('inventarios.*') || request()->routeIs('categorias.*') ? 'active fw-semibold' : '' }}"
                       href="#" role="button" data-bs-toggle="dropdown">
                        Catálogo
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('productos.index') }}">📦 Productos</a></li>
                        @if(auth()->user()->isAdmin())
                            <li><a class="dropdown-item" href="{{ route('productos.index', ['stock_bajo' => 1]) }}">⚠ Stock bajo</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="{{ route('categorias.index') }}">🗂 Categorías</a></li>
                        @endif
                    </ul>
                </li>

                @endif {{-- !isDomiciliario --}}

                {{-- Dropdown: Domicilios / Rutas --}}
                @if(auth()->user()->isDomiciliario())
                    {{-- Domiciliario: solo ve sus rutas y disponibles --}}
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('rutas.disponibles') ? 'active fw-semibold' : '' }}"
                           href="{{ route('rutas.disponibles') }}">📦 Disponibles</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('rutas.*') && !request()->routeIs('rutas.disponibles') ? 'active fw-semibold' : '' }}"
                           href="{{ route('rutas.index') }}">🗺 Mis Rutas</a>
                    </li>
                @else
                    {{-- Admin/Vendedor: sección Domicilios --}}
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle {{ request()->routeIs('domicilios.*') || request()->routeIs('rutas.*') ? 'active fw-semibold' : '' }}"
                           href="#" role="button" data-bs-toggle="dropdown">
                            Domicilios
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('domicilios.index') }}">📋 Lista</a></li>
                            <li><a class="dropdown-item" href="{{ route('domicilios.mapa') }}">🗺 Mapa</a></li>
                            <li><a class="dropdown-item" href="{{ route('rutas.disponibles') }}">📦 Disponibles</a></li>
                            @if(auth()->user()->isAdmin())
                                <li><a class="dropdown-item" href="{{ route('rutas.index') }}">🛵 Todas las rutas</a></li>
                            @endif
                        </ul>
                    </li>
                @endif

                {{-- Equipo y Config: solo admins --}}
                @if(auth()->user()->isAdmin())

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ request()->routeIs('vendedores.*') || request()->routeIs('domiciliarios.*') ? 'active fw-semibold' : '' }}"
                       href="#" role="button" data-bs-toggle="dropdown">
                        Equipo
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('vendedores.index') }}">👥 Vendedores</a></li>
                        <li><a class="dropdown-item" href="{{ route('domiciliarios.index') }}">🛵 Domiciliarios</a></li>
                    </ul>
                </li>

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

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ request()->routeIs('users.*') ? 'active fw-semibold' : '' }}"
                       href="#" role="button" data-bs-toggle="dropdown">
                        Admin
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('users.index') }}">👥 Usuarios</a></li>
                        <li><a class="dropdown-item" href="{{ route('register') }}">➕ Nuevo usuario</a></li>
                    </ul>
                </li>

                @endif {{-- isAdmin --}}
                @endauth

            </ul>

            {{-- Botón rápido + usuario --}}
            <div class="d-flex align-items-center gap-2">
                @auth
                @if(!auth()->user()->isDomiciliario())
                    <a href="{{ route('ventas.create') }}" class="btn btn-success btn-sm">➕ Nueva venta</a>
                @endif
                @endauth

                @auth
                <div class="dropdown">
                    <a class="nav-link dropdown-toggle text-white d-flex align-items-center gap-1 px-2"
                       href="#" role="button" data-bs-toggle="dropdown">
                        <span>{{ auth()->user()->name }}</span>
                        <span class="badge {{ auth()->user()->isAdmin() ? 'bg-danger' : 'bg-info text-dark' }} ms-1" style="font-size:.7rem">
                            {{ auth()->user()->role }}
                        </span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li class="px-3 py-1">
                            <small class="text-muted">{{ auth()->user()->email }}</small>
                        </li>
                        <li><hr class="dropdown-divider my-1"></li>
                        <li><a class="dropdown-item" href="{{ route('profile.edit') }}">⚙️ Mi perfil</a></li>
                        <li><hr class="dropdown-divider my-1"></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">🚪 Cerrar sesión</button>
                            </form>
                        </li>
                    </ul>
                </div>
                @endauth
            </div>
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

        @if(session('success'))
            toast('success', @json(session('success')));
        @endif

        @if(session('warning'))
            toast('warning', @json(session('warning')), 4500);
        @endif

        @if(session('info'))
            toast('info', @json(session('info')));
        @endif

        @if(session('success_password'))
            toast('success', @json(session('success_password')));
        @endif

        // Errores de sesión o validación → modal (para que el texto largo sea legible)
        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: @json(session('error')),
                confirmButtonColor: '#d33',
                confirmButtonText: 'Entendido',
            });
        @elseif($errors->any())
            var _errs = @json($errors->all());
            Swal.fire({
                icon: 'error',
                title: _errs.length === 1 ? 'Ups, hay un problema' : 'Hay ' + _errs.length + ' errores',
                html: _errs.map(function(e){ return '<div class="text-start py-1 small">• ' + e + '</div>'; }).join(''),
                confirmButtonColor: '#d33',
                confirmButtonText: 'Entendido',
                customClass: { htmlContainer: 'text-start' },
            });
        @endif

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
@stack('scripts')
</body>
</html>
