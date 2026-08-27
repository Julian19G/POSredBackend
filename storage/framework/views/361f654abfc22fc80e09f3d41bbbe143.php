

<?php $__env->startSection('title', 'Detalle del Efecto'); ?>

<?php $__env->startSection('content'); ?>
<div class="container">

    <h1 class="mb-4">Detalle del Efecto</h1>

    <div class="card shadow">
        <div class="card-body">

            <h3><?php echo e($efecto->nombre); ?></h3>

            <p><strong>Descripción:</strong><br>
                <?php echo e($efecto->descripcion ?? 'Sin descripción'); ?>

            </p>

            <p>
                <strong>Tipo:</strong>
                <span class="badge 
                    <?php if($efecto->tipo=='positivo'): ?> bg-success 
                    <?php elseif($efecto->tipo=='negativo'): ?> bg-danger 
                    <?php else: ?> bg-secondary <?php endif; ?>
                ">
                    <?php echo e(ucfirst($efecto->tipo)); ?>

                </span>
            </p>

            <p>
                <strong>Estado:</strong>
                <?php if($efecto->activo): ?>
                    <span class="badge bg-success">Activo</span>
                <?php else: ?>
                    <span class="badge bg-danger">Inactivo</span>
                <?php endif; ?>
            </p>

            <?php if($efecto->imagen): ?>
                <p><strong>Imagen:</strong></p>
                <img src="<?php echo e(asset('storage/'.$efecto->imagen)); ?>" width="250" class="rounded">
            <?php endif; ?>

            <div class="mt-3">
                <a href="<?php echo e(route('efectos.index')); ?>" class="btn btn-secondary">Volver</a>
                <a href="<?php echo e(route('efectos.edit', $efecto->id)); ?>" class="btn btn-warning">Editar</a>
            </div>

        </div>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Usuario\Documents\My Web Sites\POS\Backend\POSRed\resources\views\efectos\show.blade.php ENDPATH**/ ?>