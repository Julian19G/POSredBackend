

<?php $__env->startSection('content'); ?>
<div class="container">
    <h1 class="mb-4">Listado de Productos</h1>

    <?php if(session('success')): ?>
        <div class="alert alert-success">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="<?php echo e(route('productos.create')); ?>" class="btn btn-primary">➕ Nuevo Producto</a>
        <span class="text-muted">Total: <?php echo e($productos->count()); ?></span>
    </div>

    <table class="table table-bordered table-hover align-middle text-center">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Imagen</th>
                <th>Nombre</th>
                <th>Categoría</th>
                <th>Sabores</th>
                <th>Colores</th>
                <th>Efectos</th>
                <th>Descripción</th>
                <th>Precio</th>
                <th>Stock</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $productos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $producto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($producto->id); ?></td>

                    <!-- Imagen del producto -->
                    <td>
                        <?php if($producto->imagen): ?>
                            <img src="<?php echo e(asset('storage/' . $producto->imagen)); ?>" 
                                 alt="<?php echo e($producto->nombre); ?>" 
                                 class="img-thumbnail"
                                 style="width: 60px; height: 60px; object-fit: cover;">
                        <?php else: ?>
                            <span class="text-muted">Sin imagen</span>
                        <?php endif; ?>
                    </td>

                    <td><?php echo e($producto->nombre); ?></td>
                    <td><?php echo e($producto->categoria ? $producto->categoria->nombre : 'Sin categoría'); ?></td>

                    
                    <td>
                        <?php $__empty_2 = true; $__currentLoopData = $producto->sabores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sabor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                            <span class="badge bg-primary"><?php echo e($sabor->nombre); ?></span>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                            <span class="text-muted">—</span>
                        <?php endif; ?>
                    </td>

                    
                    <td>
                        <?php $__empty_2 = true; $__currentLoopData = $producto->colores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $color): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                            <span class="badge bg-success"><?php echo e($color->nombre); ?></span>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                            <span class="text-muted">—</span>
                        <?php endif; ?>
                    </td>

                    
                    <td>
                        <?php $__empty_2 = true; $__currentLoopData = $producto->efectos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $efecto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                            <span class="badge bg-warning text-dark"><?php echo e($efecto->nombre); ?></span>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                            <span class="text-muted">—</span>
                        <?php endif; ?>
                    </td>

                    <td><?php echo e(Str::limit($producto->descripcion, 40, '...')); ?></td>
                    <td>$<?php echo e(number_format($producto->precio, 0, ',', '.')); ?></td>
                    <td><?php echo e($producto->stock); ?></td>

                    <td>
                        <?php if($producto->activo): ?>
                            <span class="badge bg-success">Activo</span>
                        <?php else: ?>
                            <span class="badge bg-secondary">Inactivo</span>
                        <?php endif; ?>
                    </td>

                    <td class="text-center">
                        <a href="<?php echo e(route('productos.show', $producto)); ?>" class="btn btn-info btn-sm">Ver</a>
                        <a href="<?php echo e(route('productos.edit', $producto)); ?>" class="btn btn-warning btn-sm">Editar</a>
                        <form action="<?php echo e(route('productos.destroy', $producto)); ?>" method="POST" class="d-inline">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar este producto?')">
                                Eliminar
                            </button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="12" class="text-center text-muted">No hay productos registrados</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Usuario\Documents\My Web Sites\POS\Backend\POSRed\resources\views/productos/index.blade.php ENDPATH**/ ?>