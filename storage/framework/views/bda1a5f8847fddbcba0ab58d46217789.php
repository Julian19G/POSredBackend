

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-6">

    <h1 class="text-2xl font-bold text-gray-800 mb-6">Editar Sabor</h1>

    <div class="max-w-xl bg-white shadow p-6 rounded-lg">

        <form action="<?php echo e(route('sabores.update', $sabor)); ?>" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            
            <div class="mb-4">
                <label class="block font-semibold text-gray-700 mb-1">Nombre</label>
                <input type="text" name="nombre" value="<?php echo e(old('nombre', $sabor->nombre)); ?>"
                       class="w-full border-gray-300 rounded-lg shadow-sm"
                       required>
                <?php $__errorArgs = ['nombre'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-red-600 text-sm mt-1"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            
            <div class="mb-4">
                <label class="block font-semibold text-gray-700 mb-1">Descripción</label>
                <textarea name="descripcion" rows="3"
                          class="w-full border-gray-300 rounded-lg shadow-sm"><?php echo e(old('descripcion', $sabor->descripcion)); ?></textarea>
            </div>

            
            <div class="mb-4">
                <label class="block font-semibold text-gray-700 mb-1">Intensidad</label>
                <select name="intensidad" class="w-full border-gray-300 rounded-lg shadow-sm">
                    <?php for($i = 1; $i <= 10; $i++): ?>
                        <option value="<?php echo e($i); ?>" <?php echo e($sabor->intensidad == $i ? 'selected' : ''); ?>>
                            <?php echo e($i); ?>

                        </option>
                    <?php endfor; ?>
                </select>
            </div>


            
            <div class="mb-3">
                <label class="form-label">Estado</label>
                <select name="activo" class="form-select <?php $__errorArgs = ['activo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                    <option value="1" <?php echo e(old('activo', 1) == 1 ? 'selected' : ''); ?>>Activo</option>
                    <option value="0" <?php echo e(old('activo') == 0 ? 'selected' : ''); ?>>Inactivo</option>
                </select>

                <?php $__errorArgs = ['activo'];
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

            
            <div class="mb-4">
                <label class="block font-semibold text-gray-700 mb-1">Imagen actual</label>

                <?php if($sabor->imagen): ?>
                    <img src="<?php echo e(asset('storage/' . $sabor->imagen)); ?>" alt="imagen sabor"
                         class="h-20 w-20 object-cover rounded border mb-2">
                <?php else: ?>
                    <p class="text-gray-500 italic">No hay imagen</p>
                <?php endif; ?>
            </div>

            
            <div class="mb-6">
                <label class="block font-semibold text-gray-700 mb-1">Cambiar imagen</label>
                <input type="file" name="imagen"
                       class="w-full border-gray-300 rounded-lg shadow-sm">
            </div>

            
            <div class="flex items-center justify-between">
                <a href="<?php echo e(route('sabores.index')); ?>"
                   class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded">
                    Cancelar
                </a>

                <button type="submit"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded shadow">
                    Guardar cambios
                </button>
            </div>

        </form>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Usuario\Documents\My Web Sites\POS\Backend\POSRed\resources\views\sabores\edit.blade.php ENDPATH**/ ?>