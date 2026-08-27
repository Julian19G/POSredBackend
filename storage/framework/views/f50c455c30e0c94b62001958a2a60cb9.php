<?php $__env->startSection('content'); ?>
<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-4 mt-3">
        <h1 class="mb-0">Agregar stock — <?php echo e($producto->nombre); ?></h1>
        <a href="<?php echo e(route('productos.show', $producto)); ?>" class="btn btn-secondary">← Volver</a>
    </div>

    <?php if($errors->any()): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <ul class="mb-0">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($e); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm rounded-4" style="max-width:520px">
        <div class="card-body p-4">

            <form action="<?php echo e(route('inventarios.store', $producto)); ?>" method="POST">
                <?php echo csrf_field(); ?>

                
                <?php if($producto->variantes->count()): ?>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Variante <small class="text-muted">(opcional — deja vacío para agregar solo al stock base)</small></label>
                    <select name="variante_id" id="variante_id" class="form-select">
                        <option value="">— Solo stock base del producto —</option>
                        <?php $__currentLoopData = $producto->variantes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($v->id); ?>"
                                    data-consume="<?php echo e($v->cantidad_por_variante); ?>"
                                    <?php echo e(old('variante_id') == $v->id ? 'selected' : ''); ?>>
                                <?php echo e($v->nombre); ?> (stock actual: <?php echo e($v->stock); ?>,
                                consume <?php echo e($v->cantidad_por_variante); ?> uds base c/u)
                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <div id="variante-info" class="form-text mt-1"></div>
                </div>
                <?php else: ?>
                <input type="hidden" name="variante_id" value="">
                <?php endif; ?>

                
                <div class="mb-3">
                    <label class="form-label fw-semibold">Cantidad a agregar</label>
                    <input type="number" name="cantidad" id="cantidad"
                           class="form-control <?php $__errorArgs = ['cantidad'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                           min="1" value="<?php echo e(old('cantidad', 1)); ?>" required>
                    <div id="stock-preview" class="form-text mt-1 text-success"></div>
                    <?php $__errorArgs = ['cantidad'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                
                <div class="mb-4">
                    <label class="form-label fw-semibold">Motivo / Descripción <small class="text-muted">(opcional)</small></label>
                    <input type="text" name="descripcion"
                           class="form-control <?php $__errorArgs = ['descripcion'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                           placeholder="Ej: compra de proveedor, corrección de inventario…"
                           value="<?php echo e(old('descripcion')); ?>">
                    <?php $__errorArgs = ['descripcion'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-success px-4">✅ Confirmar entrada</button>
                    <a href="<?php echo e(route('productos.show', $producto)); ?>" class="btn btn-outline-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
const stockBaseActual = <?php echo e($producto->stock); ?>;
const selectVariante  = document.getElementById('variante_id');
const inputCantidad   = document.getElementById('cantidad');
const infoDiv         = document.getElementById('variante-info');
const previewDiv      = document.getElementById('stock-preview');

function actualizar() {
    const opt      = selectVariante?.options[selectVariante.selectedIndex];
    const cantidad = parseInt(inputCantidad.value) || 0;
    const consume  = parseInt(opt?.dataset?.consume || 0);

    if (opt && opt.value && consume > 0) {
        infoDiv.textContent = `Al agregar ${cantidad} pack(s) → se suman ${cantidad * consume} unidades al stock base.`;
    } else if (infoDiv) {
        infoDiv.textContent = '';
    }

    if (cantidad > 0) {
        const nuevaBase = opt?.value
            ? stockBaseActual + (cantidad * consume)
            : stockBaseActual + cantidad;
        previewDiv.textContent = `Stock base: ${stockBaseActual} → ${nuevaBase}`;
    } else {
        previewDiv.textContent = '';
    }
}

selectVariante?.addEventListener('change', actualizar);
inputCantidad.addEventListener('input', actualizar);
actualizar();
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Usuario\Documents\My Web Sites\POS\Backend\POSRed\resources\views\inventarios\create.blade.php ENDPATH**/ ?>