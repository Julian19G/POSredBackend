

<?php $__env->startSection('content'); ?>
<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Listado de Sabores</h1>

        <a href="<?php echo e(route('sabores.create')); ?>" class="btn btn-primary shadow-sm">
            + Crear Sabor
        </a>
    </div>

    
    <?php if(session('success')): ?>
        <div class="alert alert-success">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    
    <div class="card shadow-sm">
        <div class="card-body p-0">

            <table class="table table-striped table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Imagen</th>
                        <th>Nombre</th>
                        <th>Intensidad</th>
                        <th>Estado</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $sabores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sabor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($sabor->id); ?></td>

                            <td>
                                <?php if($sabor->imagen): ?>
                                    <img src="<?php echo e(asset('storage/' . $sabor->imagen)); ?>"
                                         alt="imagen sabor"
                                         class="img-thumbnail"
                                         style="width: 60px; height: 60px; object-fit: cover;">
                                <?php else: ?>
                                    <span class="text-muted fst-italic">Sin imagen</span>
                                <?php endif; ?>
                            </td>

                            <td class="fw-semibold"><?php echo e($sabor->nombre); ?></td>

                            <td>
                                <span class="badge bg-purple bg-opacity-25 text-dark">
                                    <?php echo e($sabor->intensidad); ?>

                                </span>
                            </td>

                            <td>
                                <?php if($sabor->activo): ?>
                                    <span class="badge bg-success">Activo</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Inactivo</span>
                                <?php endif; ?>
                            </td>

                            <td class="text-end">

                                <a href="<?php echo e(route('sabores.show', $sabor)); ?>"
                                   class="btn btn-sm btn-info text-white me-1">
                                   Ver
                                </a>

                                <a href="<?php echo e(route('sabores.edit', $sabor)); ?>"
                                   class="btn btn-sm btn-warning me-1">
                                   Editar
                                </a>

                                <form action="<?php echo e(route('sabores.destroy', $sabor)); ?>"
                                      method="POST"
                                      class="d-inline"
                                      onsubmit="return confirm('¿Seguro que deseas eliminar este sabor?')">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button class="btn btn-sm btn-danger">
                                        Eliminar
                                    </button>
                                </form>

                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                No hay sabores registrados.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>

            </table>

        </div>
    </div>

    
    <div class="mt-3">
        <?php echo e($sabores->links()); ?>

    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Usuario\Documents\My Web Sites\POS\Backend\POSRed\resources\views/sabores/index.blade.php ENDPATH**/ ?>