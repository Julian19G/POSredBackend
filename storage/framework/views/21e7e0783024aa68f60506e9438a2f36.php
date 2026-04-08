

<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="d-flex justify-content-between mb-4">
        <h1>🚚 Domicilios</h1>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
    <?php endif; ?>

    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Venta #</th>
                <th>Cliente</th>
                <th>Dirección</th>
                <th>Estado</th>
                <th>Costo envío</th>
                <th>Fecha envío / entrega</th>
                <th class="text-center">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $domicilios; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $domicilio): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                    $cliente = $domicilio->venta->cliente ?? null;
                    $estado  = $domicilio->estado ?? 'pendiente';
                    $badgeColor = match($estado) {
                        'pendiente'  => 'secondary',
                        'enviado'    => 'primary',
                        'entregado'  => 'success',
                        'cancelado'  => 'danger',
                        default      => 'info',
                    };
                ?>
                <tr>
                    <td><?php echo e($domicilio->id); ?></td>
                    <td>
                        <a href="<?php echo e(route('ventas.show', $domicilio->venta_id)); ?>">
                            #<?php echo e($domicilio->venta_id); ?>

                        </a>
                    </td>
                    <td><?php echo e($cliente->nombre ?? '—'); ?></td>
                    <td><?php echo e($domicilio->direccion); ?></td>
                    <td>
                        <span class="badge bg-<?php echo e($badgeColor); ?>">
                            <?php echo e(ucfirst($estado)); ?>

                        </span>
                    </td>
                    <td>$<?php echo e(number_format($domicilio->costo_envio ?? 0, 0, ',', '.')); ?></td>
                    <td>
                        <?php echo e(optional($domicilio->fecha_envio)->format('d/m/Y') ?? '—'); ?>

                        /
                        <?php echo e(optional($domicilio->fecha_entrega)->format('d/m/Y') ?? '—'); ?>

                    </td>
                    <td class="text-center">
                        <a href="<?php echo e(route('domicilios.show', $domicilio)); ?>"
                           class="btn btn-sm btn-info">Ver</a>
                        <a href="<?php echo e(route('domicilios.edit', $domicilio)); ?>"
                           class="btn btn-sm btn-warning">Editar</a>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="8" class="text-center text-muted">
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