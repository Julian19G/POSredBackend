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

        <a class="navbar-brand fw-bold" href="<?php echo e(route('dashboard')); ?>">🏪 POS</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarPOS">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarPOS">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                <li class="nav-item">
                    <a class="nav-link <?php echo e(request()->routeIs('dashboard') ? 'active fw-semibold' : ''); ?>"
                       href="<?php echo e(route('dashboard')); ?>">Dashboard</a>
                </li>

                <?php if(auth()->guard()->check()): ?>
                <?php if(!auth()->user()->isDomiciliario()): ?>

                <li class="nav-item">
                    <a class="nav-link <?php echo e(request()->routeIs('ventas.*') ? 'active fw-semibold' : ''); ?>"
                       href="<?php echo e(route('ventas.index')); ?>">Ventas</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?php echo e(request()->routeIs('pedidos.*') ? 'active fw-semibold' : ''); ?>"
                       href="<?php echo e(route('pedidos.index')); ?>">Pedidos</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?php echo e(request()->routeIs('clientes.*') ? 'active fw-semibold' : ''); ?>"
                       href="<?php echo e(route('clientes.index')); ?>">Clientes</a>
                </li>

                
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?php echo e(request()->routeIs('productos.*') || request()->routeIs('inventarios.*') || request()->routeIs('categorias.*') ? 'active fw-semibold' : ''); ?>"
                       href="#" role="button" data-bs-toggle="dropdown">
                        Catálogo
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="<?php echo e(route('productos.index')); ?>">📦 Productos</a></li>
                        <?php if(auth()->user()->isAdmin()): ?>
                            <li><a class="dropdown-item" href="<?php echo e(route('productos.index', ['stock_bajo' => 1])); ?>">⚠ Stock bajo</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?php echo e(route('categorias.index')); ?>">🗂 Categorías</a></li>
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
                            Domicilios
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
                        Equipo
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="<?php echo e(route('vendedores.index')); ?>">👥 Vendedores</a></li>
                        <li><a class="dropdown-item" href="<?php echo e(route('domiciliarios.index')); ?>">🛵 Domiciliarios</a></li>
                    </ul>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?php echo e(request()->routeIs('descuentos.*') ? 'active fw-semibold' : ''); ?>"
                       href="#" role="button" data-bs-toggle="dropdown">
                        Config
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="<?php echo e(route('descuentos.index')); ?>">🎟 Descuentos</a></li>
                        <li><a class="dropdown-item" href="<?php echo e(route('categorias.index')); ?>">🗂 Categorías</a></li>
                    </ul>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?php echo e(request()->routeIs('users.*') ? 'active fw-semibold' : ''); ?>"
                       href="#" role="button" data-bs-toggle="dropdown">
                        Admin
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="<?php echo e(route('users.index')); ?>">👥 Usuarios</a></li>
                        <li><a class="dropdown-item" href="<?php echo e(route('register')); ?>">➕ Nuevo usuario</a></li>
                    </ul>
                </li>

                <?php endif; ?> 
                <?php endif; ?>

            </ul>

            
            <div class="d-flex align-items-center gap-2">
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
<?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\Users\Usuario\Documents\My Web Sites\POS\Backend\POSRed\resources\views/layouts/app.blade.php ENDPATH**/ ?>