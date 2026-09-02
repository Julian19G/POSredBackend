<?php $__env->startSection('content'); ?>
<div class="container" style="max-width:760px">

    
    <div class="d-flex justify-content-between align-items-center mt-3 mb-3">
        <div>
            <h1 class="mb-0"><?php echo e($ruta->tipoLabel()); ?> #<?php echo e($ruta->id); ?></h1>
            <small class="text-muted">
                <?php echo e($ruta->domiciliario->nombre ?? '—'); ?> &bull;
                <?php echo e($ruta->created_at->format('d/m/Y H:i')); ?>

            </small>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="badge fs-6 bg-<?php echo e($ruta->estadoColor()); ?>"><?php echo e(ucfirst($ruta->estado)); ?></span>
            <a href="<?php echo e(route('rutas.index')); ?>" class="btn btn-outline-secondary btn-sm">← Mis rutas</a>
        </div>
    </div>

    
    <?php
        $total      = $ruta->domicilios->count();
        $entregados = $ruta->domicilios->where('estado', 'entregado')->count();
        $pct        = $total > 0 ? intval($entregados / $total * 100) : 0;
    ?>
    <div class="mb-4">
        <div class="d-flex justify-content-between small mb-1">
            <span>Progreso de entregas</span>
            <strong><?php echo e($entregados); ?>/<?php echo e($total); ?></strong>
        </div>
        <div class="progress" style="height:10px">
            <div class="progress-bar bg-success" style="width:<?php echo e($pct); ?>%"></div>
        </div>
    </div>

    
    <?php $__currentLoopData = $ruta->domicilios; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="card border-0 shadow-sm rounded-3 mb-3">
        <div class="card-body">

            
            <div class="d-flex justify-content-between align-items-start mb-2">
                <div>
                    <span class="badge bg-secondary me-1"><?php echo e($i + 1); ?></span>
                    <strong class="fs-6"><?php echo e($d->nombre_cliente); ?></strong>
                    <?php if($d->tipo === 'express'): ?>
                        <span class="badge bg-warning text-dark ms-1">⚡ Express</span>
                    <?php endif; ?>
                </div>
                <span class="badge bg-<?php echo e($d->estadoColor()); ?>"><?php echo e($d->estadoLabel()); ?></span>
            </div>

            
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <div class="p-3 rounded-2" style="background:#f8f9fa">
                        <div class="text-muted small fw-bold mb-1">📦 RECOGIDA</div>
                        <div class="small"><?php echo e($d->instrucciones_recogida ?? 'Sin instrucciones'); ?></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="p-3 rounded-2" style="background:#e8f5e9">
                        <div class="text-muted small fw-bold mb-1">🏠 ENTREGA</div>
                        <div class="small"><?php echo e($d->instrucciones_entrega ?? $d->direccion); ?></div>
                        <?php if($d->telefono_cliente): ?>
                            <div class="small text-primary mt-1">
                                📞 <?php echo e($d->telefono_cliente); ?>

                            </div>
                        <?php endif; ?>
                        <?php if($d->venta?->cliente?->whatsapp): ?>
                            <a href="https://wa.me/57<?php echo e(preg_replace('/\D/', '', $d->venta->cliente->whatsapp)); ?>"
                               target="_blank"
                               class="btn btn-sm btn-outline-success mt-1" style="font-size:.75rem">
                                💬 WhatsApp
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            
            <?php $debeCobrarse = $d->venta && $d->venta->estado !== 'pagada'; ?>
            <?php if($debeCobrarse): ?>
                <div class="alert alert-warning py-2 small mb-3">
                    <strong>💰 Cobrar al cliente:</strong>
                    $<?php echo e(number_format($d->venta->total, 0, ',', '.')); ?> COP
                </div>
            <?php else: ?>
                <div class="alert alert-success py-2 small mb-3">
                    ✅ <strong>Ya pagado.</strong> Solo entregar, no cobrar.
                </div>
            <?php endif; ?>

            
            <?php if($d->estado === 'aceptado'): ?>
                <form action="<?php echo e(route('rutas.recoger', $d->id)); ?>" method="POST">
                    <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                    <button type="submit" class="btn btn-primary w-100"
                            data-confirm="¿Confirmás que recogiste el paquete?"
                            data-confirm-icon="question"
                            data-confirm-ok="Sí, lo recogí">
                        📦 Marcar como Recogido
                    </button>
                </form>

            <?php elseif($d->estado === 'en_camino'): ?>
                <button type="button" class="btn btn-success w-100"
                        data-bs-toggle="modal"
                        data-bs-target="#modal-entregar-<?php echo e($d->id); ?>">
                    ✅ Marcar como Entregado
                </button>

                
                <div class="modal fade" id="modal-entregar-<?php echo e($d->id); ?>" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <form action="<?php echo e(route('rutas.entregar', $d->id)); ?>" method="POST">
                                <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                                <div class="modal-header">
                                    <h5 class="modal-title">Confirmar entrega — <?php echo e($d->nombre_cliente); ?></h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">

                                    <?php if($debeCobrarse): ?>
                                        <div class="alert alert-warning">
                                            💰 Debes cobrar <strong>$<?php echo e(number_format($d->venta->total, 0, ',', '.')); ?></strong> al cliente.
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Método de cobro <span class="text-danger">*</span></label>
                                            <select name="metodo_cobro" class="form-select" required>
                                                <option value="">Seleccionar…</option>
                                                <option value="efectivo">💵 Efectivo</option>
                                                <option value="transferencia">🏦 Transferencia</option>
                                                <option value="cripto">₿ Cripto</option>
                                                <option value="tarjeta">💳 Tarjeta</option>
                                                <option value="otro">Otro</option>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Monto cobrado (dejar vacío si es el total)</label>
                                            <div class="input-group">
                                                <span class="input-group-text">$</span>
                                                <input type="number" name="monto_cobrado" class="form-control"
                                                       placeholder="<?php echo e(number_format($d->venta->total, 0)); ?>"
                                                       min="0" step="100">
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <div class="alert alert-success">
                                            ✅ Este pedido ya fue pagado. Solo confirma la entrega.
                                        </div>
                                    <?php endif; ?>

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                    <button type="submit" class="btn btn-success">
                                        ✅ Confirmar entrega
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            <?php elseif($d->estado === 'entregado'): ?>
                <div class="text-center text-success fw-semibold py-2">
                    ✅ Entregado
                    <?php if($d->fecha_entrega_real): ?>
                        <small class="text-muted d-block"><?php echo e($d->fecha_entrega_real->format('d/m/Y H:i')); ?></small>
                    <?php endif; ?>
                </div>

            <?php elseif($d->estado === 'cancelado'): ?>
                <div class="text-center text-muted py-2">❌ Cancelado</div>
            <?php endif; ?>

        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    <?php if($ruta->estado === 'completada'): ?>
        <div class="alert alert-success text-center mt-3">
            🎉 <strong>¡Ruta completada!</strong>
            <?php if($ruta->fecha_completada): ?>
                Finalizada el <?php echo e($ruta->fecha_completada->format('d/m/Y \a \l\a\s H:i')); ?>.
            <?php endif; ?>
        </div>
    <?php endif; ?>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Usuario\Documents\My Web Sites\POS\Backend\POSRed\resources\views\rutas\show.blade.php ENDPATH**/ ?>