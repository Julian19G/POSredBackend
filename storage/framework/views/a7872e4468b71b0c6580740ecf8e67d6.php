<?php $__env->startSection('content'); ?>
<div class="container py-3">

    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">👥 Usuarios del sistema</h1>
        <a href="<?php echo e(route('register')); ?>" class="btn btn-primary btn-sm">➕ Nuevo usuario</a>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="ps-4">Nombre</th>
                            <th>Email</th>
                            <th>Rol</th>
                            <th>Vendedor vinculado</th>
                            <th>Registro</th>
                            <th class="text-end pe-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td class="ps-4 fw-semibold">
                            <?php echo e($u->name); ?>

                            <?php if($u->id === auth()->id()): ?>
                                <span class="badge bg-secondary ms-1" style="font-size:.7rem">Tú</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-muted small"><?php echo e($u->email); ?></td>
                        <td>
                            <span class="badge <?php echo e($u->isAdmin() ? 'bg-danger' : 'bg-info text-dark'); ?>">
                                <?php echo e(ucfirst($u->role)); ?>

                            </span>
                        </td>
                        <td>
                            <?php if($u->vendedor): ?>
                                <a href="<?php echo e(route('vendedores.show', $u->vendedor)); ?>" class="text-decoration-none small">
                                    <?php echo e($u->vendedor->nombre); ?>

                                </a>
                            <?php else: ?>
                                <span class="text-muted small">—</span>
                            <?php endif; ?>
                        </td>
                        <td><small class="text-muted"><?php echo e($u->created_at->format('d/m/Y')); ?></small></td>
                        <td class="text-end pe-4">
                            <a href="<?php echo e(route('users.edit', $u)); ?>" class="btn btn-sm btn-warning">✏️ Editar</a>
                            <?php if($u->id !== auth()->id()): ?>
                            <form action="<?php echo e(route('users.destroy', $u)); ?>" method="POST" class="d-inline"
                                  onsubmit="return confirm('¿Eliminar al usuario <?php echo e(addslashes($u->name)); ?>?')">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button class="btn btn-sm btn-outline-danger">🗑</button>
                            </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Usuario\Documents\My Web Sites\POS\Backend\POSRed\resources\views\users\index.blade.php ENDPATH**/ ?>