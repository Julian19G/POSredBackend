<?php $__env->startSection('content'); ?>
<div class="container">

    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show mt-3">
            <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-3 mt-3">
        <h1 class="mb-0">Productos</h1>
        <a href="<?php echo e(route('productos.create')); ?>" class="btn btn-primary">➕ Nuevo Producto</a>
    </div>

    
    <form method="GET" action="<?php echo e(route('productos.index')); ?>" class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-body py-3">
            <div class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label class="form-label small mb-1">Buscar</label>
                    <input type="text" name="buscar" class="form-control form-control-sm"
                           placeholder="Nombre del producto…" value="<?php echo e(request('buscar')); ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label small mb-1">Categoría</label>
                    <select name="categoria_id" class="form-select form-select-sm">
                        <option value="">Todas</option>
                        <?php $__currentLoopData = $categorias; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($cat->id); ?>" <?php echo e(request('categoria_id') == $cat->id ? 'selected' : ''); ?>>
                                <?php echo e($cat->nombre); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small mb-1">Estado</label>
                    <select name="activo" class="form-select form-select-sm">
                        <option value="">Todos</option>
                        <option value="1" <?php echo e(request('activo') === '1' ? 'selected' : ''); ?>>Activos</option>
                        <option value="0" <?php echo e(request('activo') === '0' ? 'selected' : ''); ?>>Inactivos</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2 align-items-end">
                    <div class="form-check mb-0 me-2">
                        <input class="form-check-input" type="checkbox" name="stock_bajo" id="stock_bajo"
                               <?php echo e(request()->has('stock_bajo') ? 'checked' : ''); ?>>
                        <label class="form-check-label small" for="stock_bajo">Stock bajo (≤10)</label>
                    </div>
                    <button type="submit" class="btn btn-sm btn-primary">Filtrar</button>
                    <a href="<?php echo e(route('productos.index')); ?>" class="btn btn-sm btn-outline-secondary">✕</a>
                </div>
            </div>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle text-center">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Imagen</th>
                    <th class="text-start">Nombre</th>
                    <th>Categoría</th>
                    <th>Variantes / Precios</th>
                    <th>Stock base</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $productos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $producto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($producto->id); ?></td>
                    <td>
                        <?php if($producto->imagen): ?>
                            <img src="<?php echo e(asset('storage/' . $producto->imagen)); ?>"
                                 alt="<?php echo e($producto->nombre); ?>"
                                 style="width:50px;height:50px;object-fit:cover;border-radius:6px">
                        <?php else: ?>
                            <span class="text-muted small">—</span>
                        <?php endif; ?>
                    </td>
                    <td class="text-start">
                        <strong><?php echo e($producto->nombre); ?></strong>
                        <?php if($producto->descripcion): ?>
                            <br><small class="text-muted"><?php echo e(Str::limit($producto->descripcion, 40)); ?></small>
                        <?php endif; ?>
                    </td>
                    <td><?php echo e($producto->categoria->nombre ?? '—'); ?></td>
                    <td class="text-start" style="max-width:180px">
                        <?php $__empty_2 = true; $__currentLoopData = $producto->variantes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                            <span class="badge bg-dark mb-1"><?php echo e($v->nombre); ?> — $<?php echo e(number_format($v->precio, 0, ',', '.')); ?>

                                <?php if($v->stock <= 5): ?> <span class="text-warning">⚠<?php echo e($v->stock); ?></span>
                                <?php else: ?>
                                <?php endif; ?>
                            </span><br>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                            <span class="text-muted small">Sin variantes</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if($producto->stock <= 10): ?>
                            <span class="badge bg-warning text-dark">⚠ <?php echo e($producto->stock); ?></span>
                        <?php else: ?>
                            <?php echo e($producto->stock); ?>

                        <?php endif; ?>
                    </td>
                    <td>
                        <span class="badge <?php echo e($producto->activo ? 'bg-success' : 'bg-secondary'); ?>">
                            <?php echo e($producto->activo ? 'Activo' : 'Inactivo'); ?>

                        </span>
                    </td>
                    <td>
                        <a href="<?php echo e(route('productos.show', $producto)); ?>" class="btn btn-info btn-sm">Ver</a>
                        <a href="<?php echo e(route('inventarios.create', $producto)); ?>" class="btn btn-sm btn-outline-success" title="Agregar stock">+Stock</a>
                        <a href="<?php echo e(route('productos.edit', $producto)); ?>" class="btn btn-warning btn-sm">Editar</a>
                        <form action="<?php echo e(route('productos.destroy', $producto)); ?>" method="POST" class="d-inline">
                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="btn btn-danger btn-sm"
                                    onclick="return confirm('¿Eliminar este producto?')">Eliminar</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="8" class="text-center text-muted py-4">No hay productos.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if($productos->hasPages()): ?>
        <div class="d-flex justify-content-between align-items-center mt-3">
            <?php echo e($productos->links()); ?>

            <small class="text-muted"><?php echo e($productos->total()); ?> producto(s)</small>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Usuario\Documents\My Web Sites\POS\Backend\POSRed\resources\views/productos/index.blade.php ENDPATH**/ ?>