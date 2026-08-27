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