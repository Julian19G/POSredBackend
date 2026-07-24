

<?php $__env->startSection('content'); ?>
<div class="container">
    <h1 class="fw-bold mb-4">Editar Categoría</h1>

    
    <?php if($errors->any()): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body">
            <form action="<?php echo e(route('categorias.update', $categoria)); ?>" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                
                <div class="mb-3">
                    <label for="nombre" class="form-label fw-semibold">Nombre</label>
                    <input type="text" 
                           name="nombre" 
                           id="nombre" 
                           class="form-control" 
                           value="<?php echo e(old('nombre', $categoria->nombre)); ?>" 
                           required>
                </div>

                
                <div class="mb-3">
                    <label for="descripcion" class="form-label fw-semibold">Descripción</label>
                    <textarea name="descripcion" 
                              id="descripcion" 
                              class="form-control" 
                              rows="3"><?php echo e(old('descripcion', $categoria->descripcion)); ?></textarea>
                </div>

                
                <div class="mb-3">
                    <label for="imagen" class="form-label fw-semibold">Imagen</label>
                    <?php if($categoria->imagen): ?>
                        <div class="mb-2">
                            <img src="<?php echo e(asset('storage/' . $categoria->imagen)); ?>" 
                                 alt="<?php echo e($categoria->nombre); ?>" 
                                 class="img-thumbnail rounded" 
                                 style="max-width: 150px; height: auto;">
                        </div>
                    <?php endif; ?>
                    <input type="file" name="imagen" id="imagen" class="form-control">
                    <small class="text-muted">Sube una nueva imagen si deseas reemplazar la actual.</small>
                </div>

                
                <div class="mb-4">
                    <label for="activa" class="form-label fw-semibold">Estado</label>
                    <select class="form-select" name="activa" id="activa">
                        <option value="1" <?php echo e(old('activa', $categoria->activa) == 1 ? 'selected' : ''); ?>>Activa</option>
                        <option value="0" <?php echo e(old('activa', $categoria->activa) == 0 ? 'selected' : ''); ?>>Inactiva</option>
                    </select>
                </div>

                
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle"></i> Actualizar
                    </button>
                    <a href="<?php echo e(route('categorias.index')); ?>" class="btn btn-secondary">
                        <i class="bi bi-arrow-left-circle"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Usuario\Documents\My Web Sites\POS\Backend\POSRed\resources\views/categorias/edit.blade.php ENDPATH**/ ?>