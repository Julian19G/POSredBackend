

<?php $__env->startSection('content'); ?>
<div class="container">
    <h1 class="mb-4">🧾 Detalle de Venta #<?php echo e($venta->id); ?></h1>

    
    <div class="card mb-4 shadow-sm border-0 rounded-4">
        <div class="card-body">
            <h5 class="mb-3">📋 Información general</h5>
            <div class="row">
                <div class="col-md-6 mb-2">
                    <strong>Cliente:</strong><br>
                    <?php echo e($venta->cliente->nombre ?? 'Sin cliente asignado'); ?>

                </div>
                <div class="col-md-6 mb-2">
                    <strong>Teléfono:</strong><br>
                    <?php echo e($venta->cliente->telefono ?? '—'); ?>

                </div>
                <div class="col-md-6 mb-2">
                    <strong>Dirección de envío:</strong><br>
                    <?php echo e($venta->direccion_envio ?? 'No aplica'); ?>

                </div>
                <div class="col-md-6 mb-2">
                    <strong>Envío:</strong><br>
                    <?php echo e($venta->envio ? 'Sí' : 'No'); ?>

                </div>
                <div class="col-md-6 mb-2">
                    <strong>Estado:</strong><br>
                    <?php switch($venta->estado):
                        case ('pagada'): ?>
                            <span class="badge bg-success">Pagada</span>
                            <?php break; ?>
                        <?php case ('pendiente'): ?>
                            <span class="badge bg-warning text-dark">Pendiente</span>
                            <?php break; ?>
                        <?php case ('cancelada'): ?>
                            <span class="badge bg-danger">Cancelada</span>
                            <?php break; ?>
                    <?php endswitch; ?>
                </div>
                <div class="col-md-6 mb-2">
                    <strong>Fecha de registro:</strong><br>
                    <?php echo e($venta->created_at->format('d/m/Y H:i')); ?>

                </div>
            </div>
        </div>
    </div>

    
    <div class="card mb-4 shadow-sm border-0 rounded-4">
        <div class="card-body">
            <h5 class="mb-3">🛍️ Productos vendidos</h5>
            <?php if($venta->detalles->isEmpty()): ?>
                <p class="text-muted">No hay productos registrados en esta venta.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>Producto</th>
                                <th>Precio Unitario</th>
                                <th>Cantidad</th>
                                <th>descuentos</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $venta->detalles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detalle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($detalle->producto->nombre ?? 'Producto eliminado'); ?></td>
                                    <td>$<?php echo e(number_format($detalle->precio_unitario, 2, ',', '.')); ?></td>
                                    <td><?php echo e($detalle->cantidad); ?></td>
                                    <td>
                                        <?php if($detalle->descuentos_manual > 0): ?>
                                            <span class="text-danger">-$<?php echo e(number_format($detalle->descuentos_manual, 2, ',', '.')); ?></span>
                                        <?php else: ?>
                                            <span class="text-muted">—</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>$<?php echo e(number_format(($detalle->precio_unitario * $detalle->cantidad) - $detalle->descuentos_manual, 2, ',', '.')); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

    
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body">
            <h5 class="mb-3">💰 Totales</h5>
            <div class="row">
                <div class="col-md-6 mb-2">
                    <strong>Subtotal:</strong><br>
                    $<?php echo e(number_format($venta->subtotal, 2, ',', '.')); ?>

                </div>
                <div class="col-md-6 mb-2">
                    <strong>descuentos total manual:</strong><br>
                    <?php if($venta->descuentos_manual > 0): ?>
                        <span class="text-danger">-$<?php echo e(number_format($venta->descuentos_manual, 2, ',', '.')); ?></span>
                        <small class="text-muted">(<?php echo e($venta->motivo_descuentos ?? 'Sin motivo registrado'); ?>)</small>
                    <?php else: ?>
                        <span class="text-muted">—</span>
                    <?php endif; ?>
                </div>
                <div class="col-md-6 mb-2">
                    <strong>Costo de envío:</strong><br>
                    $<?php echo e(number_format($venta->costo_envio, 2, ',', '.')); ?>

                </div>
                <div class="col-md-6 mb-2">
                    <strong>Total final:</strong><br>
                    <span class="fs-5 fw-bold text-primary">$<?php echo e(number_format($venta->total, 2, ',', '.')); ?></span>
                </div>
            </div>
        </div>
    </div>

    <a href="<?php echo e(route('ventas.index')); ?>" class="btn btn-secondary mt-4">⬅ Volver al listado</a>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Usuario\Documents\My Web Sites\POS\Backend\POSRed\resources\views/ventas/show.blade.php ENDPATH**/ ?>