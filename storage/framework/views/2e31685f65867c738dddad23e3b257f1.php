

<?php $__env->startSection('content'); ?>
<div class="container">
    <h1 class="mb-4">👁️ Detalle del descuentos</h1>

    <div class="card">
        <div class="card-body">
            <p><strong>Nombre:</strong> <?php echo e($descuento->nombre); ?></p>
            <p><strong>Código:</strong> <?php echo e($descuento->codigo); ?></p>
            <p><strong>Tipo:</strong> <?php echo e(ucfirst($descuento->tipo)); ?></p>
            <p><strong>Valor:</strong>
                <?php echo e($descuento->tipo === 'porcentaje' ? $descuento->valor.'%' : '$'.number_format($descuento->valor, 0)); ?>

            </p>
            <p><strong>Activo:</strong> <?php echo e($descuento->activo ? 'Sí' : 'No'); ?></p>
            <p><strong>Fecha inicio:</strong> <?php echo e($descuento->fecha_inicio->format('Y-m-d')); ?></p>
            <p><strong>Fecha fin:</strong> <?php echo e($descuento->fecha_fin->format('Y-m-d')); ?></p>
            <p><strong>Uso máximo:</strong> <?php echo e($descuento->uso_maximo ?? 'Ilimitado'); ?></p>
            <p><strong>Uso por cliente:</strong> <?php echo e($descuento->uso_cliente_maximo ?? 'Ilimitado'); ?></p>
        </div>
    </div>

    <div class="mt-3">
        <a href="<?php echo e(route('descuentos.edit', $descuento)); ?>" class="btn btn-warning">Editar</a>
        <a href="<?php echo e(route('descuentos.index')); ?>" class="btn btn-secondary">Volver</a>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Usuario\Documents\My Web Sites\POS\Backend\POSRed\resources\views/descuentos/show.blade.php ENDPATH**/ ?>