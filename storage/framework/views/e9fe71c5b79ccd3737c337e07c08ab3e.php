<?php $__env->startSection('content'); ?>
<div class="container">

    <div class="d-flex justify-content-between align-items-center mt-3 mb-4">
        <h1 class="mb-0">
            <?php echo e(auth()->user()->isAdmin() ? 'Todas las Rutas' : 'Mis Rutas'); ?>

        </h1>
        <?php if(auth()->user()->isDomiciliario()): ?>
            <a href="<?php echo e(route('rutas.disponibles')); ?>" class="btn btn-success">
                📦 Ver domicilios disponibles
            </a>
        <?php endif; ?>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-bordered align-middle">
            <thead class="table-dark text-center">
                <tr>
                    <th>ID</th>
                    <?php if(auth()->user()->isAdmin()): ?>
                        <th class="text-start">Domiciliario</th>
                    <?php endif; ?>
                    <th>Tipo</th>
                    <th>Estado</th>
                    <th>Domicilios</th>
                    <th>Creada</th>
                    <th>Completada</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $rutas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ruta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="text-center">
                    <td>#<?php echo e($ruta->id); ?></td>
                    <?php if(auth()->user()->isAdmin()): ?>
                        <td class="text-start"><?php echo e($ruta->domiciliario->nombre ?? '—'); ?></td>
                    <?php endif; ?>
                    <td><?php echo e($ruta->tipoLabel()); ?></td>
                    <td><span class="badge bg-<?php echo e($ruta->estadoColor()); ?>"><?php echo e(ucfirst($ruta->estado)); ?></span></td>
                    <td>
                        <?php
                            $total      = $ruta->domicilios->count();
                            $entregados = $ruta->domicilios->where('estado', 'entregado')->count();
                        ?>
                        <div class="progress" style="height:6px;min-width:80px" title="<?php echo e($entregados); ?>/<?php echo e($total); ?>">
                            <div class="progress-bar bg-success"
                                 style="width:<?php echo e($total > 0 ? ($entregados / $total * 100) : 0); ?>%"></div>
                        </div>
                        <small class="text-muted"><?php echo e($entregados); ?>/<?php echo e($total); ?></small>
                    </td>
                    <td><small><?php echo e($ruta->created_at->format('d/m/Y H:i')); ?></small></td>
                    <td>
                        <?php if($ruta->fecha_completada): ?>
                            <small class="text-success"><?php echo e($ruta->fecha_completada->format('d/m/Y H:i')); ?></small>
                        <?php else: ?>
                            <span class="text-muted">—</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="<?php echo e(route('rutas.show', $ruta)); ?>" class="btn btn-sm btn-info">Ver</a>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="<?php echo e(auth()->user()->isAdmin() ? 8 : 7); ?>" class="text-center text-muted py-5">
                        <?php if(auth()->user()->isDomiciliario()): ?>
                            <div class="fs-2 mb-2">🛵</div>
                            No tienes rutas todavía.<br>
                            <a href="<?php echo e(route('rutas.disponibles')); ?>" class="btn btn-success mt-2">
                                Ver domicilios disponibles
                            </a>
                        <?php else: ?>
                            No hay rutas registradas.
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php echo e($rutas->links()); ?>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Usuario\Documents\My Web Sites\POS\Backend\POSRed\resources\views/rutas/index.blade.php ENDPATH**/ ?>