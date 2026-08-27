<?php $__env->startSection('title', 'Recuperar contraseña'); ?>

<?php $__env->startSection('content'); ?>
<h4 class="fw-bold mb-2 text-center">Recuperar contraseña</h4>
<p class="text-muted small text-center mb-4">
    Ingresa tu correo y te enviaremos un enlace para restablecer tu contraseña.
</p>

<?php if(session('status')): ?>
    <div class="alert alert-success"><?php echo e(session('status')); ?></div>
<?php endif; ?>

<form action="<?php echo e(route('password.email')); ?>" method="POST">
    <?php echo csrf_field(); ?>

    <div class="mb-4">
        <label class="form-label fw-semibold">Correo electrónico</label>
        <input type="email" name="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
               value="<?php echo e(old('email')); ?>" autofocus required>
        <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

    <button type="submit" class="btn btn-dark w-100 fw-semibold">Enviar enlace</button>
</form>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('footer-links'); ?>
    <a href="<?php echo e(route('login')); ?>">← Volver al inicio de sesión</a>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.auth', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Usuario\Documents\My Web Sites\POS\Backend\POSRed\resources\views\auth\forgot-password.blade.php ENDPATH**/ ?>