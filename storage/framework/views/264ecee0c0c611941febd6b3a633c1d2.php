

<?php $__env->startSection('content'); ?>
<div class="container">
    <h2>Clientes</h2>

    <?php if(session('success')): ?>
        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
    <?php endif; ?>

    <a href="<?php echo e(route('clientes.create')); ?>" class="btn btn-primary mb-3">+ Nuevo Cliente</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Email</th>
                <th>Teléfono</th>
                <th>Referido Por</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $clientes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cliente): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($cliente->nombre); ?></td>
                    <td><?php echo e($cliente->email); ?></td>
                    <td><?php echo e($cliente->telefono); ?></td>
                    <td><?php echo e($cliente->referidoPor->nombre ?? 'N/A'); ?></td>
                    <td>
                        <a href="<?php echo e(route('clientes.show', $cliente)); ?>" class="btn btn-sm btn-info">Ver</a>
                        <a href="<?php echo e(route('clientes.edit', $cliente)); ?>" class="btn btn-sm btn-warning">Editar</a>
                        <form action="<?php echo e(route('clientes.destroy', $cliente)); ?>" method="POST" class="d-inline">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button onclick="return confirm('¿Seguro?')" class="btn btn-sm btn-danger">Eliminar</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Usuario\Documents\My Web Sites\POS\Backend\POSRed\resources\views/clientes/index.blade.php ENDPATH**/ ?>