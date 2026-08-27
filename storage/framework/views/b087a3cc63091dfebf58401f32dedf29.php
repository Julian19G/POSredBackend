

<?php $__env->startSection('content'); ?>
<div class="container">
    <h1 class="mb-4">🧾 Detalle de Venta #<?php echo e($detalleVenta->id); ?></h1>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <h5 class="card-title mb-3 text-primary">Información General</h5>
            <ul class="list-group list-group-flush">
                <li class="list-group-item">
                    <strong>Venta asociada:</strong>
                    Venta #<?php echo e($detalleVenta->venta->id ?? 'No disponible'); ?>

                </li>
                <li class="list-group-item">
                    <strong>Fecha de creación:</strong>
                    <?php echo e($detalleVenta->created_at->format('d/m/Y H:i')); ?>

                </li>
                <li class="list-group-item">
                    <strong>Última actualización:</strong>
                    <?php echo e($detalleVenta->updated_at->format('d/m/Y H:i')); ?>

                </li>
            </ul>
        </div>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <h5 class="card-title mb-3 text-success">📦 Información del Producto</h5>
            <ul class="list-group list-group-flush">
                <li class="list-group-item">
                    <strong>Producto:</strong>
                    <?php echo e($detalleVenta->producto->nombre ?? $detalleVenta->nombre_producto); ?>

                </li>
                <li class="list-group-item">
                    <strong>Código del producto:</strong>
                    <?php echo e($detalleVenta->codigo_producto ?? 'No especificado'); ?>

                </li>
                <li class="list-group-item">
                    <strong>Cantidad vendida:</strong>
                    <?php echo e($detalleVenta->cantidad); ?>

                </li>
                <li class="list-group-item">
                    <strong>Precio unitario:</strong>
                    $<?php echo e(number_format($detalleVenta->precio_unitario, 2)); ?>

                </li>
            </ul>
        </div>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <h5 class="card-title mb-3 text-danger">💰 Detalles Financieros</h5>
            <ul class="list-group list-group-flush">
                <li class="list-group-item">
                    <strong>Descuento aplicado:</strong>
                    $<?php echo e(number_format($detalleVenta->descuento_aplicado, 2)); ?>

                </li>
                <li class="list-group-item">
                    <strong>Impuesto:</strong>
                    $<?php echo e(number_format($detalleVenta->impuesto, 2)); ?>

                </li>
                <li class="list-group-item bg-light fw-semibold">
                    <strong>Subtotal final:</strong>
                    $<?php echo e(number_format($detalleVenta->subtotal, 2)); ?>

                </li>
            </ul>
        </div>
    </div>

    <div class="d-flex justify-content-between">
        <a href="<?php echo e(route('detalle_ventas.edit', $detalleVenta)); ?>" class="btn btn-warning">
            ✏️ Editar Detalle
        </a>
        <a href="<?php echo e(route('detalle_ventas.index')); ?>" class="btn btn-secondary">
            ← Volver al listado
        </a>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Usuario\Documents\My Web Sites\POS\Backend\POSRed\resources\views\detalle_ventas\show.blade.php ENDPATH**/ ?>