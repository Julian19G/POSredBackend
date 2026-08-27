

<?php $__env->startSection('title', 'Detalle del Sabor'); ?>

<?php $__env->startSection('content'); ?>
<div class="container">

    <h1 class="mb-4">Detalle del Sabor</h1>

    <div class="card shadow">
        <div class="card-body">

            <h3 class="card-title"><?php echo e($sabor->nombre); ?></h3>

            <p><strong>Descripción:</strong><br>
                <?php echo e($sabor->descripcion ?? 'Sin descripción'); ?>

            </p>

            <p><strong>Intensidad:</strong> <?php echo e($sabor->intensidad); ?></p>

            <p><strong>Estado:</strong>
                <?php if($sabor->activo): ?>
                    <span class="badge bg-success">Activo</span>
                <?php else: ?>
                    <span class="badge bg-danger">Inactivo</span>
                <?php endif; ?>
            </p>

            <?php if($sabor->imagen): ?>
                <div class="my-3">
                    <strong>Imagen:</strong><br>
                    <img src="<?php echo e(asset('storage/' . $sabor->imagen)); ?>"
                         alt="imagen sabor"
                         class="img-fluid rounded"
                         width="250">
                </div>
            <?php endif; ?>

            <a href="<?php echo e(route('sabores.index')); ?>" class="btn btn-secondary mt-3">Volver</a>
            <a href="<?php echo e(route('sabores.edit', $sabor->id)); ?>" class="btn btn-warning mt-3">Editar</a>

        </div>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Usuario\Documents\My Web Sites\POS\Backend\POSRed\resources\views\sabores\show.blade.php ENDPATH**/ ?>