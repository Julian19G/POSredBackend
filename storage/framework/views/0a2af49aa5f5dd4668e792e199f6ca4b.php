<?php $__env->startSection('content'); ?>
<div class="container py-3">

    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="mb-0">👤 <?php echo e($vendedor->nombre); ?></h1>
            <small class="text-muted"><?php echo e($vendedor->activo ? 'Activo' : 'Inactivo'); ?> · <?php echo e($vendedor->comision_porcentaje); ?>% de comisión</small>
        </div>
        <div class="d-flex gap-2">
            <a href="<?php echo e(route('vendedores.edit', $vendedor)); ?>" class="btn btn-warning btn-sm">✏️ Editar</a>
            <a href="<?php echo e(route('vendedores.index')); ?>" class="btn btn-outline-secondary btn-sm">← Volver</a>
        </div>
    </div>

    
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-4 text-center py-3">
                <div class="fs-4 fw-bold text-primary">$<?php echo e(number_format($stats['total_comisionado'], 0, ',', '.')); ?></div>
                <div class="small text-muted">Total comisionado</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-4 text-center py-3">
                <div class="fs-4 fw-bold text-success">$<?php echo e(number_format($stats['total_pagado'], 0, ',', '.')); ?></div>
                <div class="small text-muted">Ya pagado</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-4 text-center py-3">
                <div class="fs-4 fw-bold text-warning">$<?php echo e(number_format($stats['saldo_pendiente'], 0, ',', '.')); ?></div>
                <div class="small text-muted">Saldo pendiente</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-4 text-center py-3">
                <div class="fs-4 fw-bold text-secondary"><?php echo e($stats['total_ventas']); ?></div>
                <div class="small text-muted">Ventas totales</div>
            </div>
        </div>
    </div>

    <div class="row g-4">

        
        <div class="col-lg-5">

            
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3">Datos de contacto</h6>
                    <dl class="row mb-0 small">
                        <?php if($vendedor->telefono): ?>
                        <dt class="col-5 text-muted">Teléfono</dt>
                        <dd class="col-7">📞 <?php echo e($vendedor->telefono); ?></dd>
                        <?php endif; ?>
                        <?php if($vendedor->whatsapp): ?>
                        <dt class="col-5 text-muted">WhatsApp</dt>
                        <dd class="col-7">💬 <a href="https://wa.me/57<?php echo e(preg_replace('/\D/', '', $vendedor->whatsapp)); ?>" target="_blank"><?php echo e($vendedor->whatsapp); ?></a></dd>
                        <?php endif; ?>
                        <?php if($vendedor->email): ?>
                        <dt class="col-5 text-muted">Email</dt>
                        <dd class="col-7"><?php echo e($vendedor->email); ?></dd>
                        <?php endif; ?>
                        <?php if($vendedor->instagram): ?>
                        <dt class="col-5 text-muted">Instagram</dt>
                        <dd class="col-7">{{ $vendedor->instagram }}</dd>
                        <?php endif; ?>
                        <?php if($vendedor->notas): ?>
                        <dt class="col-5 text-muted">Notas</dt>
                        <dd class="col-7 fst-italic"><?php echo e($vendedor->notas); ?></dd>
                        <?php endif; ?>
                    </dl>
                </div>
            </div>

            
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 pt-3 pb-0">
                    <h6 class="fw-bold mb-0">💰 Comisiones pendientes</h6>
                </div>
                <div class="card-body">
                    <?php if($comisionesPendientes->isEmpty()): ?>
                        <p class="text-muted small mb-0">Sin comisiones pendientes.</p>
                    <?php else: ?>
                        <form action="<?php echo e(route('liquidaciones.store', $vendedor)); ?>" method="POST" id="form-liquidacion">
                            <?php echo csrf_field(); ?>

                            <div class="table-responsive mb-3">
                                <table class="table table-sm table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width:32px">
                                                <input type="checkbox" id="check-all" class="form-check-input" checked>
                                            </th>
                                            <th>Venta</th>
                                            <th class="text-end">Venta total</th>
                                            <th class="text-end">Comisión</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php $__currentLoopData = $comisionesPendientes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $com): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="comision_ids[]"
                                                   value="<?php echo e($com->id); ?>"
                                                   class="form-check-input comision-check"
                                                   data-monto="<?php echo e($com->monto_comision); ?>"
                                                   checked>
                                        </td>
                                        <td>
                                            <a href="<?php echo e(route('ventas.show', $com->venta_id)); ?>" class="text-decoration-none small">
                                                #<?php echo e($com->venta_id); ?>

                                            </a>
                                            <br><span class="text-muted" style="font-size:.78rem"><?php echo e($com->venta->cliente->nombre ?? '—'); ?></span>
                                        </td>
                                        <td class="text-end small">$<?php echo e(number_format($com->monto_venta, 0, ',', '.')); ?></td>
                                        <td class="text-end fw-semibold small text-success">
                                            $<span class="monto-com"><?php echo e(number_format($com->monto_comision, 0, ',', '.')); ?></span>
                                            <br><span class="text-muted" style="font-size:.75rem"><?php echo e($com->porcentaje); ?>%</span>
                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                    <tfoot class="table-light">
                                        <tr>
                                            <td colspan="3" class="fw-semibold small">Total a pagar:</td>
                                            <td class="text-end fw-bold text-success" id="total-liquidar">
                                                $<?php echo e(number_format($comisionesPendientes->sum('monto_comision'), 0, ',', '.')); ?>

                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                            <div class="row g-2 mb-2">
                                <div class="col-sm-6">
                                    <label class="form-label small mb-1">Método de pago</label>
                                    <select name="metodo_pago" class="form-select form-select-sm" required>
                                        <option value="efectivo">💵 Efectivo</option>
                                        <option value="transferencia">🏦 Transferencia</option>
                                        <option value="cripto">₿ Cripto</option>
                                        <option value="tarjeta">💳 Tarjeta</option>
                                        <option value="otro">📦 Otro</option>
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label small mb-1">Fecha de pago</label>
                                    <input type="date" name="fecha_pago" class="form-control form-control-sm"
                                           value="<?php echo e(now()->toDateString()); ?>" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label small mb-1">Referencia / comprobante</label>
                                    <input type="text" name="referencia" class="form-control form-control-sm"
                                           placeholder="Nro. de transferencia, recibo, etc.">
                                </div>
                                <div class="col-12">
                                    <label class="form-label small mb-1">Notas</label>
                                    <textarea name="notas" class="form-control form-control-sm" rows="2"></textarea>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-success btn-sm w-100"
                                    onclick="return confirm('¿Registrar pago de comisiones seleccionadas?')">
                                💸 Registrar pago
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        
        <div class="col-lg-7">

            
            <?php if($liquidaciones->isNotEmpty()): ?>
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-0 pt-3 pb-0">
                    <h6 class="fw-bold mb-0">📋 Historial de pagos</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-sm mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">Fecha</th>
                                    <th>Método</th>
                                    <th class="text-center">Comisiones</th>
                                    <th class="text-end">Monto</th>
                                    <th class="text-end pe-3">Ver</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php $__currentLoopData = $liquidaciones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $liq): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td class="ps-3 small"><?php echo e($liq->fecha_pago->format('d/m/Y')); ?></td>
                                <td class="small"><?php echo e(ucfirst($liq->metodo_pago)); ?></td>
                                <td class="text-center small"><?php echo e($liq->comisiones_count); ?></td>
                                <td class="text-end fw-semibold small text-success">
                                    $<?php echo e(number_format($liq->monto_total, 0, ',', '.')); ?>

                                </td>
                                <td class="text-end pe-3">
                                    <a href="<?php echo e(route('liquidaciones.show', [$vendedor, $liq])); ?>" class="btn btn-xs btn-outline-secondary btn-sm">Ver</a>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 pt-3 pb-0">
                    <h6 class="mb-0 fw-bold">🛒 Ventas realizadas</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-sm mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">#</th>
                                    <th>Cliente</th>
                                    <th class="text-end">Total</th>
                                    <th>Estado</th>
                                    <th>Fecha</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $ventas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td class="ps-3 text-muted small">
                                    <a href="<?php echo e(route('ventas.show', $v->id)); ?>" class="text-decoration-none"><?php echo e($v->id); ?></a>
                                </td>
                                <td class="small"><?php echo e($v->cliente->nombre ?? '—'); ?></td>
                                <td class="text-end small">$<?php echo e(number_format($v->total, 0, ',', '.')); ?></td>
                                <td>
                                    <?php switch($v->estado):
                                        case ('pagada'): ?>    <span class="badge bg-success">Pagada</span>    <?php break; ?>
                                        <?php case ('pendiente'): ?> <span class="badge bg-warning text-dark">Pendiente</span> <?php break; ?>
                                        <?php case ('cancelada'): ?> <span class="badge bg-danger">Cancelada</span>  <?php break; ?>
                                    <?php endswitch; ?>
                                </td>
                                <td><small><?php echo e($v->created_at->format('d/m/Y')); ?></small></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr><td colspan="5" class="text-center text-muted py-3 small">Sin ventas aún.</td></tr>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php if($ventas->hasPages()): ?>
                <div class="card-footer bg-white"><?php echo e($ventas->links()); ?></div>
                <?php endif; ?>
            </div>

        </div>
    </div>
</div>

<script>
// Recalcular total al marcar/desmarcar comisiones
const checkAll  = document.getElementById('check-all');
const checks    = document.querySelectorAll('.comision-check');
const totalEl   = document.getElementById('total-liquidar');

function recalcularTotal() {
    let total = 0;
    checks.forEach(cb => {
        if (cb.checked) total += parseFloat(cb.dataset.monto || 0);
    });
    if (totalEl) totalEl.textContent = '$' + total.toLocaleString('es-CO', { maximumFractionDigits: 0 });
}

if (checkAll) {
    checkAll.addEventListener('change', () => {
        checks.forEach(cb => cb.checked = checkAll.checked);
        recalcularTotal();
    });
}
checks.forEach(cb => cb.addEventListener('change', recalcularTotal));
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Usuario\Documents\My Web Sites\POS\Backend\POSRed\resources\views/vendedores/show.blade.php ENDPATH**/ ?>