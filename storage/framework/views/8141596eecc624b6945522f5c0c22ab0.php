<?php $__env->startSection('content'); ?>
<style>
:root { --dash-radius: 14px; --dash-surface: #ffffff; --dash-border: #e9edf2; }
[data-bs-theme="dark"] { --dash-surface: #2b3035; --dash-border: #3a3f46; }

/* ── Header ───────────────────────────────────────────── */
.dash-header {
    background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
    color: #fff; border-radius: var(--dash-radius);
    padding: 1.25rem 1.5rem; margin-bottom: 1.5rem;
    display: flex; justify-content: space-between; align-items: center;
    gap: 1rem; flex-wrap: wrap;
    box-shadow: 0 6px 22px rgba(30,41,59,.22);
}
.dash-header h1 { font-size: 1.5rem; margin: 0; font-weight: 700; }
.dash-header .sub { opacity: .8; font-size: .85rem; margin-top: .15rem; }

/* ── Tarjetas de métrica (usan variables Bootstrap → se adaptan al tema) ── */
.stat-card {
    position: relative; display: block; height: 100%;
    background: var(--dash-surface); border: 1px solid var(--dash-border);
    border-radius: var(--dash-radius); overflow: hidden;
    box-shadow: 0 1px 3px rgba(0,0,0,.10); text-decoration: none; color: inherit;
    transition: transform .15s, box-shadow .15s;
}
.stat-card:hover { transform: translateY(-3px); box-shadow: 0 .6rem 1.4rem rgba(0,0,0,.22); color: inherit; }
.stat-card::before { content:''; position:absolute; left:0; top:0; bottom:0; width:4px; background: var(--accent,#0d6efd); }
.stat-card .body { padding: 1rem 1.1rem; display: flex; align-items: center; gap: .85rem; }
.stat-ico { width: 46px; height: 46px; border-radius: 12px; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center; font-size: 1.35rem; }
.stat-val { font-size: 1.5rem; font-weight: 700; line-height: 1; color: var(--bs-emphasis-color, #1e293b); }
.stat-val.money { font-size: 1.15rem; }
.stat-lbl { font-size: .76rem; color: var(--bs-secondary-color, #94a3b8); margin-top: .2rem; }
.stat-sub { font-size: .7rem; color: var(--bs-tertiary-color, #94a3b8); }

.bg-soft-primary  { background:#e7f1ff; color:#0d6efd; }
.bg-soft-info     { background:#e0f7fb; color:#0aa2c0; }
.bg-soft-success  { background:#e6f7ed; color:#198754; }
.bg-soft-warning  { background:#fff6e0; color:#f59e0b; }
.bg-soft-danger   { background:#fde8e8; color:#dc3545; }
.bg-soft-secondary{ background:#eef1f5; color:#64748b; }
/* En oscuro, mantener los íconos pastel con buen contraste */
[data-bs-theme="dark"] [class^="bg-soft-"] { filter: brightness(.85); }

/* ── Acciones rápidas ─────────────────────────────────── */
.quick-action {
    display: flex; flex-direction: column; align-items: center; justify-content: center;
    gap: .45rem; padding: 1rem .5rem; height: 100%;
    border-radius: 12px; background: var(--dash-surface); border: 1px solid var(--dash-border);
    text-decoration: none; color: var(--bs-body-color, #334155); font-size: .8rem; font-weight: 600; text-align: center;
    transition: all .15s;
}
.quick-action:hover { border-color: var(--bs-secondary-color); background: var(--bs-tertiary-bg, #f8fafc); transform: translateY(-2px);
    box-shadow: 0 .4rem 1rem rgba(0,0,0,.14); color: var(--bs-emphasis-color, #1e293b); }
.quick-action .qa-ico { font-size: 1.55rem; line-height: 1; }

/* ── Secciones / banners ──────────────────────────────── */
.section-card { border: 1px solid var(--dash-border); border-radius: var(--dash-radius); background: var(--dash-surface); box-shadow: 0 1px 3px rgba(0,0,0,.10); }
.section-card .card-header { background: transparent; border: 0; padding: 1rem 1.1rem .25rem; }
.dash-banner { border-radius: 12px; padding: .7rem 1rem; display: flex; align-items: center; gap: .75rem; margin-bottom: 1.25rem; }
/* Los banners tienen fondo pastel claro → forzar texto oscuro también en modo oscuro */
[data-bs-theme="dark"] .dash-banner,
[data-bs-theme="dark"] .dash-banner .text-muted { color: #334155 !important; }

.comision-bar { height: 8px; border-radius: 4px; background: var(--bs-tertiary-bg, #e9ecef); overflow: hidden; }
.comision-bar-fill { height: 100%; border-radius: 4px; background: linear-gradient(90deg,#0d6efd,#198754); transition: width .6s ease; }

.metric-card { cursor: pointer; transition: transform .15s, box-shadow .15s; color: inherit; }
.metric-card:hover { transform: translateY(-3px); box-shadow: 0 .5rem 1.5rem rgba(0,0,0,.12)!important; }
.rank-num { width: 22px; height: 22px; border-radius: 6px; background: var(--bs-tertiary-bg, #eef1f5); color: var(--bs-secondary-color, #64748b);
    font-size:.72rem; font-weight:700; display:inline-flex; align-items:center; justify-content:center; }
</style>


<?php if($esDomiciliario ?? false): ?>

<?php if($sinDomiciliario ?? false): ?>
<div class="container py-5" style="max-width:600px">
    <div class="section-card text-center p-5">
        <div class="fs-1 mb-3">⚠️</div>
        <h4 class="fw-bold mb-2">Tu cuenta no tiene perfil de domiciliario</h4>
        <p class="text-muted">Contacta al administrador para configurar tu perfil.</p>
    </div>
</div>
<?php else: ?>

<div class="container-fluid py-4">

    
    <div class="dash-header">
        <div>
            <h1>¡Hola, <?php echo e($domiciliario->nombre); ?>! 🛵</h1>
            <div class="sub"><?php echo e($domiciliario->vehiculoLabel()); ?> · <?php echo e(now()->format('d/m/Y H:i')); ?></div>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="<?php echo e(route('rutas.disponibles')); ?>" class="btn btn-warning btn-sm">📦 Disponibles (<?php echo e($disponiblesCount); ?>)</a>
            <a href="<?php echo e(route('rutas.index')); ?>" class="btn btn-outline-light btn-sm">🗺 Mis rutas</a>
        </div>
    </div>

    
    <?php if($tarifaVigente): ?>
    <div class="dash-banner" style="background:linear-gradient(135deg,#e3f2fd,#f0f7ff);">
        <span class="fs-5">💵</span>
        <span class="small">
            Tarifa vigente: <strong class="text-primary">$<?php echo e(number_format($tarifaVigente->monto, 0, ',', '.')); ?></strong>
            — <?php echo e($tarifaVigente->nombre); ?>

            <span class="text-muted">(<?php echo e(substr($tarifaVigente->hora_inicio,0,5)); ?>–<?php echo e(substr($tarifaVigente->hora_fin,0,5)); ?>)</span>
        </span>
    </div>
    <?php endif; ?>

    
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="stat-card" style="--accent:#198754">
                <div class="body">
                    <div class="stat-ico bg-soft-success">💵</div>
                    <div>
                        <div class="stat-val money text-success">$<?php echo e(number_format($gananciaHoy, 0, ',', '.')); ?></div>
                        <div class="stat-lbl">Ganado hoy</div>
                        <div class="stat-sub"><?php echo e($entregasHoy); ?> entrega(s)</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card" style="--accent:#0d6efd">
                <div class="body">
                    <div class="stat-ico bg-soft-primary">📅</div>
                    <div>
                        <div class="stat-val money text-primary">$<?php echo e(number_format($gananciaMes, 0, ',', '.')); ?></div>
                        <div class="stat-lbl">Este mes</div>
                        <div class="stat-sub"><?php echo e($entregasMes); ?> entrega(s)</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card" style="--accent:#64748b">
                <div class="body">
                    <div class="stat-ico bg-soft-secondary">🏆</div>
                    <div>
                        <div class="stat-val money text-secondary">$<?php echo e(number_format($gananciaTotal, 0, ',', '.')); ?></div>
                        <div class="stat-lbl">Total acumulado</div>
                        <div class="stat-sub">todas las entregas</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <a href="<?php echo e(route('rutas.disponibles')); ?>" class="stat-card" style="--accent:#f59e0b">
                <div class="body">
                    <div class="stat-ico bg-soft-warning">📦</div>
                    <div>
                        <div class="stat-val text-warning"><?php echo e($disponiblesCount); ?></div>
                        <div class="stat-lbl">Disponibles</div>
                        <div class="stat-sub">toca para ver</div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    
    <?php if($misActivos->isNotEmpty()): ?>
    <div class="section-card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="mb-0 fw-bold">⚡ En progreso (<?php echo e($misActivos->count()); ?>)</h6>
            <?php if($rutaActiva): ?>
            <a href="<?php echo e(route('rutas.show', $rutaActiva)); ?>" class="btn btn-sm btn-warning">Ver ruta →</a>
            <?php endif; ?>
        </div>
        <div class="card-body pt-1 pb-2">
            <?php $__currentLoopData = $misActivos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dom): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php $ruta = $dom->ruta; ?>
            <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                <div style="min-width:0">
                    <div class="fw-semibold text-truncate"><?php echo e($dom->venta->cliente->nombre ?? '—'); ?></div>
                    <div class="text-muted small text-truncate"><?php echo e($dom->direccion); ?></div>
                    <?php if($ruta): ?><div class="stat-sub"><?php echo e($ruta->tipoLabel()); ?> #<?php echo e($ruta->id); ?></div><?php endif; ?>
                </div>
                <div class="text-end ms-3 flex-shrink-0">
                    <div class="fw-bold text-success small">$<?php echo e(number_format($dom->tarifa_monto, 0, ',', '.')); ?></div>
                    <span class="badge bg-<?php echo e($dom->estadoColor()); ?>"><?php echo e($dom->estadoLabel()); ?></span>
                    <?php if($dom->cobrar_en_entrega): ?><div class="text-warning" style="font-size:.7rem">💵 cobrar</div><?php endif; ?>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
    <?php endif; ?>

    
    <?php if($rutaActiva): ?>
    <div class="section-card mb-4 border-start border-4 border-warning">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="mb-0 fw-bold"><?php echo e($rutaActiva->tipoLabel()); ?> activa #<?php echo e($rutaActiva->id); ?></h6>
                <a href="<?php echo e(route('rutas.show', $rutaActiva)); ?>" class="btn btn-warning btn-sm">Continuar →</a>
            </div>
            <?php
                $dEntregados = $rutaActiva->domicilios->where('estado', 'entregado')->count();
                $dTotal      = $rutaActiva->domicilios->count();
                $pctRuta     = $dTotal > 0 ? intval($dEntregados / $dTotal * 100) : 0;
            ?>
            <div class="progress mb-1" style="height:8px">
                <div class="progress-bar bg-success" style="width:<?php echo e($pctRuta); ?>%"></div>
            </div>
            <small class="text-muted"><?php echo e($dEntregados); ?>/<?php echo e($dTotal); ?> entregados</small>
        </div>
    </div>
    <?php elseif($misActivos->isEmpty()): ?>
    <div class="section-card mb-4 text-center p-5">
        <div class="fs-1 mb-2">📦</div>
        <h5 class="fw-bold">¿Listo para rodar?</h5>
        <p class="text-muted mb-3">
            Hay <strong class="<?php echo e($disponiblesCount > 0 ? 'text-success' : 'text-muted'); ?>"><?php echo e($disponiblesCount); ?> domicilio(s)</strong> esperando.
        </p>
        <a href="<?php echo e(route('rutas.disponibles')); ?>" class="btn btn-success btn-lg">Ver domicilios disponibles</a>
    </div>
    <?php endif; ?>

    
    <h6 class="text-muted mb-2">⚡ Accesos rápidos</h6>
    <div class="row g-3">
        <div class="col-6 col-md-3"><a href="<?php echo e(route('rutas.disponibles')); ?>" class="quick-action"><span class="qa-ico">📦</span>Disponibles</a></div>
        <div class="col-6 col-md-3"><a href="<?php echo e(route('rutas.index')); ?>" class="quick-action"><span class="qa-ico">🗺</span>Mis rutas</a></div>
        <div class="col-6 col-md-3"><a href="<?php echo e(route('profile.edit')); ?>" class="quick-action"><span class="qa-ico">⚙️</span>Mi perfil</a></div>
        <div class="col-6 col-md-3">
            <form action="<?php echo e(route('logout')); ?>" method="POST" class="h-100"><?php echo csrf_field(); ?>
                <button type="submit" class="quick-action w-100 border-0"><span class="qa-ico">🚪</span>Cerrar sesión</button>
            </form>
        </div>
    </div>

</div>
<?php endif; ?>


<?php elseif(!$esAdmin && ($sinVendedor ?? false)): ?>
<div class="container py-5" style="max-width:600px">
    <div class="section-card text-center p-5">
        <div class="fs-1 mb-3">⚠️</div>
        <h4 class="fw-bold mb-2">Tu cuenta no está vinculada a un vendedor</h4>
        <p class="text-muted mb-4">
            Pídele al administrador que vincule tu usuario a un perfil de vendedor para ver tu panel personalizado.
        </p>
        <a href="<?php echo e(route('ventas.create')); ?>" class="btn btn-primary">➕ Registrar venta</a>
    </div>
</div>


<?php elseif($esAdmin): ?>
<?php
    $hoyStr    = now()->toDateString();
    $semanaStr = now()->startOfWeek()->toDateString();
    $mesStr    = now()->startOfMonth()->toDateString();
?>
<div class="container-fluid py-4">

    
    <div class="dash-header">
        <div>
            <h1>📊 Panel de control</h1>
            <div class="sub">Hola, <?php echo e(auth()->user()->name); ?> · <?php echo e(now()->format('d/m/Y H:i')); ?></div>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="<?php echo e(route('ventas.create')); ?>" class="btn btn-success btn-sm">➕ Nueva venta</a>
            <a href="<?php echo e(route('productos.create')); ?>" class="btn btn-light btn-sm">📦 Nuevo producto</a>
            <a href="<?php echo e(route('clientes.create')); ?>" class="btn btn-outline-light btn-sm">👤 Nuevo cliente</a>
        </div>
    </div>

    
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-4 col-xl-2">
            <a href="<?php echo e(route('ventas.index', ['fecha_desde' => $hoyStr, 'fecha_hasta' => $hoyStr])); ?>" class="stat-card" style="--accent:#0d6efd">
                <div class="body">
                    <div class="stat-ico bg-soft-primary">🛒</div>
                    <div><div class="stat-val"><?php echo e($ventasHoy); ?></div><div class="stat-lbl">Ventas hoy</div></div>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-4 col-xl-2">
            <a href="<?php echo e(route('ventas.index', ['fecha_desde' => $semanaStr, 'fecha_hasta' => $hoyStr])); ?>" class="stat-card" style="--accent:#0aa2c0">
                <div class="body">
                    <div class="stat-ico bg-soft-info">📆</div>
                    <div><div class="stat-val"><?php echo e($ventasSemana); ?></div><div class="stat-lbl">Esta semana</div></div>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-4 col-xl-2">
            <a href="<?php echo e(route('ventas.index', ['fecha_desde' => $mesStr, 'fecha_hasta' => $hoyStr])); ?>" class="stat-card" style="--accent:#64748b">
                <div class="body">
                    <div class="stat-ico bg-soft-secondary">📈</div>
                    <div><div class="stat-val"><?php echo e($ventasMes); ?></div><div class="stat-lbl">Este mes</div></div>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-4 col-xl-2">
            <a href="<?php echo e(route('ventas.index', ['fecha_desde' => $mesStr, 'fecha_hasta' => $hoyStr, 'estado' => 'pagada'])); ?>" class="stat-card" style="--accent:#198754">
                <div class="body">
                    <div class="stat-ico bg-soft-success">💰</div>
                    <div><div class="stat-val money text-success">$<?php echo e(number_format($ingresosMes, 0, ',', '.')); ?></div><div class="stat-lbl">Ingresos del mes</div></div>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-4 col-xl-2">
            <a href="<?php echo e(route('ventas.index', ['estado' => 'pendiente'])); ?>" class="stat-card" style="--accent:#f59e0b">
                <div class="body">
                    <div class="stat-ico bg-soft-warning">⏳</div>
                    <div><div class="stat-val money text-warning">$<?php echo e(number_format($pendienteCobro, 0, ',', '.')); ?></div><div class="stat-lbl">Por cobrar</div></div>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-4 col-xl-2">
            <a href="<?php echo e(route('pedidos.index')); ?>" class="stat-card" style="--accent:#dc3545">
                <div class="body">
                    <div class="stat-ico bg-soft-danger">🚚</div>
                    <div><div class="stat-val text-danger"><?php echo e($pedidosPendientes); ?></div><div class="stat-lbl">Pedidos activos</div></div>
                </div>
            </a>
        </div>
    </div>

    
    <?php if($comisionesTotales > 0): ?>
    <div class="dash-banner" style="background:#fff8e1;">
        <span class="fs-5">💸</span>
        <span class="small">
            Hay <strong>$<?php echo e(number_format($comisionesTotales, 0, ',', '.')); ?></strong> en comisiones pendientes de pago a vendedores.
        </span>
        <a href="<?php echo e(route('vendedores.index')); ?>" class="btn btn-sm btn-warning ms-auto">Ver vendedores</a>
    </div>
    <?php endif; ?>

    
    <div class="section-card mb-4">
        <div class="card-body d-flex align-items-center gap-3 flex-wrap">
            <div class="stat-ico bg-soft-success" style="width:52px;height:52px;font-size:1.5rem">🛵</div>
            <div>
                <div class="fw-bold text-success" style="font-size:1.3rem">
                    $<?php echo e(number_format($gananciaEnviosMes, 0, ',', '.')); ?>

                </div>
                <div class="small text-muted">
                    Ganancia extra por envíos este mes
                    <span class="text-secondary">· recargo de referidos que retiene la tienda (logística)</span>
                </div>
            </div>
            <div class="ms-auto text-end">
                <div class="fw-semibold">$<?php echo e(number_format($gananciaEnviosTotal, 0, ',', '.')); ?></div>
                <div class="stat-sub">total acumulado</div>
            </div>
        </div>
    </div>

    
    <div class="section-card mb-4">
        <div class="card-header"><h6 class="mb-0 fw-bold">⚡ Acciones rápidas</h6></div>
        <div class="card-body pt-2">
            <div class="row g-2">
                <div class="col-4 col-md-3 col-lg-2"><a href="<?php echo e(route('ventas.create')); ?>"  class="quick-action"><span class="qa-ico">➕</span>Nueva venta</a></div>
                <div class="col-4 col-md-3 col-lg-2"><a href="<?php echo e(route('productos.create')); ?>" class="quick-action"><span class="qa-ico">📦</span>Nuevo producto</a></div>
                <div class="col-4 col-md-3 col-lg-2"><a href="<?php echo e(route('clientes.create')); ?>"  class="quick-action"><span class="qa-ico">👤</span>Nuevo cliente</a></div>
                <div class="col-4 col-md-3 col-lg-2"><a href="<?php echo e(route('pedidos.index')); ?>"    class="quick-action"><span class="qa-ico">🚚</span>Pedidos</a></div>
                <div class="col-4 col-md-3 col-lg-2"><a href="<?php echo e(route('productos.index')); ?>"  class="quick-action"><span class="qa-ico">🗃️</span>Catálogo</a></div>
                <div class="col-4 col-md-3 col-lg-2"><a href="<?php echo e(route('productos.index', ['stock_bajo' => 1])); ?>" class="quick-action"><span class="qa-ico">⚠️</span>Stock bajo</a></div>
                <div class="col-4 col-md-3 col-lg-2"><a href="<?php echo e(route('clientes.index')); ?>"   class="quick-action"><span class="qa-ico">🧑‍🤝‍🧑</span>Clientes</a></div>
                <div class="col-4 col-md-3 col-lg-2"><a href="<?php echo e(route('vendedores.index')); ?>" class="quick-action"><span class="qa-ico">👥</span>Vendedores</a></div>
                <div class="col-4 col-md-3 col-lg-2"><a href="<?php echo e(route('domiciliarios.index')); ?>" class="quick-action"><span class="qa-ico">🛵</span>Domiciliarios</a></div>
                <div class="col-4 col-md-3 col-lg-2"><a href="<?php echo e(route('domicilios.mapa')); ?>"  class="quick-action"><span class="qa-ico">🗺️</span>Mapa</a></div>
                <div class="col-4 col-md-3 col-lg-2"><a href="<?php echo e(route('descuentos.index')); ?>" class="quick-action"><span class="qa-ico">🎟️</span>Descuentos</a></div>
                <div class="col-4 col-md-3 col-lg-2"><a href="<?php echo e(route('categorias.index')); ?>" class="quick-action"><span class="qa-ico">🗂️</span>Categorías</a></div>
                <div class="col-4 col-md-3 col-lg-2"><a href="<?php echo e(route('users.index')); ?>"      class="quick-action"><span class="qa-ico">🔐</span>Usuarios</a></div>
                <div class="col-4 col-md-3 col-lg-2"><a href="<?php echo e(route('ventas.index')); ?>"     class="quick-action"><span class="qa-ico">📋</span>Todas las ventas</a></div>
            </div>
        </div>
    </div>

    <div class="row g-4">

        
        <div class="col-lg-8">
            <div class="section-card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold">🧾 Ventas recientes</h6>
                    <a href="<?php echo e(route('ventas.index')); ?>" class="btn btn-sm btn-outline-primary">Ver todas</a>
                </div>
                <div class="card-body p-0 pt-2">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">#</th><th>Cliente</th><th>Vendedor</th><th>Total</th><th>Estado</th><th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $ventasRecientes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td class="ps-3 text-muted small"><?php echo e($v->id); ?></td>
                                    <td><?php echo e($v->cliente->nombre ?? '—'); ?></td>
                                    <td class="small text-muted"><?php echo e($v->vendedor->nombre ?? '—'); ?></td>
                                    <td><strong>$<?php echo e(number_format($v->total, 0, ',', '.')); ?></strong></td>
                                    <td>
                                        <?php switch($v->estado):
                                            case ('pagada'): ?>    <span class="badge bg-success">Pagada</span>    <?php break; ?>
                                            <?php case ('pendiente'): ?> <span class="badge bg-warning text-dark">Pendiente</span> <?php break; ?>
                                            <?php case ('cancelada'): ?> <span class="badge bg-danger">Cancelada</span>  <?php break; ?>
                                        <?php endswitch; ?>
                                    </td>
                                    <td><a href="<?php echo e(route('ventas.show', $v->id)); ?>" class="btn btn-sm btn-outline-secondary py-0 px-2">Ver</a></td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr><td colspan="6" class="text-center text-muted py-3">Sin ventas aún</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="col-lg-4 d-flex flex-column gap-4">

            <div class="section-card">
                <div class="card-header"><h6 class="mb-0 fw-bold">🏆 Top productos vendidos</h6></div>
                <div class="card-body pt-2">
                    <?php $__empty_1 = true; $__currentLoopData = $topProductos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $tp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="d-flex align-items-center gap-2" style="min-width:0">
                            <span class="rank-num"><?php echo e($i + 1); ?></span>
                            <div style="min-width:0">
                                <div class="small fw-semibold text-truncate"><?php echo e($tp->nombre_producto); ?></div>
                                <div class="stat-sub">$<?php echo e(number_format($tp->total_ingresos, 0, ',', '.')); ?></div>
                            </div>
                        </div>
                        <span class="badge bg-primary rounded-pill flex-shrink-0"><?php echo e($tp->total_vendido); ?> uds</span>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p class="text-muted small mb-0">Sin datos</p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="section-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold text-danger">⚠️ Stock bajo (≤ 5)</h6>
                    <a href="<?php echo e(route('productos.index', ['stock_bajo' => 1])); ?>" class="btn btn-sm btn-outline-danger">Ver todos</a>
                </div>
                <div class="card-body pt-2">
                    <?php $__empty_1 = true; $__currentLoopData = $stockBajo; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="small text-truncate">
                            <span class="fw-semibold"><?php echo e($v->producto->nombre ?? '—'); ?></span>
                            <span class="text-muted"> · <?php echo e($v->nombre); ?></span>
                        </div>
                        <span class="badge <?php echo e($v->stock === 0 ? 'bg-danger' : 'bg-warning text-dark'); ?> flex-shrink-0"><?php echo e($v->stock); ?> uds</span>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p class="text-success small mb-0">✓ Todo el stock está OK</p>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>

</div>


<?php else: ?>
<div class="container-fluid py-4">

    
    <div class="dash-header">
        <div>
            <h1>Bienvenido, <?php echo e($vendedor->nombre); ?> 👋</h1>
            <div class="sub">Tu panel personal · <?php echo e(now()->format('d/m/Y H:i')); ?></div>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="<?php echo e(route('ventas.create')); ?>" class="btn btn-success btn-sm">➕ Nueva venta</a>
            <a href="<?php echo e(route('vendedores.show', $vendedor->id)); ?>" class="btn btn-outline-light btn-sm">💸 Mis comisiones</a>
        </div>
    </div>

    
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-4 col-xl">
            <div class="stat-card" style="--accent:#0d6efd"><div class="body">
                <div class="stat-ico bg-soft-primary">🛒</div>
                <div><div class="stat-val"><?php echo e($ventasHoy); ?></div><div class="stat-lbl">Ventas hoy</div></div>
            </div></div>
        </div>
        <div class="col-6 col-md-4 col-xl">
            <div class="stat-card" style="--accent:#0aa2c0"><div class="body">
                <div class="stat-ico bg-soft-info">📆</div>
                <div><div class="stat-val"><?php echo e($ventasSemana); ?></div><div class="stat-lbl">Esta semana</div></div>
            </div></div>
        </div>
        <div class="col-6 col-md-4 col-xl">
            <div class="stat-card" style="--accent:#64748b"><div class="body">
                <div class="stat-ico bg-soft-secondary">📈</div>
                <div><div class="stat-val"><?php echo e($ventasMes); ?></div><div class="stat-lbl">Este mes</div></div>
            </div></div>
        </div>
        <div class="col-6 col-md-4 col-xl">
            <div class="stat-card" style="--accent:#198754"><div class="body">
                <div class="stat-ico bg-soft-success">💰</div>
                <div><div class="stat-val money text-success">$<?php echo e(number_format($ingresosMes, 0, ',', '.')); ?></div><div class="stat-lbl">Ingresos mes</div></div>
            </div></div>
        </div>
        <div class="col-6 col-md-4 col-xl">
            <a href="<?php echo e(route('pedidos.index')); ?>" class="stat-card" style="--accent:#dc3545"><div class="body">
                <div class="stat-ico bg-soft-danger">🚚</div>
                <div><div class="stat-val text-danger"><?php echo e($pedidosActivos); ?></div><div class="stat-lbl">Pedidos activos</div></div>
            </div></a>
        </div>
    </div>

    
    <?php if($comisionPendiente > 0): ?>
    <div class="dash-banner" style="background:linear-gradient(135deg,#e8f5e9,#f1f8e9);">
        <span class="fs-4">💸</span>
        <div>
            <div class="fw-semibold small">Tienes comisiones pendientes de cobro</div>
            <div class="fw-bold text-success">$<?php echo e(number_format($comisionPendiente, 0, ',', '.')); ?></div>
        </div>
        <a href="<?php echo e(route('vendedores.show', $vendedor->id)); ?>" class="btn btn-sm btn-success ms-auto">Ver mis comisiones</a>
    </div>
    <?php endif; ?>

    <div class="row g-4">

        
        <div class="col-lg-8">
            <div class="section-card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold">🧾 Mis ventas recientes</h6>
                    <a href="<?php echo e(route('ventas.index')); ?>" class="btn btn-sm btn-outline-primary">Ver todas</a>
                </div>
                <div class="card-body p-0 pt-2">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light">
                                <tr><th class="ps-3">#</th><th>Cliente</th><th>Total</th><th>Pago</th><th>Estado</th><th></th></tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $ventasRecientes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td class="ps-3 text-muted small"><?php echo e($v->id); ?></td>
                                    <td><?php echo e($v->cliente->nombre ?? '—'); ?></td>
                                    <td><strong>$<?php echo e(number_format($v->total, 0, ',', '.')); ?></strong></td>
                                    <td>
                                        <?php if($v->pedido?->metodo_pago): ?>
                                            <span class="badge bg-light text-dark"><?php echo e(Str::ucfirst($v->pedido->metodo_pago)); ?></span>
                                        <?php else: ?>
                                            <span class="text-muted small">—</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php switch($v->estado):
                                            case ('pagada'): ?>    <span class="badge bg-success">Pagada</span>    <?php break; ?>
                                            <?php case ('pendiente'): ?> <span class="badge bg-warning text-dark">Pendiente</span> <?php break; ?>
                                            <?php case ('cancelada'): ?> <span class="badge bg-danger">Cancelada</span>  <?php break; ?>
                                        <?php endswitch; ?>
                                    </td>
                                    <td><a href="<?php echo e(route('ventas.show', $v->id)); ?>" class="btn btn-sm btn-outline-secondary py-0 px-2">Ver</a></td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        Aún no tienes ventas registradas.<br>
                                        <a href="<?php echo e(route('ventas.create')); ?>" class="btn btn-sm btn-primary mt-2">➕ Registrar primera venta</a>
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="col-lg-4 d-flex flex-column gap-4">

            
            <div class="section-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold">💰 Mis comisiones</h6>
                    <a href="<?php echo e(route('vendedores.show', $vendedor->id)); ?>" class="btn btn-sm btn-outline-secondary">Detalle</a>
                </div>
                <div class="card-body pt-2">
                    <div class="row text-center g-2 mb-3">
                        <div class="col-4">
                            <div class="fw-bold text-success" style="font-size:1rem">$<?php echo e(number_format($comisionTotal, 0, ',', '.')); ?></div>
                            <div class="stat-sub">Total generado</div>
                        </div>
                        <div class="col-4">
                            <div class="fw-bold text-primary" style="font-size:1rem">$<?php echo e(number_format($comisionCobrada, 0, ',', '.')); ?></div>
                            <div class="stat-sub">Ya cobrado</div>
                        </div>
                        <div class="col-4">
                            <div class="fw-bold text-warning" style="font-size:1rem">$<?php echo e(number_format($comisionPendiente, 0, ',', '.')); ?></div>
                            <div class="stat-sub">Por cobrar</div>
                        </div>
                    </div>
                    <?php if($comisionTotal > 0): ?>
                    <?php $pct = min(round($comisionCobrada / $comisionTotal * 100), 100); ?>
                    <div class="comision-bar mb-1"><div class="comision-bar-fill" style="width:<?php echo e($pct); ?>%"></div></div>
                    <div class="text-end stat-sub"><?php echo e($pct); ?>% cobrado</div>
                    <?php endif; ?>

                    <?php if($ultimasComisiones->isNotEmpty()): ?>
                    <hr class="my-2">
                    <div class="small text-muted fw-semibold mb-2">Últimas comisiones</div>
                    <?php $__currentLoopData = $ultimasComisiones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $com): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="d-flex justify-content-between align-items-center py-1 border-bottom small">
                        <span class="text-muted">Venta #<?php echo e($com->venta_id); ?></span>
                        <div class="d-flex gap-2 align-items-center">
                            <span class="fw-semibold">$<?php echo e(number_format($com->monto_comision, 0, ',', '.')); ?></span>
                            <span class="badge bg-<?php echo e($com->estado === 'pagada' ? 'success' : ($com->estado === 'anulada' ? 'secondary' : 'warning text-dark')); ?>"><?php echo e($com->estado); ?></span>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                </div>
            </div>

            
            <div class="section-card">
                <div class="card-header"><h6 class="mb-0 fw-bold">📦 Mis productos más vendidos</h6></div>
                <div class="card-body pt-2">
                    <?php $__empty_1 = true; $__currentLoopData = $topProductos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $tp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="d-flex align-items-center gap-2" style="min-width:0">
                            <span class="rank-num"><?php echo e($i + 1); ?></span>
                            <div style="min-width:0">
                                <div class="small fw-semibold text-truncate"><?php echo e($tp->nombre_producto); ?></div>
                                <div class="stat-sub">$<?php echo e(number_format($tp->total_ingresos, 0, ',', '.')); ?></div>
                            </div>
                        </div>
                        <span class="badge bg-primary rounded-pill flex-shrink-0"><?php echo e($tp->total_vendido); ?> uds</span>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p class="text-muted small mb-0">Aún no hay ventas registradas.</p>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>

    
    <h6 class="text-muted mb-2 mt-4">⚡ Accesos rápidos</h6>
    <div class="row g-2">
        <div class="col-4 col-md-3 col-lg-2"><a href="<?php echo e(route('ventas.create')); ?>" class="quick-action"><span class="qa-ico">➕</span>Nueva venta</a></div>
        <div class="col-4 col-md-3 col-lg-2"><a href="<?php echo e(route('clientes.create')); ?>" class="quick-action"><span class="qa-ico">👤</span>Nuevo cliente</a></div>
        <div class="col-4 col-md-3 col-lg-2"><a href="<?php echo e(route('ventas.index')); ?>" class="quick-action"><span class="qa-ico">📋</span>Mis ventas</a></div>
        <div class="col-4 col-md-3 col-lg-2"><a href="<?php echo e(route('pedidos.index')); ?>" class="quick-action"><span class="qa-ico">🚚</span>Pedidos</a></div>
        <div class="col-4 col-md-3 col-lg-2"><a href="<?php echo e(route('productos.index')); ?>" class="quick-action"><span class="qa-ico">📦</span>Catálogo</a></div>
        <div class="col-4 col-md-3 col-lg-2"><a href="<?php echo e(route('vendedores.show', $vendedor->id)); ?>" class="quick-action"><span class="qa-ico">💸</span>Comisiones</a></div>
    </div>

</div>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Usuario\Documents\My Web Sites\POS\Backend\POSRed\resources\views/dashboard.blade.php ENDPATH**/ ?>