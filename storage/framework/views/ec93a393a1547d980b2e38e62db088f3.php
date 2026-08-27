<?php $__env->startSection('content'); ?>
<div class="container py-4" style="max-width:540px">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0 fs-3">Editar usuario</h1>
        <a href="<?php echo e(route('users.index')); ?>" class="btn btn-outline-secondary btn-sm">← Volver</a>
    </div>

    <?php if($errors->any()): ?>
        <div class="alert alert-danger"><?php echo e($errors->first()); ?></div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">

            
            <div class="mb-4 p-3 bg-light rounded-3">
                <div class="fw-bold"><?php echo e($user->name); ?></div>
                <div class="text-muted small"><?php echo e($user->email); ?></div>
                <div class="text-muted small">Registrado: <?php echo e($user->created_at->format('d/m/Y')); ?></div>
            </div>

            <form action="<?php echo e(route('users.update', $user)); ?>" method="POST">
                <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>

                
                <div class="mb-4">
                    <label class="form-label fw-semibold">Rol en el sistema</label>
                    <div class="d-flex gap-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="role" id="role_admin"
                                   value="admin" <?php echo e(old('role', $user->role) === 'admin' ? 'checked' : ''); ?>>
                            <label class="form-check-label" for="role_admin">
                                <span class="badge bg-danger">Admin</span>
                                <small class="text-muted d-block">Acceso completo + gestión de usuarios</small>
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="role" id="role_vendedor"
                                   value="vendedor" <?php echo e(old('role', $user->role) === 'vendedor' ? 'checked' : ''); ?>>
                            <label class="form-check-label" for="role_vendedor">
                                <span class="badge bg-info text-dark">Vendedor</span>
                                <small class="text-muted d-block">Acceso al sistema POS</small>
                            </label>
                        </div>
                    </div>
                </div>

                
                <div class="mb-4">
                    <label class="form-label fw-semibold">Vincular a perfil de vendedor</label>
                    <select name="vendedor_id" class="form-select">
                        <option value="">— Sin vincular —</option>
                        <?php $__currentLoopData = $vendedoresDisponibles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($v->id); ?>"
                                <?php echo e(old('vendedor_id', $vendedorActual?->id) == $v->id ? 'selected' : ''); ?>>
                                <?php echo e($v->nombre); ?>

                                <?php if($v->comision_porcentaje > 0): ?> (<?php echo e($v->comision_porcentaje); ?>%) <?php endif; ?>
                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <div class="form-text">Permite asociar las ventas de este usuario a un registro de vendedor para calcular comisiones.</div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Guardar cambios</button>
                    <a href="<?php echo e(route('users.index')); ?>" class="btn btn-outline-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Usuario\Documents\My Web Sites\POS\Backend\POSRed\resources\views\users\edit.blade.php ENDPATH**/ ?>