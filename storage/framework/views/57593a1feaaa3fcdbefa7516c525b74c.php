

<?php $__env->startSection('content'); ?>
<div class="container py-4">

    
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo e(route('categorias.index')); ?>">Categorías</a></li>
            <li class="breadcrumb-item active"><?php echo e($categoria->nombre); ?></li>
        </ol>
    </nav>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">

        
        <div class="card-header bg-white border-bottom px-4 py-3 d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-3">

                
                <?php if($categoria->imagen): ?>
                    <img src="<?php echo e(asset('storage/' . $categoria->imagen)); ?>"
                         alt="<?php echo e($categoria->nombre); ?>"
                         class="rounded-3 shadow-sm"
                         style="width:56px;height:56px;object-fit:cover;">
                <?php else: ?>
                    <div class="rounded-3 bg-light d-flex align-items-center justify-content-center"
                         style="width:56px;height:56px;font-size:1.5rem;">
                        🏷️
                    </div>
                <?php endif; ?>

                <div>
                    <h5 class="fw-bold mb-0"><?php echo e($categoria->nombre); ?></h5>
                    <small class="text-muted">
                        <?php echo e($categoria->descripcion ?? 'Sin descripción'); ?>

                    </small>
                </div>
            </div>

            <div class="d-flex align-items-center gap-2">
                <?php if($categoria->activa): ?>
                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">Activa</span>
                <?php else: ?>
                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2 py-1">Inactiva</span>
                <?php endif; ?>
                <a href="<?php echo e(route('categorias.edit', $categoria)); ?>" class="btn btn-warning btn-sm">
                    ✏️ Editar
                </a>
                <a href="<?php echo e(route('categorias.index')); ?>" class="btn btn-outline-secondary btn-sm">
                    ← Volver
                </a>
            </div>
        </div>

        
        <div class="card-body p-0">

            
            <div class="px-4 py-3 border-bottom bg-light d-flex align-items-center justify-content-between">
                <span class="fw-semibold text-muted small text-uppercase">
                    Productos en esta categoría
                </span>
                <span class="badge bg-secondary rounded-pill">
                    <?php echo e($categoria->productos->count()); ?>

                </span>
            </div>

            <?php if($categoria->productos->isEmpty()): ?>
                <div class="text-center py-5 text-muted">
                    <div style="font-size:2.5rem">📦</div>
                    <p class="mt-2 mb-0">No hay productos en esta categoría aún.</p>
                </div>

            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">#</th>
                                <th>Nombre</th>
                                <th>Precio</th>
                                <th>Stock</th>
                                <th>Estado</th>
                                <th class="pe-4 text-end">Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php $__currentLoopData = $categoria->productos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $producto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td class="ps-4 text-muted small"><?php echo e($producto->id); ?></td>
                                <td class="fw-semibold"><?php echo e($producto->nombre); ?></td>
                                <td>$<?php echo e(number_format($producto->precio, 2, ',', '.')); ?></td>
                                <td>
                                    <span class="badge rounded-pill bg-<?php echo e($producto->stock > 5 ? 'success' : ($producto->stock > 0 ? 'warning text-dark' : 'danger')); ?>">
                                        <?php echo e($producto->stock); ?>

                                    </span>
                                </td>
                                <td>
                                    <?php if($producto->stock > 5): ?>
                                        <span class="text-success small fw-semibold">✔ En stock</span>
                                    <?php elseif($producto->stock > 0): ?>
                                        <span class="text-warning small fw-semibold">⚠ Poco stock</span>
                                    <?php else: ?>
                                        <span class="text-danger small fw-semibold">✖ Sin stock</span>
                                    <?php endif; ?>
                                </td>
                                <td class="pe-4 text-end">
                                    <a href="<?php echo e(route('productos.show', $producto->id)); ?>"
                                       class="btn btn-outline-primary btn-sm">Ver</a>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>

        </div>

        
        <div class="card-footer bg-white border-top text-muted small px-4 py-2">
            Última actualización: <?php echo e($categoria->updated_at->format('d/m/Y H:i')); ?>

        </div>

    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Usuario\Documents\My Web Sites\POS\Backend\POSRed\resources\views/categorias/show.blade.php ENDPATH**/ ?>