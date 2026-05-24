<?php $__env->startSection('content'); ?>
<div class="container">

    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show mt-3">
            <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-3 mt-3">
        <h1 class="mb-0">Clientes</h1>
        <a href="<?php echo e(route('clientes.create')); ?>" class="btn btn-primary">➕ Nuevo Cliente</a>
    </div>

    
    <form method="GET" action="<?php echo e(route('clientes.index')); ?>" class="mb-4">
        <div class="input-group">
            <input type="text" name="buscar" class="form-control"
                   placeholder="Buscar por nombre, teléfono o email…"
                   value="<?php echo e(request('buscar')); ?>">
            <button type="submit" class="btn btn-primary">Buscar</button>
            <?php if(request('buscar')): ?>
                <a href="<?php echo e(route('clientes.index')); ?>" class="btn btn-outline-secondary">✕ Limpiar</a>
            <?php endif; ?>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Nombre</th>
                    <th>Teléfono</th>
                    <th>Email</th>
                    <th>Referido por</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $clientes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cliente): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td class="fw-semibold"><?php echo e($cliente->nombre); ?></td>
                    <td><?php echo e($cliente->telefono); ?></td>
                    <td><?php echo e($cliente->email); ?></td>
                    <td><?php echo e($cliente->referidoPor->nombre ?? '—'); ?></td>
                    <td class="text-center">
                        <a href="<?php echo e(route('clientes.show', $cliente)); ?>" class="btn btn-sm btn-info">Ver</a>
                        <a href="<?php echo e(route('clientes.edit', $cliente)); ?>" class="btn btn-sm btn-warning">Editar</a>
                        <?php if(auth()->user()->isAdmin()): ?>
                            <form action="<?php echo e(route('clientes.destroy', $cliente)); ?>" method="POST" class="d-inline">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button class="btn btn-sm btn-danger"
                                        data-confirm="Se eliminará el cliente permanentemente.">Eliminar</button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="5" class="text-center text-muted py-4">
                        <?php if(request('buscar')): ?>
                            No se encontraron clientes con "<?php echo e(request('buscar')); ?>"
                        <?php else: ?>
                            No hay clientes registrados.
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if($clientes->hasPages()): ?>
        <div class="d-flex justify-content-between align-items-center mt-3">
            <?php echo e($clientes->links()); ?>

            <small class="text-muted"><?php echo e($clientes->total()); ?> cliente(s)</small>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Usuario\Documents\My Web Sites\POS\Backend\POSRed\resources\views/clientes/index.blade.php ENDPATH**/ ?>