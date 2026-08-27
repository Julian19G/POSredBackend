<?php $__env->startSection('content'); ?>
<div class="container" style="max-width:520px">
    <div class="mt-4 mb-3">
        <h1>Editar Domiciliario</h1>
    </div>

    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-4">
            <form action="<?php echo e(route('domiciliarios.update', $d)); ?>" method="POST">
                <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>

                <div class="mb-3">
                    <label class="form-label">Nombre completo</label>
                    <input type="text" name="nombre" class="form-control"
                           value="<?php echo e(old('nombre', $d->nombre)); ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Teléfono</label>
                    <input type="text" name="telefono" class="form-control"
                           value="<?php echo e(old('telefono', $d->telefono)); ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">Vehículo</label>
                    <select name="vehiculo" class="form-select" required>
                        <?php $__currentLoopData = ['moto' => '🏍 Moto', 'bicicleta' => '🚲 Bicicleta', 'pie' => '🚶 A pie', 'carro' => '🚗 Carro']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($val); ?>" <?php echo e(old('vehiculo', $d->vehiculo) === $val ? 'selected' : ''); ?>><?php echo e($label); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="form-check form-switch mb-4">
                    <input class="form-check-input" type="checkbox" name="activo" id="activo"
                           <?php echo e(old('activo', $d->activo) ? 'checked' : ''); ?>>
                    <label class="form-check-label" for="activo">Activo</label>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-fill">Guardar cambios</button>
                    <a href="<?php echo e(route('domiciliarios.show', $d)); ?>" class="btn btn-outline-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Usuario\Documents\My Web Sites\POS\Backend\POSRed\resources\views\domiciliarios\edit.blade.php ENDPATH**/ ?>