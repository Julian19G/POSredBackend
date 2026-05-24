<?php $__env->startSection('content'); ?>
<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-4 mt-3">
        <h1 class="mb-0">Venta #<?php echo e($venta->id); ?></h1>
        <div class="d-flex gap-2">
            <a href="<?php echo e(route('ventas.recibo', $venta->id)); ?>" target="_blank" class="btn btn-outline-secondary">🖨 Imprimir</a>
            <a href="<?php echo e(route('ventas.edit', $venta->id)); ?>" class="btn btn-warning">✏️ Editar</a>
            <a href="<?php echo e(route('ventas.index')); ?>" class="btn btn-secondary">← Volver</a>
        </div>
    </div>

    <div class="row g-4">

        
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body">
                    <h5 class="mb-3">Información general</h5>
                    <table class="table table-sm table-borderless mb-0">
                        <tr>
                            <th>Cliente</th>
                            <td>
                                <a href="<?php echo e(route('clientes.show', $venta->cliente_id)); ?>" class="text-decoration-none">
                                    <?php echo e($venta->cliente->nombre ?? '—'); ?>

                                </a>
                            </td>
                        </tr>
                        <tr><th>Teléfono</th><td><?php echo e($venta->cliente->telefono ?? '—'); ?></td></tr>
                        <?php if($venta->cliente?->whatsapp): ?>
                        <tr>
                            <th>WhatsApp</th>
                            <td>
                                <a href="https://wa.me/57<?php echo e(preg_replace('/\D/','',$venta->cliente->whatsapp)); ?>" target="_blank">
                                    💬 <?php echo e($venta->cliente->whatsapp); ?>

                                </a>
                            </td>
                        </tr>
                        <?php endif; ?>
                        <tr>
                            <th>Vendedor</th>
                            <td>
                                <?php if($venta->vendedor): ?>
                                    <a href="<?php echo e(route('vendedores.show', $venta->vendedor->id)); ?>" class="text-decoration-none">
                                        <?php echo e($venta->vendedor->nombre); ?>

                                    </a>
                                <?php else: ?>
                                    <span class="text-muted">—</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Estado</th>
                            <td>
                                <?php switch($venta->estado):
                                    case ('pagada'): ?>    <span class="badge bg-success">Pagada</span>    <?php break; ?>
                                    <?php case ('pendiente'): ?> <span class="badge bg-warning text-dark">Pendiente</span> <?php break; ?>
                                    <?php case ('cancelada'): ?> <span class="badge bg-danger">Cancelada</span>  <?php break; ?>
                                <?php endswitch; ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Método de pago</th>
                            <td><?php echo e($venta->pedido?->metodo_pago ? Str::ucfirst($venta->pedido->metodo_pago) : '—'); ?></td>
                        </tr>
                        <tr><th>Envío</th><td><?php echo e($venta->envio ? 'Sí' : 'No'); ?></td></tr>
                        <?php if($venta->direccion_envio): ?>
                        <tr><th>Dirección envío</th><td><?php echo e($venta->direccion_envio); ?></td></tr>
                        <?php endif; ?>
                        <tr><th>Fecha</th><td><?php echo e($venta->created_at->format('d/m/Y H:i')); ?></td></tr>
                    </table>
                </div>
            </div>
        </div>

        
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body">
                    <h5 class="mb-3">Totales</h5>
                    <table class="table table-sm table-borderless mb-0">
                        <tr><th>Subtotal</th><td class="text-end">$<?php echo e(number_format($venta->subtotal, 0, ',', '.')); ?></td></tr>
                        <?php if($venta->descuento_manual > 0): ?>
                        <tr>
                            <th>Descuento <?php if($venta->motivo_descuento): ?><small class="text-muted">(<?php echo e($venta->motivo_descuento); ?>)</small><?php endif; ?></th>
                            <td class="text-end text-danger">-$<?php echo e(number_format($venta->descuento_manual, 0, ',', '.')); ?></td>
                        </tr>
                        <?php endif; ?>
                        <?php if($venta->costo_envio > 0): ?>
                        <tr><th>Costo envío</th><td class="text-end">$<?php echo e(number_format($venta->costo_envio, 0, ',', '.')); ?></td></tr>
                        <?php endif; ?>
                        <tr class="border-top">
                            <th class="fs-5">Total</th>
                            <td class="text-end fs-5 fw-bold text-primary">$<?php echo e(number_format($venta->total, 0, ',', '.')); ?></td>
                        </tr>
                    </table>
                </div>
            </div>

            
            <?php if($venta->pedido): ?>
            <div class="card border-0 shadow-sm rounded-4 mt-3">
                <div class="card-header bg-white border-0 pt-3 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold">Comprobantes de pago</h6>
                    <?php if($venta->pedido): ?>
                        <a href="<?php echo e(route('pedidos.show', $venta->pedido->id)); ?>" class="btn btn-xs btn-sm btn-outline-secondary py-0 px-2 small">
                            Ver pedido
                        </a>
                    <?php endif; ?>
                </div>
                <div class="card-body pt-2">
                    <?php $comprobantes = $venta->pedido->comprobantes; ?>
                    <?php if($comprobantes->isEmpty()): ?>
                        <p class="text-muted small mb-0">Sin comprobantes registrados.</p>
                    <?php else: ?>
                        <?php $__currentLoopData = $comprobantes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $comp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="d-flex justify-content-between align-items-start border-bottom py-2 small">
                            <div>
                                <span class="fw-semibold"><?php echo e(ucfirst($comp->tipo)); ?></span>
                                <?php if($comp->referencia): ?> — <span class="text-muted"><?php echo e($comp->referencia); ?></span> <?php endif; ?>
                                <br>
                                <span class="text-muted" style="font-size:.78rem">
                                    <?php echo e($comp->created_at->format('d/m/Y H:i')); ?>

                                </span>
                            </div>
                            <div class="text-end">
                                <div class="fw-semibold">$<?php echo e(number_format($comp->monto, 0, ',', '.')); ?></div>
                                <?php echo $comp->badge_estado; ?>

                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>

        
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body">
                    <h5 class="mb-3">Productos vendidos</h5>
                    <?php if($venta->detalles->isEmpty()): ?>
                        <p class="text-muted">Sin productos.</p>
                    <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th>Producto</th>
                                    <th class="text-center">Precio unit.</th>
                                    <th class="text-center">Cant.</th>
                                    <th class="text-center">Descuento</th>
                                    <th class="text-center">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $venta->detalles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td>
                                        <?php echo e($d->nombre_producto ?? 'Producto eliminado'); ?>

                                        <?php if($d->nombre_variante): ?>
                                            <br><small class="text-muted"><?php echo e($d->nombre_variante); ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">$<?php echo e(number_format($d->precio_unitario, 0, ',', '.')); ?></td>
                                    <td class="text-center"><?php echo e($d->cantidad); ?></td>
                                    <td class="text-center">
                                        <?php if($d->descuento_aplicado > 0): ?>
                                            <span class="text-danger">-$<?php echo e(number_format($d->descuento_aplicado, 0, ',', '.')); ?></span>
                                        <?php else: ?>
                                            <span class="text-muted">—</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">$<?php echo e(number_format($d->subtotal, 0, ',', '.')); ?></td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Usuario\Documents\My Web Sites\POS\Backend\POSRed\resources\views/ventas/show.blade.php ENDPATH**/ ?>