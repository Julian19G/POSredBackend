<?php $__env->startSection('content'); ?>
<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-3 mt-3">
        <h1 class="mb-0">Domiciliarios</h1>
        <a href="<?php echo e(route('domiciliarios.create')); ?>" class="btn btn-primary">➕ Nuevo Domiciliario</a>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-bordered align-middle">
            <thead class="table-dark text-center">
                <tr>
                    <th>ID</th>
                    <th class="text-start">Nombre</th>
                    <th>Teléfono</th>
                    <th>Vehículo</th>
                    <th>Email</th>
                    <th>Domicilios</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $domiciliarios; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="text-center">
                    <td>#<?php echo e($d->id); ?></td>
                    <td class="text-start fw-semibold"><?php echo e($d->nombre); ?></td>
                    <td><?php echo e($d->telefono ?? '—'); ?></td>
                    <td><?php echo e($d->vehiculoLabel()); ?></td>
                    <td class="text-muted small"><?php echo e($d->user->email); ?></td>
                    <td><?php echo e($d->domicilios_count); ?></td>
                    <td>
                        <span class="badge <?php echo e($d->activo ? 'bg-success' : 'bg-secondary'); ?>">
                            <?php echo e($d->activo ? 'Activo' : 'Inactivo'); ?>

                        </span>
                    </td>
                    <td>
                        <a href="<?php echo e(route('domiciliarios.show', $d)); ?>" class="btn btn-sm btn-info">Ver</a>
                        <a href="<?php echo e(route('domiciliarios.edit', $d)); ?>" class="btn btn-sm btn-warning">Editar</a>
                        <form action="<?php echo e(route('domiciliarios.destroy', $d)); ?>" method="POST" class="d-inline">
                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                            <button class="btn btn-sm btn-danger"
                                data-confirm="Se desactivará este domiciliario. ¿Continuar?">Eliminar</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="8" class="text-center text-muted py-4">No hay domiciliarios registrados.</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php echo e($domiciliarios->links()); ?>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Usuario\Documents\My Web Sites\POS\Backend\POSRed\resources\views/domiciliarios/index.blade.php ENDPATH**/ ?>