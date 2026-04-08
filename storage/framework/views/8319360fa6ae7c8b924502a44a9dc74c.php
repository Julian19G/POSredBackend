

<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold mb-0">Listado de Categorías</h1>
        <a href="<?php echo e(route('categorias.create')); ?>" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Nueva Categoría
        </a>
    </div>

    
    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    <?php endif; ?>

    
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 70px;">ID</th>
                        <th style="width: 100px;">Imagen</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Estado</th>
                        <th class="text-center" style="width: 240px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $categorias; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $categoria): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($categoria->id); ?></td>

                            
                            <td>
                                <?php if($categoria->imagen): ?>
                                    <img src="<?php echo e(asset('storage/' . $categoria->imagen)); ?>" 
                                         alt="<?php echo e($categoria->nombre); ?>" 
                                         class="rounded" 
                                         style="width: 60px; height: 60px; object-fit: cover;">
                                <?php else: ?>
                                    <span class="text-muted fst-italic">Sin imagen</span>
                                <?php endif; ?>
                            </td>

                            <td class="fw-semibold"><?php echo e($categoria->nombre); ?></td>
                            <td><?php echo e($categoria->descripcion ?: 'Sin descripción'); ?></td>
                            <td>
                                <?php if($categoria->activa): ?>
                                    <span class="badge bg-success">Activa</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Inactiva</span>
                                <?php endif; ?>
                            </td>

                            
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <a href="<?php echo e(route('categorias.show', $categoria)); ?>" 
                                       class="btn btn-info btn-sm">
                                        <i class="bi bi-eye"></i> Ver
                                    </a>
                                    <a href="<?php echo e(route('categorias.edit', $categoria)); ?>" 
                                       class="btn btn-warning btn-sm text-white">
                                        <i class="bi bi-pencil"></i> Editar
                                    </a>
                                    <form action="<?php echo e(route('categorias.destroy', $categoria)); ?>" 
                                          method="POST" class="d-inline">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" 
                                                class="btn btn-danger btn-sm"
                                                onclick="return confirm('¿Estás seguro de eliminar esta categoría?')">
                                            <i class="bi bi-trash"></i> Eliminar
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                <i class="bi bi-folder-x"></i> No hay categorías registradas.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Usuario\Documents\My Web Sites\POS\Backend\POSRed\resources\views/categorias/index.blade.php ENDPATH**/ ?>