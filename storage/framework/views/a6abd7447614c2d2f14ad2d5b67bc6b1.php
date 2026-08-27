<?php $__env->startSection('title', 'Iniciar sesión'); ?>

<?php $__env->startSection('content'); ?>
<h4 class="fw-bold mb-4 text-center">Iniciar sesión</h4>

<?php if(session('status')): ?>
    <div class="alert alert-success"><?php echo e(session('status')); ?></div>
<?php endif; ?>

<form action="<?php echo e(route('login.post')); ?>" method="POST">
    <?php echo csrf_field(); ?>

    <div class="mb-3">
        <label class="form-label fw-semibold">Correo electrónico</label>
        <input type="email" name="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
               value="<?php echo e(old('email')); ?>" autofocus required autocomplete="email">
        <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <div class="invalid-feedback"><?php echo e($message); ?></div>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

    <div class="mb-3">
        <div class="d-flex justify-content-between">
            <label class="form-label fw-semibold">Contraseña</label>
            <a href="<?php echo e(route('password.request')); ?>" class="small">¿Olvidaste tu contraseña?</a>
        </div>
        <input type="password" name="password" class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
               required autocomplete="current-password">
        <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <div class="invalid-feedback"><?php echo e($message); ?></div>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

    <div class="mb-4 form-check">
        <input type="checkbox" name="remember" id="remember" class="form-check-input">
        <label class="form-check-label" for="remember">Recordarme</label>
    </div>

    <button type="submit" class="btn btn-dark w-100 fw-semibold">Entrar</button>
</form>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('footer-links'); ?>
    ¿No tienes cuenta? <a href="<?php echo e(route('register')); ?>">Regístrate</a>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.auth', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Usuario\Documents\My Web Sites\POS\Backend\POSRed\resources\views\auth\login.blade.php ENDPATH**/ ?>