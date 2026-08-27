<?php $__env->startSection('content'); ?>
<div class="container py-4" style="max-width:760px">

    <h1 class="mb-1">📦 Presentaciones</h1>
    <p class="text-muted">Define una vez los paquetes (x10, x20…) y reutilízalos al crear productos. La <strong>cantidad</strong> son las unidades base que consume cada paquete.</p>

    
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body">
            <form action="<?php echo e(route('presentaciones.store')); ?>" method="POST" class="row g-2 align-items-end">
                <?php echo csrf_field(); ?>
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">Nombre</label>
                    <input type="text" name="nombre" class="form-control" placeholder="Ej: Paquete x10" value="<?php echo e(old('nombre')); ?>" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-semibold">Cantidad (unidades)</label>
                    <input type="number" name="cantidad" class="form-control" step="any" min="0.01" placeholder="10 o 0.5" value="<?php echo e(old('cantidad')); ?>" required>
                </div>
                <div class="col-md-2 d-grid">
                    <button class="btn btn-primary">Agregar</button>
                </div>
            </form>
        </div>
    </div>

    
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <table class="table mb-0 align-middle">
                <thead class="table-light">
                    <tr><th class="ps-3">Nombre</th><th>Cantidad</th><th class="text-end pe-3">Acción</th></tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $presentaciones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="ps-3 fw-semibold"><?php echo e($p->nombre); ?></td>
                        <td><?php echo e($p->cantidad); ?> uds</td>
                        <td class="text-end pe-3">
                            <form action="<?php echo e(route('presentaciones.destroy', $p)); ?>" method="POST" class="d-inline">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button class="btn btn-outline-danger btn-sm"
                                        data-confirm="¿Eliminar la presentación «<?php echo e($p->nombre); ?>»?">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="3" class="text-center text-muted py-3">Sin presentaciones aún</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Usuario\Documents\My Web Sites\POS\Backend\POSRed\resources\views\presentaciones\index.blade.php ENDPATH**/ ?>