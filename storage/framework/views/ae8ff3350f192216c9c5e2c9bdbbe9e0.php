

<?php $__env->startSection('title', 'Detalle del Color'); ?>

<?php $__env->startSection('content'); ?>
<div class="container">

    <h1 class="mb-4">Detalle del color</h1>

    <div class="card shadow">
        <div class="card-body">

            <h3><?php echo e($color->nombre); ?></h3>

            <p>
                <strong>Código HEX:</strong>
                <?php echo e($color->codigo_hex ?? 'No definido'); ?>

            </p>

            <?php if($color->codigo_hex): ?>
            <p><strong>Vista del color:</strong></p>
            <div 
                style="
                    width:120px;
                    height:120px;
                    background: <?php echo e($color->codigo_hex); ?>;
                    border-radius:15px;
                    border: 1px solid #999;">
            </div>
            <?php endif; ?>

            <p class="mt-3">
                <strong>Descripción:</strong><br>
                <?php echo e($color->descripcion ?? 'Sin descripción'); ?>

            </p>

            <p>
                <strong>Estado:</strong>
                <?php if($color->activo): ?>
                    <span class="badge bg-success">Activo</span>
                <?php else: ?>
                    <span class="badge bg-danger">Inactivo</span>
                <?php endif; ?>
            </p>

            <div class="mt-3">
                <a href="<?php echo e(route('colores.index')); ?>" class="btn btn-secondary">Volver</a>
                <a href="<?php echo e(route('colores.edit', $color->id)); ?>" class="btn btn-warning">Editar</a>
            </div>

        </div>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Usuario\Documents\My Web Sites\POS\Backend\POSRed\resources\views\colores\show.blade.php ENDPATH**/ ?>