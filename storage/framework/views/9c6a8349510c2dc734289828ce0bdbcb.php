

<?php $__env->startSection('content'); ?>
<div class="container">
    <h2>Editar Clientes</h2>

    <form action="<?php echo e(route('clientes.update',$cliente)); ?>" method="POST">
        <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>

        <?php echo $__env->make('clientes.form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <button type="submit" class="btn btn-primary">Actualizar</button>
        <a href="<?php echo e(route('clientes.index')); ?>" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Usuario\Documents\My Web Sites\POS\Backend\POSRed\resources\views\clientes\edit.blade.php ENDPATH**/ ?>