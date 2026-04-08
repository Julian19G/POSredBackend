

<?php $__env->startSection('title', 'Lista de Colores'); ?>

<?php $__env->startSection('content'); ?>
<div class="container">

    <h1 class="mb-4">Colores</h1>

    <a href="<?php echo e(route('colores.create')); ?>" class="btn btn-primary mb-3">Crear nuevo color</a>

    <div class="card shadow">
        <div class="card-body">

            <?php if($colores->count()): ?>
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Código HEX</th>
                        <th>Color</th>
                        <th>Activo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    <?php $__currentLoopData = $colores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $color): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($color->id); ?></td>
                        <td><?php echo e($color->nombre); ?></td>
                        <td><?php echo e($color->codigo_hex ?? '—'); ?></td>

                        <td>
                            <?php if($color->codigo_hex): ?>
                                <div style="width:35px; height:35px; border-radius:5px; background: <?php echo e($color->codigo_hex); ?>; border: 1px solid #999;"></div>
                            <?php else: ?>
                                —
                            <?php endif; ?>
                        </td>

                        <td>
                            <?php if($color->activo): ?>
                                <span class="badge bg-success">Activo</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Inactivo</span>
                            <?php endif; ?>
                        </td>

                        <td>
                            <a href="<?php echo e(route('colores.show', $color->id)); ?>" class="btn btn-info btn-sm">Ver</a>
                            <a href="<?php echo e(route('colores.edit', $color->id)); ?>" class="btn btn-warning btn-sm">Editar</a>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
            <?php else: ?>
                <p class="text-muted">No hay colores registrados aún.</p>
            <?php endif; ?>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Usuario\Documents\My Web Sites\POS\Backend\POSRed\resources\views/colores/index.blade.php ENDPATH**/ ?>