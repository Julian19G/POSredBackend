<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'POS') — Acceso</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <style>
        body { background: #f0f2f5; min-height: 100vh; display: flex; align-items: center; }
        .auth-card { width: 100%; max-width: 420px; }
        .brand { font-size: 1.6rem; font-weight: 800; letter-spacing: -0.5px; }
    </style>
</head>
<body>
<div class="container py-5">
    <div class="mx-auto auth-card">

        <div class="text-center mb-4">
            <div class="brand">🏪 POS</div>
            <div class="text-muted small">Sistema de Ventas</div>
        </div>

        <div class="card border-0 shadow rounded-4">
            <div class="card-body p-4 p-md-5">
                @yield('content')
            </div>
        </div>

        <div class="text-center mt-3 small text-muted">
            @yield('footer-links')
        </div>

    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    function toast(icon, title, timer) {
        Swal.mixin({
            toast: true, position: 'top-end',
            showConfirmButton: false,
            timer: timer || (icon === 'error' ? 5000 : 3500),
            timerProgressBar: true,
        }).fire({ icon: icon, title: title });
    }

    @if(session('status'))
        toast('success', @json(session('status')));
    @endif
    @if(session('success'))
        toast('success', @json(session('success')));
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error', title: 'Error',
            text: @json(session('error')),
            confirmButtonColor: '#d33', confirmButtonText: 'Entendido',
        });
    @elseif($errors->any())
        var _errs = @json($errors->all());
        Swal.fire({
            icon: 'error',
            title: _errs.length === 1 ? 'Ups, hay un problema' : 'Hay ' + _errs.length + ' errores',
            html: _errs.map(function(e){ return '<div class="text-start py-1 small">• ' + e + '</div>'; }).join(''),
            confirmButtonColor: '#d33', confirmButtonText: 'Entendido',
        });
    @endif

    document.querySelectorAll('.alert').forEach(function (el) { el.remove(); });
});
</script>
</body>
</html>
