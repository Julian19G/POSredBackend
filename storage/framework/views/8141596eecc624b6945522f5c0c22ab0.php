<?php $__env->startSection('content'); ?>
<style>
.metric-card { cursor:pointer; transition:transform .15s,box-shadow .15s; color:inherit; }
.metric-card:hover { transform:translateY(-3px); box-shadow:0 .5rem 1.5rem rgba(0,0,0,.12)!important; }
.metric-card:active { transform:translateY(0); }
.comision-bar { height:8px; border-radius:4px; background:#e9ecef; overflow:hidden; }
.comision-bar-fill { height:100%; border-radius:4px; background:linear-gradient(90deg,#0d6efd,#198754); transition:width .6s ease; }
</style>


<?php if($esDomiciliario ?? false): ?>

<?php if($sinDomiciliario ?? false): ?>
<div class="container py-5" style="max-width:600px">
    <div class="card border-0 shadow-sm rounded-4 text-center p-5">
        <div class="fs-1 mb-3">⚠️</div>
        <h4 class="fw-bold mb-2">Tu cuenta no tiene perfil de domiciliario</h4>
        <p class="text-muted">Contacta al administrador para configurar tu perfil.</p>
    </div>
</div>
<?php else: ?>

<div class="container py-4">

    
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h2 class="fw-bold mb-0">¡Hola, <?php echo e($domiciliario->nombre); ?>! 🛵</h2>
            <span class="text-muted"><?php echo e($domiciliario->vehiculoLabel()); ?></span>
        </div>
        <small class="text-muted mt-1"><?php echo e(now()->format('d/m/Y H:i')); ?></small>
    </div>

    
    <?php if($tarifaVigente): ?>
    <div class="alert border-0 rounded-3 mb-4 py-2 px-3 d-flex align-items-center gap-2"
         style="background:linear-gradient(135deg,#e3f2fd,#f0f7ff);">
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
            <div class="card border-0 shadow-sm h-100 text-center py-3 px-2">
                <div class="fw-bold text-success" style="font-size:1.3rem">
                    $<?php echo e(number_format($gananciaHoy, 0, ',', '.')); ?>

                </div>
                <div class="small text-muted">Ganado hoy</div>
                <div class="text-muted" style="font-size:.72rem"><?php echo e($entregasHoy); ?> entrega(s)</div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm h-100 text-center py-3 px-2">
                <div class="fw-bold text-primary" style="font-size:1.3rem">
                    $<?php echo e(number_format($gananciaMes, 0, ',', '.')); ?>

                </div>
                <div class="small text-muted">Este mes</div>
                <div class="text-muted" style="font-size:.72rem"><?php echo e($entregasMes); ?> entrega(s)</div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm h-100 text-center py-3 px-2">
                <div class="fw-bold text-secondary" style="font-size:1.3rem">
                    $<?php echo e(number_format($gananciaTotal, 0, ',', '.')); ?>

                </div>
                <div class="small text-muted">Total acumulado</div>
                <div class="text-muted" style="font-size:.72rem">todas las entregas</div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <a href="<?php echo e(route('rutas.disponibles')); ?>" class="text-decoration-none">
                <div class="card border-0 shadow-sm h-100 text-center py-3 px-2 <?php echo e($disponiblesCount > 0 ? 'border border-warning border-2' : ''); ?>">
                    <div class="fw-bold <?php echo e($disponiblesCount > 0 ? 'text-warning' : 'text-muted'); ?>" style="font-size:1.5rem">
                        <?php echo e($disponiblesCount); ?>

                    </div>
                    <div class="small text-muted">Disponibles</div>
                    <div class="text-muted" style="font-size:.72rem">toca para ver</div>
                </div>
            </a>
        </div>

    </div>

    
    <?php if($misActivos->isNotEmpty()): ?>
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center pt-3">
            <h6 class="mb-0 fw-bold">⚡ En progreso (<?php echo e($misActivos->count()); ?>)</h6>
            <?php if($rutaActiva): ?>
            <a href="<?php echo e(route('rutas.show', $rutaActiva)); ?>" class="btn btn-sm btn-warning">Ver ruta →</a>
            <?php endif; ?>
        </div>
        <div class="card-body pt-1 pb-2">
            <?php $__currentLoopData = $misActivos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dom): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $ruta = $dom->ruta;
            ?>
            <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                <div style="min-width:0">
                    <div class="fw-semibold text-truncate"><?php echo e($dom->venta->cliente->nombre ?? '—'); ?></div>
                    <div class="text-muted small text-truncate"><?php echo e($dom->direccion); ?></div>
                    <?php if($ruta): ?>
                    <div class="text-muted" style="font-size:.72rem"><?php echo e($ruta->tipoLabel()); ?> #<?php echo e($ruta->id); ?></div>
                    <?php endif; ?>
                </div>
                <div class="text-end ms-3 flex-shrink-0">
                    <div class="fw-bold text-success small">$<?php echo e(number_format($dom->tarifa_monto, 0, ',', '.')); ?></div>
                    <span class="badge bg-<?php echo e($dom->estadoColor()); ?>"><?php echo e($dom->estadoLabel()); ?></span>
                    <?php if($dom->cobrar_en_entrega): ?>
                    <div class="text-warning" style="font-size:.7rem">💵 cobrar</div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
    <?php endif; ?>

    
    <?php if($rutaActiva): ?>
    <div class="card border-0 shadow-sm rounded-3 mb-4 border-start border-4 border-warning">
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
    
    <div class="card border-0 shadow-sm rounded-3 mb-4 text-center p-5">
        <div class="fs-1 mb-2">📦</div>
        <h5 class="fw-bold">¿Listo para rodar?</h5>
        <p class="text-muted mb-3">
            Hay <strong class="<?php echo e($disponiblesCount > 0 ? 'text-success' : 'text-muted'); ?>">
                <?php echo e($disponiblesCount); ?> domicilio(s)
            </strong> esperando.
        </p>
        <a href="<?php echo e(route('rutas.disponibles')); ?>" class="btn btn-success btn-lg">
            Ver domicilios disponibles
        </a>
    </div>
    <?php endif; ?>

    
    <div class="row g-3">
        <div class="col-6">
            <a href="<?php echo e(route('rutas.disponibles')); ?>" class="card border-0 shadow-sm text-decoration-none h-100">
                <div class="card-body text-center py-4">
                    <div class="fs-2 mb-1">📦</div>
                    <div class="fw-semibold">Disponibles</div>
                </div>
            </a>
        </div>
        <div class="col-6">
            <a href="<?php echo e(route('rutas.index')); ?>" class="card border-0 shadow-sm text-decoration-none h-100">
                <div class="card-body text-center py-4">
                    <div class="fs-2 mb-1">🗺</div>
                    <div class="fw-semibold">Mis rutas</div>
                </div>
            </a>
        </div>
    </div>

</div>
<?php endif; ?>


<?php elseif(!$esAdmin && ($sinVendedor ?? false)): ?>
<div class="container py-5" style="max-width:600px">
    <div class="card border-0 shadow-sm rounded-4 text-center p-5">
        <div class="fs-1 mb-3">⚠️</div>
        <h4 class="fw-bold mb-2">Tu cuenta no está vinculada a un vendedor</h4>
        <p class="text-muted mb-4">
            Pídele al administrador que vincule tu usuario a un perfil de vendedor
            para poder ver tu panel personalizado.
        </p>
        <a href="<?php echo e(route('ventas.create')); ?>" class="btn btn-primary">➕ Registrar venta</a>
    </div>
</div>


<?php elseif($esAdmin): ?>
<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="mb-0">Dashboard</h1>
            <small class="text-muted">Vista general del negocio</small>
        </div>
        <small class="text-muted"><?php echo e(now()->format('d/m/Y H:i')); ?></small>
    </div>

    
    <?php
        $hoyStr    = now()->toDateString();
        $semanaStr = now()->startOfWeek()->toDateString();
        $mesStr    = now()->startOfMonth()->toDateString();
    ?>
    <div class="row g-3 mb-4">

        <div class="col-6 col-md-4 col-lg-2">
            <a href="<?php echo e(route('ventas.index', ['fecha_desde' => $hoyStr, 'fecha_hasta' => $hoyStr])); ?>"
               class="card border-0 shadow-sm text-center h-100 text-decoration-none metric-card">
                <div class="card-body py-3">
                    <div class="fs-2 fw-bold text-primary"><?php echo e($ventasHoy); ?></div>
                    <div class="small text-muted">Ventas hoy</div>
                </div>
            </a>
        </div>

        <div class="col-6 col-md-4 col-lg-2">
            <a href="<?php echo e(route('ventas.index', ['fecha_desde' => $semanaStr, 'fecha_hasta' => $hoyStr])); ?>"
               class="card border-0 shadow-sm text-center h-100 text-decoration-none metric-card">
                <div class="card-body py-3">
                    <div class="fs-2 fw-bold text-info"><?php echo e($ventasSemana); ?></div>
                    <div class="small text-muted">Esta semana</div>
                </div>
            </a>
        </div>

        <div class="col-6 col-md-4 col-lg-2">
            <a href="<?php echo e(route('ventas.index', ['fecha_desde' => $mesStr, 'fecha_hasta' => $hoyStr])); ?>"
               class="card border-0 shadow-sm text-center h-100 text-decoration-none metric-card">
                <div class="card-body py-3">
                    <div class="fs-2 fw-bold text-secondary"><?php echo e($ventasMes); ?></div>
                    <div class="small text-muted">Este mes</div>
                </div>
            </a>
        </div>

        <div class="col-6 col-md-4 col-lg-2">
            <a href="<?php echo e(route('ventas.index', ['fecha_desde' => $mesStr, 'fecha_hasta' => $hoyStr, 'estado' => 'pagada'])); ?>"
               class="card border-0 shadow-sm text-center h-100 text-decoration-none metric-card">
                <div class="card-body py-3">
                    <div class="fs-6 fw-bold text-success">$<?php echo e(number_format($ingresosMes, 0, ',', '.')); ?></div>
                    <div class="small text-muted">Ingresos mes</div>
                </div>
            </a>
        </div>

        <div class="col-6 col-md-4 col-lg-2">
            <a href="<?php echo e(route('ventas.index', ['estado' => 'pendiente'])); ?>"
               class="card border-0 shadow-sm text-center h-100 text-decoration-none metric-card">
                <div class="card-body py-3">
                    <div class="fs-6 fw-bold text-warning">$<?php echo e(number_format($pendienteCobro, 0, ',', '.')); ?></div>
                    <div class="small text-muted">Por cobrar</div>
                </div>
            </a>
        </div>

        <div class="col-6 col-md-4 col-lg-2">
            <a href="<?php echo e(route('pedidos.index')); ?>"
               class="card border-0 shadow-sm text-center h-100 text-decoration-none metric-card">
                <div class="card-body py-3">
                    <div class="fs-2 fw-bold text-danger"><?php echo e($pedidosPendientes); ?></div>
                    <div class="small text-muted">Pedidos activos</div>
                </div>
            </a>
        </div>

    </div>

    
    <?php if($comisionesTotales > 0): ?>
    <div class="alert border-0 rounded-3 mb-4 py-2 px-3 d-flex align-items-center gap-2"
         style="background:#fff8e1;">
        <span class="fs-5">💸</span>
        <span class="small">
            Hay <strong>$<?php echo e(number_format($comisionesTotales, 0, ',', '.')); ?></strong>
            en comisiones pendientes de pago a vendedores.
        </span>
        <a href="<?php echo e(route('vendedores.index')); ?>" class="btn btn-sm btn-warning ms-auto">Ver vendedores</a>
    </div>
    <?php endif; ?>

    <div class="row g-4">

        
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center pt-3">
                    <h6 class="mb-0 fw-bold">Ventas recientes</h6>
                    <a href="<?php echo e(route('ventas.index')); ?>" class="btn btn-sm btn-outline-primary">Ver todas</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">#</th>
                                    <th>Cliente</th>
                                    <th>Vendedor</th>
                                    <th>Total</th>
                                    <th>Estado</th>
                                    <th></th>
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
                                    <td>
                                        <a href="<?php echo e(route('ventas.show', $v->id)); ?>"
                                           class="btn btn-sm btn-outline-secondary py-0 px-2">Ver</a>
                                    </td>
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

        
        <div class="col-lg-5 d-flex flex-column gap-4">

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pt-3">
                    <h6 class="mb-0 fw-bold">Top productos vendidos</h6>
                </div>
                <div class="card-body pt-1">
                    <?php $__empty_1 = true; $__currentLoopData = $topProductos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div>
                            <div class="small fw-semibold"><?php echo e($tp->nombre_producto); ?></div>
                            <div class="text-muted" style="font-size:.75rem">$<?php echo e(number_format($tp->total_ingresos, 0, ',', '.')); ?></div>
                        </div>
                        <span class="badge bg-primary rounded-pill"><?php echo e($tp->total_vendido); ?> uds</span>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p class="text-muted small mb-0">Sin datos</p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pt-3 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold text-danger">⚠ Stock bajo (≤ 5)</h6>
                    <a href="<?php echo e(route('productos.index', ['stock_bajo' => 1])); ?>"
                       class="btn btn-sm btn-outline-danger">Ver todos</a>
                </div>
                <div class="card-body pt-1">
                    <?php $__empty_1 = true; $__currentLoopData = $stockBajo; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="small">
                            <span class="fw-semibold"><?php echo e($v->producto->nombre ?? '—'); ?></span>
                            <span class="text-muted"> · <?php echo e($v->nombre); ?></span>
                        </div>
                        <span class="badge <?php echo e($v->stock === 0 ? 'bg-danger' : 'bg-warning text-dark'); ?>">
                            <?php echo e($v->stock); ?> uds
                        </span>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p class="text-success small mb-0">✓ Todo el stock está OK</p>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>

    
    <div class="row g-3 mt-2">
        <div class="col-12"><h6 class="text-muted mb-1">Accesos rápidos</h6></div>
        <div class="col-6 col-md-3">
            <a href="<?php echo e(route('ventas.create')); ?>" class="btn btn-primary w-100">➕ Nueva venta</a>
        </div>
        <div class="col-6 col-md-3">
            <a href="<?php echo e(route('clientes.create')); ?>" class="btn btn-outline-secondary w-100">👤 Nuevo cliente</a>
        </div>
        <div class="col-6 col-md-3">
            <a href="<?php echo e(route('productos.index')); ?>" class="btn btn-outline-secondary w-100">📦 Productos</a>
        </div>
        <div class="col-6 col-md-3">
            <a href="<?php echo e(route('pedidos.index')); ?>" class="btn btn-outline-secondary w-100">🚚 Pedidos</a>
        </div>
    </div>

</div>


<?php else: ?>
<div class="container-fluid py-4">

    
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h1 class="mb-0">Bienvenido, <?php echo e($vendedor->nombre); ?> 👋</h1>
            <small class="text-muted">Tu panel personal · <?php echo e(now()->format('d/m/Y H:i')); ?></small>
        </div>
        <a href="<?php echo e(route('ventas.create')); ?>" class="btn btn-success">➕ Nueva venta</a>
    </div>

    
    <div class="row g-3 mb-4">

        <div class="col-6 col-md-3 col-lg">
            <div class="card border-0 shadow-sm text-center h-100">
                <div class="card-body py-3">
                    <div class="fs-2 fw-bold text-primary"><?php echo e($ventasHoy); ?></div>
                    <div class="small text-muted">Ventas hoy</div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3 col-lg">
            <div class="card border-0 shadow-sm text-center h-100">
                <div class="card-body py-3">
                    <div class="fs-2 fw-bold text-info"><?php echo e($ventasSemana); ?></div>
                    <div class="small text-muted">Esta semana</div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3 col-lg">
            <div class="card border-0 shadow-sm text-center h-100">
                <div class="card-body py-3">
                    <div class="fs-2 fw-bold text-secondary"><?php echo e($ventasMes); ?></div>
                    <div class="small text-muted">Este mes</div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3 col-lg">
            <div class="card border-0 shadow-sm text-center h-100">
                <div class="card-body py-3">
                    <div class="fw-bold text-success" style="font-size:1.1rem">
                        $<?php echo e(number_format($ingresosMes, 0, ',', '.')); ?>

                    </div>
                    <div class="small text-muted">Ingresos mes</div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3 col-lg">
            <a href="<?php echo e(route('pedidos.index')); ?>"
               class="card border-0 shadow-sm text-center h-100 text-decoration-none metric-card">
                <div class="card-body py-3">
                    <div class="fs-2 fw-bold text-danger"><?php echo e($pedidosActivos); ?></div>
                    <div class="small text-muted">Pedidos activos</div>
                </div>
            </a>
        </div>

    </div>

    
    <?php if($comisionPendiente > 0): ?>
    <div class="alert border-0 rounded-3 mb-4 py-2 px-3 d-flex align-items-center gap-3"
         style="background:linear-gradient(135deg,#e8f5e9,#f1f8e9);">
        <span class="fs-4">💸</span>
        <div>
            <div class="fw-semibold small">Tienes comisiones pendientes de cobro</div>
            <div class="fw-bold text-success">$<?php echo e(number_format($comisionPendiente, 0, ',', '.')); ?></div>
        </div>
        <a href="<?php echo e(route('vendedores.show', $vendedor->id)); ?>"
           class="btn btn-sm btn-success ms-auto">Ver mis comisiones</a>
    </div>
    <?php endif; ?>

    <div class="row g-4">

        
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center pt-3">
                    <h6 class="mb-0 fw-bold">Mis ventas recientes</h6>
                    <a href="<?php echo e(route('ventas.index')); ?>" class="btn btn-sm btn-outline-primary">Ver todas</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">#</th>
                                    <th>Cliente</th>
                                    <th>Total</th>
                                    <th>Pago</th>
                                    <th>Estado</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $ventasRecientes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td class="ps-3 text-muted small"><?php echo e($v->id); ?></td>
                                    <td><?php echo e($v->cliente->nombre ?? '—'); ?></td>
                                    <td><strong>$<?php echo e(number_format($v->total, 0, ',', '.')); ?></strong></td>
                                    <td>
                                        <?php if($v->pedido?->metodo_pago): ?>
                                            <span class="badge bg-light text-dark">
                                                <?php echo e(Str::ucfirst($v->pedido->metodo_pago)); ?>

                                            </span>
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
                                    <td>
                                        <a href="<?php echo e(route('ventas.show', $v->id)); ?>"
                                           class="btn btn-sm btn-outline-secondary py-0 px-2">Ver</a>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        Aún no tienes ventas registradas.<br>
                                        <a href="<?php echo e(route('ventas.create')); ?>" class="btn btn-sm btn-primary mt-2">
                                            ➕ Registrar primera venta
                                        </a>
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="col-lg-5 d-flex flex-column gap-4">

            
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pt-3 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold">💰 Mis comisiones</h6>
                    <a href="<?php echo e(route('vendedores.show', $vendedor->id)); ?>"
                       class="btn btn-sm btn-outline-secondary">Detalle</a>
                </div>
                <div class="card-body">
                    <div class="row text-center g-2 mb-3">
                        <div class="col-4">
                            <div class="fw-bold text-success" style="font-size:1rem">
                                $<?php echo e(number_format($comisionTotal, 0, ',', '.')); ?>

                            </div>
                            <div class="text-muted" style="font-size:.72rem">Total generado</div>
                        </div>
                        <div class="col-4">
                            <div class="fw-bold text-primary" style="font-size:1rem">
                                $<?php echo e(number_format($comisionCobrada, 0, ',', '.')); ?>

                            </div>
                            <div class="text-muted" style="font-size:.72rem">Ya cobrado</div>
                        </div>
                        <div class="col-4">
                            <div class="fw-bold text-warning" style="font-size:1rem">
                                $<?php echo e(number_format($comisionPendiente, 0, ',', '.')); ?>

                            </div>
                            <div class="text-muted" style="font-size:.72rem">Por cobrar</div>
                        </div>
                    </div>

                    <?php if($comisionTotal > 0): ?>
                    <?php $pct = min(round($comisionCobrada / $comisionTotal * 100), 100); ?>
                    <div class="comision-bar mb-1">
                        <div class="comision-bar-fill" style="width:<?php echo e($pct); ?>%"></div>
                    </div>
                    <div class="text-end text-muted" style="font-size:.72rem"><?php echo e($pct); ?>% cobrado</div>
                    <?php endif; ?>

                    
                    <?php if($ultimasComisiones->isNotEmpty()): ?>
                    <hr class="my-2">
                    <div class="small text-muted fw-semibold mb-2">Últimas comisiones</div>
                    <?php $__currentLoopData = $ultimasComisiones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $com): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="d-flex justify-content-between align-items-center py-1 border-bottom small">
                        <span class="text-muted">Venta #<?php echo e($com->venta_id); ?></span>
                        <div class="d-flex gap-2 align-items-center">
                            <span class="fw-semibold">$<?php echo e(number_format($com->monto_comision, 0, ',', '.')); ?></span>
                            <span class="badge bg-<?php echo e($com->estado === 'pagada' ? 'success' : ($com->estado === 'anulada' ? 'secondary' : 'warning text-dark')); ?>">
                                <?php echo e($com->estado); ?>

                            </span>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                </div>
            </div>

            
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pt-3">
                    <h6 class="mb-0 fw-bold">📦 Mis productos más vendidos</h6>
                </div>
                <div class="card-body pt-1">
                    <?php $__empty_1 = true; $__currentLoopData = $topProductos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div>
                            <div class="small fw-semibold"><?php echo e($tp->nombre_producto); ?></div>
                            <div class="text-muted" style="font-size:.75rem">
                                $<?php echo e(number_format($tp->total_ingresos, 0, ',', '.')); ?>

                            </div>
                        </div>
                        <span class="badge bg-primary rounded-pill"><?php echo e($tp->total_vendido); ?> uds</span>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p class="text-muted small mb-0">Aún no hay ventas registradas.</p>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>

    
    <div class="row g-3 mt-2">
        <div class="col-12"><h6 class="text-muted mb-1">Accesos rápidos</h6></div>
        <div class="col-6 col-md-3">
            <a href="<?php echo e(route('ventas.create')); ?>" class="btn btn-primary w-100">➕ Nueva venta</a>
        </div>
        <div class="col-6 col-md-3">
            <a href="<?php echo e(route('clientes.create')); ?>" class="btn btn-outline-secondary w-100">👤 Nuevo cliente</a>
        </div>
        <div class="col-6 col-md-3">
            <a href="<?php echo e(route('pedidos.index')); ?>" class="btn btn-outline-secondary w-100">🚚 Mis pedidos</a>
        </div>
        <div class="col-6 col-md-3">
            <a href="<?php echo e(route('vendedores.show', $vendedor->id)); ?>"
               class="btn btn-outline-success w-100">💸 Mis comisiones</a>
        </div>
    </div>

</div>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Usuario\Documents\My Web Sites\POS\Backend\POSRed\resources\views/dashboard.blade.php ENDPATH**/ ?>