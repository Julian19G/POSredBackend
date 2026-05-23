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
</body>
</html>
