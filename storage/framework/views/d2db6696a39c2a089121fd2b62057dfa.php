

<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>🎟️ Descuentos</h1>
        <a href="<?php echo e(route('descuentos.create')); ?>" class="btn btn-primary">
            + Nuevo Descuento
        </a>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
    <?php endif; ?>

    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>Nombre</th>
                <th>Código</th>
                <th>Tipo</th>
                <th>Valor</th>
                <th>Activo</th>
                <th>Vigencia</th>
                <th class="text-center">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $descuentos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $descuento): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($descuento->nombre); ?></td>
                    <td><strong><?php echo e($descuento->codigo); ?></strong></td>
                    <td><?php echo e(ucfirst($descuento->tipo)); ?></td>
                    <td>
                        <?php echo e($descuento->tipo === 'porcentaje' ? $descuento->valor.'%' : '$'.number_format($descuento->valor, 0)); ?>

                    </td>
                    <td>
                        <span class="badge <?php echo e($descuento->activo ? 'bg-success' : 'bg-secondary'); ?>">
                            <?php echo e($descuento->activo ? 'Activo' : 'Inactivo'); ?>

                        </span>
                    </td>
                    <td>
                        <?php echo e(optional($descuento->fecha_inicio)->format('Y-m-d') ?? '—'); ?>

                        →
                        <?php echo e(optional($descuento->fecha_fin)->format('Y-m-d') ?? '—'); ?>


                    </td>
                    <td class="text-center">
                        <a href="<?php echo e(route('descuentos.show', $descuento)); ?>" class="btn btn-sm btn-info">Ver</a>
                        <a href="<?php echo e(route('descuentos.edit', $descuento)); ?>" class="btn btn-sm btn-warning">Editar</a>
                        <form action="<?php echo e(route('descuentos.destroy', $descuento)); ?>" method="POST" class="d-inline">
                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                            <button class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar descuento?')">
                                Eliminar
                            </button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="7" class="text-center text-muted">No hay descuentos registrados</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <?php echo e($descuentos->links()); ?>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Usuario\Documents\My Web Sites\POS\Backend\POSRed\resources\views/descuentos/index.blade.php ENDPATH**/ ?>