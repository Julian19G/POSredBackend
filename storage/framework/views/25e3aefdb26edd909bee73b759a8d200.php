<?php $__env->startSection('content'); ?>
<div class="container">

    <?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show mt-3">
        <?php echo e(session('success')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-4 mt-3">
        <h1 class="mb-0">👥 Vendedores</h1>
        <a href="<?php echo e(route('vendedores.create')); ?>" class="btn btn-primary">➕ Nuevo Vendedor</a>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-bordered align-middle">
            <thead class="table-dark text-center">
                <tr>
                    <th>Nombre</th>
                    <th>Contacto</th>
                    <th>Instagram</th>
                    <th class="text-end">Comisión</th>
                    <th class="text-end">Ventas</th>
                    <th class="text-end">Total vendido</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $vendedores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
                <td class="fw-semibold"><?php echo e($v->nombre); ?></td>
                <td class="small">
                    <?php if($v->telefono): ?> 📞 <?php echo e($v->telefono); ?><br> <?php endif; ?>
                    <?php if($v->whatsapp): ?> 💬 <?php echo e($v->whatsapp); ?><br> <?php endif; ?>
                    <?php if($v->email): ?>    ✉️ <?php echo e($v->email); ?> <?php endif; ?>
                </td>
                <td class="small"><?php echo e($v->instagram ? '@'.$v->instagram : '—'); ?></td>
                <td class="text-end"><?php echo e($v->comision_porcentaje); ?>%</td>
                <td class="text-center"><?php echo e($v->ventas_count); ?></td>
                <td class="text-end">$<?php echo e(number_format($v->ventas_sum_total ?? 0, 0, ',', '.')); ?></td>
                <td class="text-center">
                    <span class="badge <?php echo e($v->activo ? 'bg-success' : 'bg-secondary'); ?>">
                        <?php echo e($v->activo ? 'Activo' : 'Inactivo'); ?>

                    </span>
                </td>
                <td class="text-center">
                    <a href="<?php echo e(route('vendedores.show', $v)); ?>" class="btn btn-info btn-sm">Ver</a>
                    <a href="<?php echo e(route('vendedores.edit', $v)); ?>" class="btn btn-warning btn-sm">Editar</a>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
                <td colspan="8" class="text-center text-muted py-4">No hay vendedores registrados.</td>
            </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Usuario\Documents\My Web Sites\POS\Backend\POSRed\resources\views/vendedores/index.blade.php ENDPATH**/ ?>