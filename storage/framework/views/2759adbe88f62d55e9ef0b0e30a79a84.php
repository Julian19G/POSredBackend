

<?php $__env->startSection('content'); ?>
<div class="container">

    
    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    
    <?php if($errors->any()): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <?php echo e($errors->first()); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="mb-0">📦 Listado de Ventas</h1>

    <div class="d-flex align-items-center gap-3">
                <a href="<?php echo e(route('ventas.create')); ?>" class="btn btn-primary">
            ➕ Nueva Venta
        </a>
        <span class="text-muted">
            Total de ventas: <?php echo e($ventas->total()); ?>

        </span>


    </div>
</div>

    <div class="table-responsive">
        <table class="table table-hover table-bordered align-middle">
            <thead class="table-dark text-center">
                <tr>
                    <th>ID</th>
                    <th>Cliente</th>
                    <th>Subtotal</th>
                    <th>descuentos</th>
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
                    <td><?php echo e($venta->cliente->nombre ?? '—'); ?></td>

                    <td>$<?php echo e(number_format($venta->subtotal, 2, ',', '.')); ?></td>

                    <td>
                        <?php if($venta->descuentos_manual > 0): ?>
                            <span class="text-danger">
                                -$<?php echo e(number_format($venta->descuentos_manual, 2, ',', '.')); ?>

                            </span>
                            <?php if($venta->motivo_descuentos): ?>
                                <br><small>(<?php echo e($venta->motivo_descuentos); ?>)</small>
                            <?php endif; ?>
                        <?php else: ?>
                            <span class="text-muted">—</span>
                        <?php endif; ?>
                    </td>

                    <td>
                        <strong>$<?php echo e(number_format($venta->total, 2, ',', '.')); ?></strong>
                    </td>

                    
                    <td>
                        <?php if($venta->costo_envio > 0): ?>
                            <span class="badge bg-primary">Envío</span><br>
                            $<?php echo e(number_format($venta->costo_envio, 2, ',', '.')); ?>

                            <?php if($venta->direccion_envio): ?>
                                <br><small><?php echo e($venta->direccion_envio); ?></small>
                            <?php endif; ?>
                        <?php else: ?>
                            <span class="badge bg-secondary">No aplica</span>
                        <?php endif; ?>
                    </td>

                    <td>
                        <?php switch($venta->estado):
                            case ('pagada'): ?>
                                <span class="badge bg-success">Pagada</span>
                                <?php break; ?>
                            <?php case ('pendiente'): ?>
                                <span class="badge bg-warning text-dark">Pendiente</span>
                                <?php break; ?>
                            <?php case ('cancelada'): ?>
                                <span class="badge bg-danger">Cancelada</span>
                                <?php break; ?>
                            <?php default: ?>
                                <span class="badge bg-secondary">Desconocido</span>
                        <?php endswitch; ?>
                    </td>

                    <td><?php echo e($venta->created_at?->format('d/m/Y H:i')); ?></td>

                    <td>
                        <a href="<?php echo e(route('ventas.show', $venta->id)); ?>" class="btn btn-sm btn-info">👁 Ver</a>
                        <a href="<?php echo e(route('ventas.edit', $venta->id)); ?>" class="btn btn-sm btn-warning">✏️ Editar</a>

                        <form action="<?php echo e(route('ventas.destroy', $venta->id)); ?>" method="POST" class="d-inline">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button class="btn btn-sm btn-danger"
                                onclick="return confirm('¿Eliminar esta venta?')">
                                🗑
                            </button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="9" class="text-center text-muted py-4">
                        No hay ventas registradas.
                    </td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

    
    <?php if($ventas->hasPages()): ?>
        <div class="mt-4">
            <div class="d-flex justify-content-center">
                <?php echo e($ventas->links()); ?>

            </div>

            <small class="text-muted d-block text-center mt-2">
                Mostrando <?php echo e($ventas->firstItem()); ?> a <?php echo e($ventas->lastItem()); ?>

                de <?php echo e($ventas->total()); ?> ventas
            </small>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Usuario\Documents\My Web Sites\POS\Backend\POSRed\resources\views/ventas/index.blade.php ENDPATH**/ ?>