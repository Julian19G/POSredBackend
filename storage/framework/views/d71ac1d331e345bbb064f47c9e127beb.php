<?php $__env->startSection('content'); ?>
<div class="container">

    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show mt-3">
            <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    <?php if($errors->any()): ?>
        <div class="alert alert-danger alert-dismissible fade show mt-3">
            <?php echo e($errors->first()); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-3 mt-3">
        <h1 class="mb-0">Ventas</h1>
        <a href="<?php echo e(route('ventas.create')); ?>" class="btn btn-primary">➕ Nueva Venta</a>
    </div>

    
    <form method="GET" action="<?php echo e(route('ventas.index')); ?>" class="card shadow-sm border-0 rounded-3 mb-4">
        <div class="card-body py-3">
            <div class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small mb-1">Buscar cliente</label>
                    <input type="text" name="buscar" class="form-control form-control-sm"
                           placeholder="Nombre del cliente…" value="<?php echo e(request('buscar')); ?>">
                </div>
                <div class="col-md-2">
                    <label class="form-label small mb-1">Estado</label>
                    <select name="estado" class="form-select form-select-sm">
                        <option value="">Todos</option>
                        <option value="pendiente"  <?php echo e(request('estado') === 'pendiente'  ? 'selected' : ''); ?>>Pendiente</option>
                        <option value="pagada"     <?php echo e(request('estado') === 'pagada'     ? 'selected' : ''); ?>>Pagada</option>
                        <option value="cancelada"  <?php echo e(request('estado') === 'cancelada'  ? 'selected' : ''); ?>>Cancelada</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small mb-1">Desde</label>
                    <input type="date" name="fecha_desde" class="form-control form-control-sm"
                           value="<?php echo e(request('fecha_desde')); ?>">
                </div>
                <div class="col-md-2">
                    <label class="form-label small mb-1">Hasta</label>
                    <input type="date" name="fecha_hasta" class="form-control form-control-sm"
                           value="<?php echo e(request('fecha_hasta')); ?>">
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-sm btn-primary flex-fill">Filtrar</button>
                    <a href="<?php echo e(route('ventas.index')); ?>" class="btn btn-sm btn-outline-secondary flex-fill">Limpiar</a>
                </div>
            </div>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-hover table-bordered align-middle">
            <thead class="table-dark text-center">
                <tr>
                    <th>ID</th>
                    <th>Cliente</th>
                    <th>Vendedor</th>
                    <th>Subtotal</th>
                    <th>Descuento</th>
                    <th>Total</th>
                    <th>Envío</th>
                    <th>Estado</th>
                    <th>Fecha</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $ventas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $venta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="text-center">
                    <td>#<?php echo e($venta->id); ?></td>
                    <td class="text-start"><?php echo e($venta->cliente->nombre ?? '—'); ?></td>
                    <td class="text-start"><?php echo e($venta->vendedor->nombre ?? '—'); ?></td>
                    <td>$<?php echo e(number_format($venta->subtotal, 0, ',', '.')); ?></td>
                    <td>
                        <?php if($venta->descuento_manual > 0): ?>
                            <span class="text-danger">-$<?php echo e(number_format($venta->descuento_manual, 0, ',', '.')); ?></span>
                        <?php else: ?>
                            <span class="text-muted">—</span>
                        <?php endif; ?>
                    </td>
                    <td><strong>$<?php echo e(number_format($venta->total, 0, ',', '.')); ?></strong></td>
                    <td>
                        <?php if($venta->costo_envio > 0): ?>
                            <span class="badge bg-primary">Envío</span><br>
                            <small>$<?php echo e(number_format($venta->costo_envio, 0, ',', '.')); ?></small>
                        <?php else: ?>
                            <span class="badge bg-light text-dark">No</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php switch($venta->estado):
                            case ('pagada'): ?>    <span class="badge bg-success">Pagada</span>    <?php break; ?>
                            <?php case ('pendiente'): ?> <span class="badge bg-warning text-dark">Pendiente</span> <?php break; ?>
                            <?php case ('cancelada'): ?> <span class="badge bg-danger">Cancelada</span>  <?php break; ?>
                        <?php endswitch; ?>
                    </td>
                    <td><small><?php echo e($venta->created_at?->format('d/m/Y H:i')); ?></small></td>
                    <td>
                        <a href="<?php echo e(route('ventas.show', $venta->id)); ?>" class="btn btn-sm btn-info">Ver</a>
                        <a href="<?php echo e(route('ventas.recibo', $venta->id)); ?>" class="btn btn-sm btn-outline-secondary" target="_blank">🖨</a>
                        <?php if(auth()->user()->isAdmin()): ?>
                            <a href="<?php echo e(route('ventas.edit', $venta->id)); ?>" class="btn btn-sm btn-warning">Editar</a>
                            <form action="<?php echo e(route('ventas.destroy', $venta->id)); ?>" method="POST" class="d-inline">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button class="btn btn-sm btn-danger"
                                    data-confirm="Esta acción eliminará la venta permanentemente.">🗑</button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="10" class="text-center text-muted py-4">No hay ventas registradas.</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if($ventas->hasPages()): ?>
        <div class="d-flex justify-content-between align-items-center mt-3">
            <?php echo e($ventas->links()); ?>

            <small class="text-muted">
                Mostrando <?php echo e($ventas->firstItem()); ?>–<?php echo e($ventas->lastItem()); ?> de <?php echo e($ventas->total()); ?>

            </small>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Usuario\Documents\My Web Sites\POS\Backend\POSRed\resources\views\ventas\index.blade.php ENDPATH**/ ?>