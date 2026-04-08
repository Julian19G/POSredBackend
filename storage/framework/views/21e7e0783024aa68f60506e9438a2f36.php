

<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="d-flex justify-content-between mb-4">
        <h1>🚚 Domicilios</h1>
        <a href="<?php echo e(route('domicilios.create')); ?>" class="btn btn-primary">
            + Nuevo domicilio
        </a>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
    <?php endif; ?>

    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Cliente</th>
                <th>Dirección</th>
                <th>Estado</th>
                <th>Costo</th>
                <th>Fechas</th>
                <th class="text-center">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $domicilios; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $domicilio): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($domicilio->id); ?></td>
                    <td><?php echo e($domicilio->cliente->nombre ?? '—'); ?></td>
                    <td><?php echo e($domicilio->direccion); ?></td>
                    <td>
                        <span class="badge bg-info">
                            <?php echo e(ucfirst($domicilio->estado)); ?>

                        </span>
                    </td>
                    <td>$<?php echo e(number_format($domicilio->costo_envio ?? 0, 0)); ?></td>
                    <td>
                        <?php echo e(optional($domicilio->fecha_envio)->format('Y-m-d') ?? '—'); ?>

                        /
                        <?php echo e(optional($domicilio->fecha_entrega)->format('Y-m-d') ?? '—'); ?>

                    </td>
                    <td class="text-center">
                        <a href="<?php echo e(route('domicilios.show', $domicilio)); ?>" class="btn btn-sm btn-info">Ver</a>
                        <a href="<?php echo e(route('domicilios.edit', $domicilio)); ?>" class="btn btn-sm btn-warning">Editar</a>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="7" class="text-center text-muted">
                        No hay domicilios registrados
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <?php echo e($domicilios->links()); ?>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Usuario\Documents\My Web Sites\POS\Backend\POSRed\resources\views/domicilios/index.blade.php ENDPATH**/ ?>