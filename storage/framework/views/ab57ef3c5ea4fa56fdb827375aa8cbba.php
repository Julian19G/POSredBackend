

<?php $__env->startSection('title', 'Lista de Efectos'); ?>

<?php $__env->startSection('content'); ?>
<div class="container">

    <h1 class="mb-4">Efectos</h1>

    <a href="<?php echo e(route('efectos.create')); ?>" class="btn btn-primary mb-3">Crear nuevo efecto</a>

    <div class="card shadow">
        <div class="card-body">

            <?php if($efectos->count() > 0): ?>
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Tipo</th>
                        <th>Activo</th>
                        <th>Imagen</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    <?php $__currentLoopData = $efectos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $efecto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($efecto->id); ?></td>
                        <td><?php echo e($efecto->nombre); ?></td>
                        <td>
                            <span class="badge 
                                <?php if($efecto->tipo == 'positivo'): ?> bg-success
                                <?php elseif($efecto->tipo == 'negativo'): ?> bg-danger
                                <?php else: ?> bg-secondary <?php endif; ?>
                            ">
                                <?php echo e(ucfirst($efecto->tipo)); ?>

                            </span>
                        </td>

                        <td>
                            <?php if($efecto->activo): ?>
                                <span class="badge bg-success">Activo</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Inactivo</span>
                            <?php endif; ?>
                        </td>

                        <td>
                            <?php if($efecto->imagen): ?>
                                <img src="<?php echo e(asset('storage/'.$efecto->imagen)); ?>" width="50" class="rounded">
                            <?php else: ?>
                                —
                            <?php endif; ?>
                        </td>

                        <td>
                            <a href="<?php echo e(route('efectos.show', $efecto->id)); ?>" class="btn btn-info btn-sm">Ver</a>
                            <a href="<?php echo e(route('efectos.edit', $efecto->id)); ?>" class="btn btn-warning btn-sm">Editar</a>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>

            <?php else: ?>
                <p class="text-muted">No hay efectos registrados aún.</p>
            <?php endif; ?>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Usuario\Documents\My Web Sites\POS\Backend\POSRed\resources\views/efectos/index.blade.php ENDPATH**/ ?>