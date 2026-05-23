<?php $__env->startSection('content'); ?>
<style>
    .metric-card {
        cursor: pointer;
        transition: transform .15s, box-shadow .15s;
        color: inherit;
    }
    .metric-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 .5rem 1.5rem rgba(0,0,0,.12) !important;
    }
    .metric-card:active {
        transform: translateY(0);
    }
</style>
<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Dashboard</h1>
        <small class="text-muted"><?php echo e(now()->format('d/m/Y H:i')); ?></small>
    </div>

    
    <div class="row g-3 mb-4">

        <?php
            $hoyStr      = now()->toDateString();
            $semanaStr   = now()->startOfWeek()->toDateString();
            $mesStr      = now()->startOfMonth()->toDateString();
        ?>

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
                    <div class="fs-5 fw-bold text-success">$<?php echo e(number_format($ingresosMes, 0, ',', '.')); ?></div>
                    <div class="small text-muted">Ingresos mes</div>
                </div>
            </a>
        </div>

        <div class="col-6 col-md-4 col-lg-2">
            <a href="<?php echo e(route('ventas.index', ['estado' => 'pendiente'])); ?>"
               class="card border-0 shadow-sm text-center h-100 text-decoration-none metric-card">
                <div class="card-body py-3">
                    <div class="fs-5 fw-bold text-warning">$<?php echo e(number_format($pendienteCobro, 0, ',', '.')); ?></div>
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
                                            <span class="badge bg-light text-dark"><?php echo e(Str::ucfirst($v->pedido->metodo_pago)); ?></span>
                                        <?php else: ?>
                                            <span class="text-muted small">—</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php switch($v->estado):
                                            case ('pagada'): ?> <span class="badge bg-success">Pagada</span> <?php break; ?>
                                            <?php case ('pendiente'): ?> <span class="badge bg-warning text-dark">Pendiente</span> <?php break; ?>
                                            <?php case ('cancelada'): ?> <span class="badge bg-danger">Cancelada</span> <?php break; ?>
                                        <?php endswitch; ?>
                                    </td>
                                    <td>
                                        <a href="<?php echo e(route('ventas.show', $v->id)); ?>" class="btn btn-xs btn-sm btn-outline-secondary py-0 px-2">Ver</a>
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
                    <a href="<?php echo e(route('productos.index', ['stock_bajo' => 1])); ?>" class="btn btn-sm btn-outline-danger">Ver todos</a>
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
        <div class="col-12">
            <h6 class="text-muted mb-2">Accesos rápidos</h6>
        </div>
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Usuario\Documents\My Web Sites\POS\Backend\POSRed\resources\views/dashboard.blade.php ENDPATH**/ ?>