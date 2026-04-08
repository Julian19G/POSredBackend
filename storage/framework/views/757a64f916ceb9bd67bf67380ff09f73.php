
<?php $__env->startSection('content'); ?>
<div class="container py-4">

    
    <div class="d-flex align-items-center justify-content-between mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="<?php echo e(route('ventas.index')); ?>">Ventas</a></li>
                <li class="breadcrumb-item"><a href="<?php echo e(route('pedidos.index')); ?>">Pedidos</a></li>
                <li class="breadcrumb-item active">Pedido #<?php echo e($pedido->id); ?></li>
            </ol>
        </nav>
        <a href="<?php echo e(route('pedidos.index')); ?>" class="btn btn-outline-secondary btn-sm">
            ← Volver a Pedidos
        </a>
    </div>

<div class="row g-4">

    
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3">📦 Pedido #<?php echo e($pedido->id); ?></h5>
                <p class="mb-1 text-muted small">Venta #<?php echo e($pedido->venta->id); ?> —
                    <?php echo e($pedido->venta->created_at->format('d/m/Y H:i')); ?></p>

                
                <div class="mt-3">
                    <strong>👤 Cliente</strong>
                    <p class="mb-0"><?php echo e($pedido->venta->cliente->nombre); ?></p>
                    <p class="mb-0 text-muted small"><?php echo e($pedido->venta->cliente->telefono); ?></p>
                </div>

                
                <div class="mt-3">
                    <strong>🛍 Productos</strong>
                    <table class="table table-sm mt-2">
                        <thead class="table-light">
                            <tr><th>Producto</th><th>Cant.</th><th class="text-end">Subtotal</th></tr>
                        </thead>
                        <tbody>
                        <?php $__currentLoopData = $pedido->venta->detalles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($d->nombre_producto); ?></td>
                                <td><?php echo e($d->cantidad); ?></td>
                                <td class="text-end">$<?php echo e(number_format($d->subtotal, 2, ',', '.')); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>

                
                <div class="d-flex flex-column align-items-end mt-2">
                    <span class="text-muted small">Subtotal: $<?php echo e(number_format($pedido->venta->subtotal, 2, ',', '.')); ?></span>
                    <?php if($pedido->venta->descuento_manual > 0): ?>
                    <span class="text-danger small">Descuento: −$<?php echo e(number_format($pedido->venta->descuento_manual, 2, ',', '.')); ?></span>
                    <?php endif; ?>
                    <?php if($pedido->venta->costo_envio > 0): ?>
                    <span class="text-muted small">Envío: $<?php echo e(number_format($pedido->venta->costo_envio, 2, ',', '.')); ?></span>
                    <?php endif; ?>
                    <strong class="fs-5 mt-1">Total: $<?php echo e(number_format($pedido->venta->total, 2, ',', '.')); ?></strong>
                </div>
            </div>
        </div>

        
        <?php if($pedido->venta->domicilio): ?>
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <h6 class="fw-bold">🚚 Domicilio</h6>
                <p class="mb-1"><?php echo e($pedido->venta->domicilio->direccion); ?></p>
                <p class="mb-1 text-muted small">
                    <?php echo e($pedido->venta->domicilio->ciudad); ?>,
                    <?php echo e($pedido->venta->domicilio->departamento); ?>,
                    <?php echo e($pedido->venta->domicilio->pais); ?>

                </p>
                <?php if($pedido->venta->domicilio->comentarios): ?>
                <p class="text-muted small fst-italic"><?php echo e($pedido->venta->domicilio->comentarios); ?></p>
                <?php endif; ?>
                <span class="badge bg-<?php echo e($pedido->venta->domicilio->estado === 'entregado' ? 'success' : ($pedido->venta->domicilio->estado === 'cancelado' ? 'danger' : 'warning')); ?>">
                    <?php echo e(ucfirst($pedido->venta->domicilio->estado)); ?>

                </span>
            </div>
        </div>
        <?php endif; ?>
    </div>

    
    <div class="col-lg-5">

        
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3">📋 Estado del Pedido</h6>

                
                <?php
                    $pasos = ['nuevo','en_preparacion','despachado','entregado'];
                    $estados = \App\Models\Pedido::estadosLabel();
                    $estadoActual = $pedido->estado;
                    $indexActual = array_search($estadoActual, $pasos);
                ?>
                <div class="d-flex flex-column gap-2 mb-3">
                <?php $__currentLoopData = $pasos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $paso): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php $done = $indexActual !== false && $i <= $indexActual; ?>
                    <div class="d-flex align-items-center gap-2">
                        <span class="rounded-circle d-inline-flex align-items-center justify-content-center fw-bold"
                            style="width:28px;height:28px;font-size:.75rem;
                                   background:<?php echo e($done ? '#198754' : '#dee2e6'); ?>;
                                   color:<?php echo e($done ? '#fff' : '#666'); ?>">
                            <?php echo e($i + 1); ?>

                        </span>
                        <span class="<?php echo e($done ? 'fw-semibold' : 'text-muted'); ?>">
                            <?php echo e($estados[$paso]['label']); ?>

                        </span>
                        <?php if($paso === 'despachado' && $pedido->fecha_despacho): ?>
                            <small class="text-muted ms-auto"><?php echo e($pedido->fecha_despacho->format('d/m H:i')); ?></small>
                        <?php endif; ?>
                        <?php if($paso === 'entregado' && $pedido->fecha_entrega): ?>
                            <small class="text-muted ms-auto"><?php echo e($pedido->fecha_entrega->format('d/m H:i')); ?></small>
                        <?php endif; ?>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php if($estadoActual === 'cancelado'): ?>
                    <div class="d-flex align-items-center gap-2">
                        <span class="rounded-circle d-inline-flex align-items-center justify-content-center fw-bold"
                            style="width:28px;height:28px;font-size:.75rem;background:#dc3545;color:#fff">✖</span>
                        <span class="fw-semibold text-danger">Cancelado</span>
                    </div>
                <?php endif; ?>
                </div>

                
                <?php if(!in_array($pedido->estado, ['entregado','cancelado'])): ?>
                <form action="<?php echo e(route('pedidos.estado', $pedido->id)); ?>" method="POST">
                    <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                    <div class="mb-2">
                        <label class="form-label small fw-semibold">Cambiar estado</label>
                        <select name="estado" class="form-select form-select-sm">
                            <?php $__currentLoopData = \App\Models\Pedido::estadosLabel(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $info): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($val); ?>" <?php echo e($pedido->estado === $val ? 'selected' : ''); ?>>
                                    <?php echo e($info['label']); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="mb-2">
                        <label class="form-label small fw-semibold">Notas internas</label>
                        <textarea name="notas" class="form-control form-control-sm" rows="2"
                                  placeholder="Ej: cliente confirmó recepción…"><?php echo e($pedido->notas); ?></textarea>
                    </div>
                    <button class="btn btn-primary btn-sm w-100">Actualizar estado</button>
                </form>
                <?php else: ?>
                    <div class="alert alert-<?php echo e($pedido->estado === 'entregado' ? 'success' : 'danger'); ?> mb-0 py-2 small">
                        Pedido <?php echo e($pedido->estado === 'entregado' ? 'entregado ✅' : 'cancelado ❌'); ?>

                    </div>
                <?php endif; ?>
            </div>
        </div>

        
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3">💰 Pago</h6>
                <p class="mb-1">
                    Estado:
                    <span class="badge bg-<?php echo e($pedido->estado_pago === 'pagado' ? 'success' : ($pedido->estado_pago === 'reembolsado' ? 'warning' : 'secondary')); ?>">
                        <?php echo e(ucfirst($pedido->estado_pago)); ?>

                    </span>
                </p>
                <?php if($pedido->metodo_pago): ?>
                    <p class="mb-3">Método: <strong><?php echo e(\App\Models\Pedido::metodosLabel()[$pedido->metodo_pago]); ?></strong></p>
                <?php endif; ?>

                <?php if($pedido->estado_pago !== 'pagado'): ?>
                <form action="<?php echo e(route('pedidos.pago', $pedido->id)); ?>" method="POST">
                    <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                    <div class="mb-2">
                        <label class="form-label small fw-semibold">Método de pago</label>
                        <select name="metodo_pago" class="form-select form-select-sm" required>
                            <option value="">Seleccione…</option>
                            <?php $__currentLoopData = \App\Models\Pedido::metodosLabel(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($val); ?>"><?php echo e($label); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <button class="btn btn-success btn-sm w-100">✅ Confirmar pago</button>
                </form>
                <?php endif; ?>
            </div>
        </div>

        
        <div class="d-grid gap-2">
            <a href="<?php echo e(route('pedidos.index')); ?>" class="btn btn-outline-secondary">
                📋 Ver todos los pedidos
            </a>
            <a href="<?php echo e(route('ventas.create')); ?>" class="btn btn-outline-primary">
                🛒 Nueva venta
            </a>
            <a href="<?php echo e(route('ventas.show', $pedido->venta->id)); ?>" class="btn btn-outline-dark">
                🧾 Ver venta #<?php echo e($pedido->venta->id); ?>

            </a>
        </div>

    </div>
</div>
</div>

<?php if(session('success')): ?>
<div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div class="toast show align-items-center text-bg-success border-0">
        <div class="d-flex">
            <div class="toast-body"><?php echo e(session('success')); ?></div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>
<?php endif; ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Usuario\Documents\My Web Sites\POS\Backend\POSRed\resources\views/pedidos/show.blade.php ENDPATH**/ ?>