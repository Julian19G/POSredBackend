
<?php $__env->startSection('content'); ?>
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>📦 Pedidos</h2>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Cliente</th>
                        <th>Total</th>
                        <th>Estado pedido</th>
                        <th>Pago</th>
                        <th>Domicilio</th>
                        <th>Fecha</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $pedidos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pedido): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="text-muted small"><?php echo e($pedido->id); ?></td>
                        <td>
                            <strong><?php echo e($pedido->venta->cliente->nombre); ?></strong>
                            <br><small class="text-muted"><?php echo e($pedido->venta->cliente->telefono); ?></small>
                        </td>
                        <td>$<?php echo e(number_format($pedido->venta->total, 2, ',', '.')); ?></td>
                        <td>
                            <span class="badge bg-<?php echo e($pedido->badge_estado); ?>">
                                <?php echo e($pedido->label_estado); ?>

                            </span>
                        </td>
                        <td>
                            <span class="badge bg-<?php echo e($pedido->estado_pago === 'pagado' ? 'success' : 'secondary'); ?>">
                                <?php echo e(ucfirst($pedido->estado_pago)); ?>

                            </span>
                        </td>
                        <td>
                            <?php if($pedido->venta->envio): ?>
                                <span class="badge bg-info text-dark">🚚 Sí</span>
                            <?php else: ?>
                                <span class="text-muted small">No</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-muted small"><?php echo e($pedido->created_at->format('d/m/Y')); ?></td>
                        <td>
                            <a href="<?php echo e(route('pedidos.show', $pedido->id)); ?>"
                               class="btn btn-sm btn-outline-primary">Ver</a>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="8" class="text-center text-muted py-4">No hay pedidos aún.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3"><?php echo e($pedidos->links()); ?></div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Usuario\Documents\My Web Sites\POS\Backend\POSRed\resources\views/pedidos/index.blade.php ENDPATH**/ ?>