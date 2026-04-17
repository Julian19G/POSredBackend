

<?php $__env->startSection('content'); ?>
<div class="container">
    <h1 class="mb-4">Detalle del Producto</h1>

    <div class="card shadow-sm border-0 rounded-4">
        <div class="row g-0">
            
            <div class="col-md-4 text-center p-3">
                <?php if($producto->imagen): ?>
                    <img src="<?php echo e(asset('storage/' . $producto->imagen)); ?>" 
                         alt="<?php echo e($producto->nombre); ?>" 
                         class="img-fluid rounded-4 shadow-sm" 
                         style="max-height: 280px; object-fit: cover;">
                <?php else: ?>
                    <img src="https://via.placeholder.com/300x200?text=Sin+Imagen" 
                         class="img-fluid rounded-4 opacity-75" 
                         alt="Sin imagen">
                <?php endif; ?>
            </div>

            
            <div class="col-md-8">
                <div class="card-body">
                    <h3 class="card-title fw-bold mb-3"><?php echo e($producto->nombre); ?></h3>
                    <p class="card-text mb-2">
                        <strong>Descripción:</strong><br>
                        <?php echo e($producto->descripcion ?: 'Sin descripción disponible.'); ?>

                    </p>
                        <div class="col-md-6">
                            <p class="card-text mb-1">
                                <strong>Stock:</strong><br>
                                <?php echo e($producto->stock); ?>

                            </p>
                        </div>
                    </div>
                    <div class="mt-4">
                    <h5 class="fw-bold">Variantes / Presentaciones</h5>
                    <?php if($producto->variantes->count()): ?>
                        <table class="table table-bordered table-sm mt-2">
                            <thead class="table-dark">
                                <tr>
                                    <th>Nombre</th>
                                    <th>Cant. por variante</th>
                                    <th>Precio</th>
                                    <th>Stock</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $producto->variantes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($v->nombre); ?></td>
                                        <td><?php echo e($v->cantidad_por_variante); ?></td>
                                        <td>$<?php echo e(number_format($v->precio, 0, ',', '.')); ?></td>
                                        <td><?php echo e($v->stock); ?></td>
                                        <td>
                                            <?php if($v->activo): ?>
                                                <span class="badge bg-success">Activo</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Inactivo</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </tbody>
                                        </table>
                                    <?php else: ?>
                                        <p class="text-muted">Este producto no tiene variantes registradas.</p>
                                    <?php endif; ?>
                                </div>

                    <p class="card-text mb-2">
                        <strong>Categoría:</strong><br>
                        <?php echo e($producto->categoria ? $producto->categoria->nombre : 'Sin categoría'); ?>

                    </p>

                    <p class="card-text mb-3">
                        <strong>Estado:</strong><br>
                        <?php if($producto->activo): ?>
                            <span class="badge bg-success px-3 py-2">Activo</span>
                        <?php else: ?>
                            <span class="badge bg-danger px-3 py-2">Inactivo</span>
                        <?php endif; ?>
                    </p>

                    
                    <div class="d-flex gap-2 mt-4">
                        <a href="<?php echo e(route('productos.edit', $producto)); ?>" class="btn btn-warning">
                            <i class="bi bi-pencil-square"></i> Editar
                        </a>
                        <a href="<?php echo e(route('productos.index')); ?>" class="btn btn-secondary">
                            <i class="bi bi-arrow-left-circle"></i> Volver
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Usuario\Documents\My Web Sites\POS\Backend\POSRed\resources\views/productos/show.blade.php ENDPATH**/ ?>