<?php $__env->startSection('content'); ?>
<div class="container py-3" style="max-width:640px">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">➕ Nuevo Vendedor</h1>
        <a href="<?php echo e(route('vendedores.index')); ?>" class="btn btn-outline-secondary btn-sm">← Volver</a>
    </div>

    <?php if($errors->any()): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <ul class="mb-0"><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($e); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <form action="<?php echo e(route('vendedores.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <?php echo $__env->make('vendedores._form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-primary px-4">💾 Guardar</button>
                    <a href="<?php echo e(route('vendedores.index')); ?>" class="btn btn-outline-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Usuario\Documents\My Web Sites\POS\Backend\POSRed\resources\views\vendedores\create.blade.php ENDPATH**/ ?>