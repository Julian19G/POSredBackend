<div class="mb-3">
    <label for="nombre" class="form-label">Nombre</label>
    <input id="nombre" type="text" name="nombre" class="form-control" value="<?php echo e(old('nombre', $cliente->nombre ?? '')); ?>" required>
</div>

<div class="mb-3">
    <label for="email" class="form-label">Email</label>
    <input id="email" type="email" name="email" class="form-control" value="<?php echo e(old('email', $cliente->email ?? '')); ?>" required>
</div>

<div class="mb-3">
    <label for="telefono" class="form-label">Teléfono</label>
    <input id="telefono" type="text" name="telefono" class="form-control" value="<?php echo e(old('telefono', $cliente->telefono ?? '')); ?>" required>
</div>

<div class="mb-3">
    <label for="direccion" class="form-label">Dirección</label>
    <input id="direccion" type="text" name="direccion" class="form-control" value="<?php echo e(old('direccion', $cliente->direccion ?? '')); ?>" required>
</div>

<div class="mb-3">
    <label for="referido_por" class="form-label">¿Quién lo refirió?</label>
    <select id="referido_por" name="referido_por" class="form-control">
        <option value="">-- Ninguno --</option>
        <?php $__currentLoopData = $clientes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($c->id); ?>"
                <?php echo e(old('referido_por', $cliente->referido_por ?? '') == $c->id ? 'selected' : ''); ?>>
                <?php echo e($c->nombre); ?>

            </option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
</div>

<?php if($errors->any()): ?>
    <div class="alert alert-danger mt-2">
        <ul class="mb-0">
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><?php echo e($error); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
<?php endif; ?>
<?php /**PATH C:\Users\Usuario\Documents\My Web Sites\POS\Backend\POSRed\resources\views/clientes/form.blade.php ENDPATH**/ ?>