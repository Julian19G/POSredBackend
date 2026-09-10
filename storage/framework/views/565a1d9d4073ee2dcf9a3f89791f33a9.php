<?php $__env->startSection('content'); ?>
<div class="container py-4" style="max-width:760px">

    <h1 class="mb-1">🌸 Tipos de flor</h1>
    <p class="text-muted">Etiquetas para la categoría <strong>Flores</strong> (Sativa, Índica, Indoor…). El emoji se muestra en la tienda.</p>

    
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body">
            <form action="<?php echo e(route('tipos-flor.store')); ?>" method="POST" class="row g-2 align-items-end">
                <?php echo csrf_field(); ?>
                <div class="col-md-5">
                    <label class="form-label small fw-semibold">Nombre</label>
                    <input type="text" name="nombre" class="form-control" placeholder="Ej: Sativa" value="<?php echo e(old('nombre')); ?>" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-semibold">Emoji</label>
                    <input type="text" name="icono" class="form-control text-center" placeholder="🌿" maxlength="16" value="<?php echo e(old('icono')); ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-semibold">Descripción</label>
                    <input type="text" name="descripcion" class="form-control" value="<?php echo e(old('descripcion')); ?>">
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
                    <tr><th class="ps-3">Tipo</th><th>Descripción</th><th class="text-end pe-3">Acción</th></tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $tipos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="ps-3 fw-semibold"><?php echo e($t->icono); ?> <?php echo e($t->nombre); ?></td>
                        <td class="text-muted small"><?php echo e($t->descripcion ?? '—'); ?></td>
                        <td class="text-end pe-3">
                            <form action="<?php echo e(route('tipos-flor.destroy', $t)); ?>" method="POST" class="d-inline">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button class="btn btn-outline-danger btn-sm"
                                        data-confirm="¿Eliminar el tipo «<?php echo e($t->nombre); ?>»?">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="3" class="text-center text-muted py-3">Sin tipos aún</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Usuario\Documents\My Web Sites\POS\Backend\POSRed\resources\views/tipos_flor/index.blade.php ENDPATH**/ ?>