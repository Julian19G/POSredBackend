

<?php $__env->startSection('content'); ?>
<div class="container">
    <h1 class="mb-4">📋 Detalles de Ventas</h1>

    
    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    <?php endif; ?>

    
    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="<?php echo e(route('detalle_ventas.create')); ?>" class="btn btn-primary">
            ➕ Nuevo Detalle de Venta
        </a>
        <form method="GET" action="<?php echo e(route('detalle_ventas.index')); ?>" class="d-flex">
            <input type="text" name="search" class="form-control me-2" placeholder="Buscar por producto o venta..." value="<?php echo e(request('search')); ?>">
            <button class="btn btn-outline-secondary" type="submit">Buscar</button>
        </form>
    </div>

    
    <div class="table-responsive">
        <table class="table table-hover align-middle text-center">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Venta</th>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio Unitario</th>
                    <th>Descuento</th>
                    <th>Impuesto</th>
                    <th>Subtotal</th>
                    <th>Fecha</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $detalleVentas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detalle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($detalle->id); ?></td>
                        <td>#<?php echo e($detalle->venta_id); ?></td>
                        <td><?php echo e($detalle->nombre_producto); ?></td>
                        <td><?php echo e($detalle->cantidad); ?></td>
                        <td>$<?php echo e(number_format($detalle->precio_unitario, 2)); ?></td>
                        <td>$<?php echo e(number_format($detalle->descuento_aplicado, 2)); ?></td>
                        <td>$<?php echo e(number_format($detalle->impuesto, 2)); ?></td>
                        <td><strong>$<?php echo e(number_format($detalle->subtotal, 2)); ?></strong></td>
                        <td><?php echo e($detalle->created_at->format('d/m/Y')); ?></td>
                        <td>
                           <div class="d-flex gap-1 justify-content-center">
                                <a href="<?php echo e(route('detalle_ventas.show', $detalle->id)); ?>" class="btn btn-sm btn-outline-info">👁 Ver</a>
                                <a href="<?php echo e(route('detalle_ventas.edit', $detalle->id)); ?>" class="btn btn-sm btn-outline-warning">✏️ Editar</a>
                                <form action="<?php echo e(route('detalle_ventas.destroy', $detalle->id)); ?>" method="POST" class="d-inline" onsubmit="return confirm('¿Seguro que deseas eliminar este registro?');">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-sm btn-outline-danger">🗑 Eliminar</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="11" class="text-muted">No hay detalles de ventas registrados.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

<div class="mt-3 text-center">



    <div class="d-flex justify-content-center">
        <?php echo e($detalleVentas->links()); ?>

    </div>

        <small class="text-muted d-block mb-2">
        Mostrando <?php echo e($detalleVentas->firstItem()); ?> a <?php echo e($detalleVentas->lastItem()); ?>

        de <?php echo e($detalleVentas->total()); ?> resultados
    </small>
    
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Usuario\Documents\My Web Sites\POS\Backend\POSRed\resources\views/detalle_ventas/index.blade.php ENDPATH**/ ?>