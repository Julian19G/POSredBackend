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
        <a href="<?php echo e(route('pedidos.index')); ?>" class="btn btn-outline-secondary btn-sm">← Volver</a>
    </div>

    <?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <?php echo e(session('success')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>

<div class="row g-4">

    
    <div class="col-lg-7">

        
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-1">📦 Pedido #<?php echo e($pedido->id); ?></h5>
                <p class="mb-2 text-muted small">Venta #<?php echo e($pedido->venta->id); ?> — <?php echo e($pedido->venta->created_at->format('d/m/Y H:i')); ?></p>

                <div class="mb-3">
                    <strong>👤 Cliente</strong>
                    <p class="mb-0"><?php echo e($pedido->venta->cliente->nombre); ?></p>
                    <p class="mb-0 text-muted small">
                        📞 <?php echo e($pedido->venta->cliente->telefono); ?>

                        <?php if($pedido->venta->cliente->whatsapp): ?>
                            · 💬 <?php echo e($pedido->venta->cliente->whatsapp); ?>

                        <?php endif; ?>
                    </p>
                </div>

                <strong>🛍 Productos</strong>
                <table class="table table-sm mt-2 mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Producto</th>
                            <th>Variante</th>
                            <th class="text-center">Cant.</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php $__currentLoopData = $pedido->venta->detalles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($d->nombre_producto); ?></td>
                            <td class="text-muted small"><?php echo e($d->nombre_variante ?? '—'); ?></td>
                            <td class="text-center"><?php echo e($d->cantidad); ?></td>
                            <td class="text-end">$<?php echo e(number_format($d->subtotal, 0, ',', '.')); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>

                <div class="d-flex flex-column align-items-end mt-3 border-top pt-2">
                    <span class="text-muted small">Subtotal: $<?php echo e(number_format($pedido->venta->subtotal, 0, ',', '.')); ?></span>
                    <?php if($pedido->venta->descuento_manual > 0): ?>
                    <span class="text-danger small">Descuento: −$<?php echo e(number_format($pedido->venta->descuento_manual, 0, ',', '.')); ?></span>
                    <?php endif; ?>
                    <?php if($pedido->venta->costo_envio > 0): ?>
                    <span class="text-muted small">Envío: $<?php echo e(number_format($pedido->venta->costo_envio, 0, ',', '.')); ?></span>
                    <?php endif; ?>
                    <strong class="fs-5 mt-1">Total: $<?php echo e(number_format($pedido->venta->total, 0, ',', '.')); ?></strong>
                </div>
            </div>
        </div>

        
        <?php if($pedido->venta->domicilio): ?>
        <?php $dom = $pedido->venta->domicilio; ?>
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <h6 class="fw-bold">🚚 Domicilio</h6>
                    <a href="<?php echo e(route('domicilios.show', $dom->id)); ?>" class="btn btn-outline-secondary btn-sm">Ver en mapa</a>
                </div>
                <p class="mb-1 fw-semibold"><?php echo e($dom->direccion); ?></p>
                <?php if($dom->referencia_ubicacion): ?>
                <p class="mb-1 text-muted small">📍 <?php echo e($dom->referencia_ubicacion); ?></p>
                <?php endif; ?>
                <?php if($dom->zona): ?>
                <p class="mb-1 text-muted small">🗺 <?php echo e($dom->zona->nombre); ?></p>
                <?php endif; ?>
                <p class="mb-2 text-muted small"><?php echo e($dom->ciudad); ?>, <?php echo e($dom->departamento); ?></p>
                <?php if($dom->comentarios): ?>
                <p class="text-muted small fst-italic mb-2"><?php echo e($dom->comentarios); ?></p>
                <?php endif; ?>
                <span class="badge bg-<?php echo e($dom->estado === 'entregado' ? 'success' : ($dom->estado === 'cancelado' ? 'danger' : 'warning')); ?>">
                    <?php echo e(ucfirst($dom->estado)); ?>

                </span>
            </div>
        </div>
        <?php endif; ?>

        
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3">📎 Comprobantes de pago</h6>

                <?php $__empty_1 = true; $__currentLoopData = $pedido->comprobantes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $comp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="border rounded-3 p-3 mb-3 <?php echo e($comp->estado === 'verificado' ? 'border-success bg-success-subtle' : ($comp->estado === 'rechazado' ? 'border-danger bg-danger-subtle' : 'border-warning bg-warning-subtle')); ?>">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <span class="badge bg-<?php echo e($comp->badge_estado); ?> mb-1"><?php echo e(ucfirst($comp->estado)); ?></span>
                            <p class="mb-0 fw-semibold"><?php echo e(\App\Models\Comprobante::tiposLabel()[$comp->tipo]); ?>

                                — $<?php echo e(number_format($comp->monto, 0, ',', '.')); ?></p>
                            <?php if($comp->referencia): ?>
                            <p class="mb-0 text-muted small">Ref: <?php echo e($comp->referencia); ?></p>
                            <?php endif; ?>
                            <?php if($comp->notas): ?>
                            <p class="mb-0 text-muted small fst-italic"><?php echo e($comp->notas); ?></p>
                            <?php endif; ?>
                            <?php if($comp->verificado_en): ?>
                            <p class="mb-0 text-muted small">Verificado: <?php echo e($comp->verificado_en->format('d/m/Y H:i')); ?></p>
                            <?php endif; ?>
                        </div>
                        <div class="d-flex flex-column gap-1 ms-3">
                            <?php if($comp->imagen_path): ?>
                            <a href="<?php echo e(asset('storage/' . $comp->imagen_path)); ?>" target="_blank"
                               class="btn btn-outline-secondary btn-sm">🖼 Ver imagen</a>
                            <?php endif; ?>
                            <?php if($comp->estado === 'pendiente'): ?>
                            <form action="<?php echo e(route('pedidos.comprobante.verificar', [$pedido->id, $comp->id])); ?>" method="POST">
                                <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                                <button class="btn btn-success btn-sm w-100"
                                        data-confirm="Se marcará la venta como pagada."
                                        data-confirm-icon="success"
                                        data-confirm-ok="✅ Verificar">
                                    ✅ Verificar
                                </button>
                            </form>
                            <form action="<?php echo e(route('pedidos.comprobante.rechazar', [$pedido->id, $comp->id])); ?>" method="POST">
                                <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                                <button class="btn btn-outline-danger btn-sm w-100"
                                        data-confirm="Se marcará este comprobante como rechazado."
                                        data-confirm-ok="❌ Rechazar">
                                    ❌ Rechazar
                                </button>
                            </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="text-muted small mb-3">Aún no hay comprobantes registrados.</p>
                <?php endif; ?>

                
                <?php if($pedido->estado_pago !== 'pagado'): ?>
                <details class="mt-2">
                    <summary class="btn btn-outline-primary btn-sm">➕ Registrar comprobante</summary>
                    <form action="<?php echo e(route('pedidos.comprobante.subir', $pedido->id)); ?>" method="POST"
                          enctype="multipart/form-data" class="mt-3">
                        <?php echo csrf_field(); ?>
                        <div class="row g-2">
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Tipo de pago</label>
                                <select name="tipo" class="form-select form-select-sm" required>
                                    <option value="">Seleccione…</option>
                                    <?php $__currentLoopData = \App\Models\Comprobante::tiposLabel(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($val); ?>"><?php echo e($label); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Monto recibido ($)</label>
                                <input type="number" name="monto" class="form-control form-control-sm"
                                       min="0" step="1000" placeholder="0"
                                       value="<?php echo e($pedido->venta->total); ?>">
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-semibold">Referencia / N° transacción</label>
                                <input type="text" name="referencia" class="form-control form-control-sm"
                                       placeholder="Nro. transferencia, hash cripto, etc.">
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-semibold">Imagen del comprobante</label>
                                <input type="file" name="imagen" class="form-control form-control-sm"
                                       accept="image/*,.pdf">
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-semibold">Notas</label>
                                <input type="text" name="notas" class="form-control form-control-sm"
                                       placeholder="Observaciones opcionales…">
                            </div>
                            <div class="col-12">
                                <button class="btn btn-primary btn-sm">📎 Guardar comprobante</button>
                            </div>
                        </div>
                    </form>
                </details>
                <?php endif; ?>

            </div>
        </div>

    </div>

    
    <div class="col-lg-5">

        
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3">📋 Estado del Pedido</h6>

                <?php
                    $pasos = ['nuevo','en_preparacion','despachado','entregado'];
                    $estados = \App\Models\Pedido::estadosLabel();
                    $estadoActual = $pedido->estado;
                    $indexActual  = array_search($estadoActual, $pasos);
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
                        <?php elseif($paso === 'entregado' && $pedido->fecha_entrega): ?>
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
                        <textarea name="notas" class="form-control form-control-sm" rows="2"><?php echo e($pedido->notas); ?></textarea>
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
                <h6 class="fw-bold mb-3">💰 Estado de pago</h6>
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge bg-<?php echo e($pedido->estado_pago === 'pagado' ? 'success' : 'secondary'); ?> fs-6">
                        <?php echo e($pedido->estado_pago === 'pagado' ? '✅ Pagado' : '⏳ Pendiente'); ?>

                    </span>
                    <?php if($pedido->metodo_pago): ?>
                    <span class="badge bg-light text-dark">
                        <?php echo e(\App\Models\Pedido::metodosLabel()[$pedido->metodo_pago]); ?>

                    </span>
                    <?php endif; ?>
                </div>

                <?php if($pedido->estado_pago !== 'pagado'): ?>
                <p class="text-muted small mb-3">
                    Registra un comprobante en el panel izquierdo y verifícalo para marcar la venta como pagada.
                </p>
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
                    <div class="mb-2">
                        <label class="form-label small fw-semibold">Monto (dejar vacío = total)</label>
                        <input type="number" name="monto" class="form-control form-control-sm"
                               min="0" step="1000" placeholder="<?php echo e($pedido->venta->total); ?>">
                    </div>
                    <div class="mb-2">
                        <label class="form-label small fw-semibold">Referencia (opcional)</label>
                        <input type="text" name="referencia" class="form-control form-control-sm"
                               placeholder="Nro. transacción, recibo, etc.">
                    </div>
                    <button class="btn btn-success btn-sm w-100">✅ Confirmar pago</button>
                </form>
                <?php endif; ?>
            </div>
        </div>

        
        <div class="d-grid gap-2">
            <a href="<?php echo e(route('ventas.recibo', $pedido->venta->id)); ?>" target="_blank"
               class="btn btn-outline-dark">🖨 Imprimir recibo</a>
            <a href="<?php echo e(route('ventas.show', $pedido->venta->id)); ?>" class="btn btn-outline-secondary">
                🧾 Ver venta #<?php echo e($pedido->venta->id); ?>

            </a>
        </div>
    </div>

</div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Usuario\Documents\My Web Sites\POS\Backend\POSRed\resources\views/pedidos/show.blade.php ENDPATH**/ ?>