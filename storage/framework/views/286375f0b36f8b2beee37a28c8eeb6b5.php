<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="d-flex justify-content-between align-items-center mt-3 mb-4">
        <div>
            <h1 class="mb-0"><?php echo e($d->nombre); ?></h1>
            <small class="text-muted"><?php echo e($d->vehiculoLabel()); ?> &bull; <?php echo e($d->user->email); ?></small>
        </div>
        <?php if(auth()->user()->isAdmin()): ?>
            <div class="d-flex gap-2">
                <a href="<?php echo e(route('domiciliarios.edit', $d)); ?>" class="btn btn-warning">Editar</a>
                <a href="<?php echo e(route('domiciliarios.index')); ?>" class="btn btn-outline-secondary">Volver</a>
            </div>
        <?php endif; ?>
    </div>

    <?php if(auth()->user()->isAdmin() || auth()->user()->domiciliario?->id === $d->id): ?>
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white fw-semibold">📝 Registrar entrega manual</div>
        <div class="card-body">
            <?php if($errors->any()): ?>
                <div class="alert alert-danger"><?php echo e($errors->first()); ?></div>
            <?php endif; ?>
            <form action="<?php echo e(route('domiciliarios.entrega-manual', $d)); ?>" method="POST" class="row g-3">
                <?php echo csrf_field(); ?>
                <div class="col-md-4">
                    <label class="form-label">Venta</label>
                    <select name="venta_id" class="form-select">
                        <option value="">Externa / sin venta</option>
                        <?php $__currentLoopData = $ventasDisponibles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $venta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($venta->id); ?>">#<?php echo e($venta->id); ?> · <?php echo e($venta->cliente->nombre ?? 'Sin cliente'); ?> · $<?php echo e(number_format($venta->total, 0, ',', '.')); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Cliente externo</label>
                    <input type="text" name="cliente_nombre" class="form-control" placeholder="Nombre del cliente">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Teléfono externo</label>
                    <input type="text" name="cliente_telefono" class="form-control" placeholder="Teléfono del cliente">
                </div>
                <div class="col-md-5">
                    <label class="form-label">Dirección</label>
                    <input type="text" name="direccion" class="form-control" required placeholder="Dirección de entrega">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Ciudad / barrio</label>
                    <input type="text" name="ciudad" class="form-control">
                </div>
                <div class="col-12">
                    <label class="form-label">Comentarios</label>
                    <input type="text" name="comentarios" class="form-control" placeholder="Nota opcional">
                </div>
                <div class="col-12 d-flex justify-content-between align-items-center">
                    <small class="text-muted">Puedes seleccionar una venta o registrar una entrega externa. Se asignará la tarifa fija vigente.</small>
                    <button class="btn btn-success">✅ Registrar entrega</button>
                </div>
            </form>
        </div>
    </div>
    <?php endif; ?>

    
    <div class="row g-3 mb-4">
        <div class="col-sm-4">
            <div class="card border-0 shadow-sm text-center py-3">
                <div class="fs-2 fw-bold text-primary"><?php echo e($d->entregas_count); ?></div>
                <div class="text-muted small">Entregas totales</div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="card border-0 shadow-sm text-center py-3">
                <div class="fs-2 fw-bold text-success"><?php echo e($d->entregasMes()); ?></div>
                <div class="text-muted small">Este mes</div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="card border-0 shadow-sm text-center py-3">
                <div class="fs-2 fw-bold <?php echo e($d->activo ? 'text-success' : 'text-secondary'); ?>">
                    <?php echo e($d->activo ? 'Activo' : 'Inactivo'); ?>

                </div>
                <div class="text-muted small">Estado</div>
            </div>
        </div>
    </div>

    
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white fw-semibold">Últimas rutas</div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-light text-center">
                    <tr>
                        <th>ID</th>
                        <th>Tipo</th>
                        <th>Estado</th>
                        <th>Domicilios</th>
                        <th>Fecha</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $d->rutas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ruta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="text-center">
                        <td>#<?php echo e($ruta->id); ?></td>
                        <td><?php echo e($ruta->tipoLabel()); ?></td>
                        <td><span class="badge bg-<?php echo e($ruta->estadoColor()); ?>"><?php echo e(ucfirst($ruta->estado)); ?></span></td>
                        <td><?php echo e($ruta->domicilios_count ?? '—'); ?></td>
                        <td><small><?php echo e($ruta->created_at->format('d/m/Y H:i')); ?></small></td>
                        <td><a href="<?php echo e(route('rutas.show', $ruta)); ?>" class="btn btn-xs btn-sm btn-outline-info">Ver</a></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="6" class="text-center text-muted py-3">Sin rutas registradas.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Usuario\Documents\My Web Sites\POS\Backend\POSRed\resources\views\domiciliarios\show.blade.php ENDPATH**/ ?>