

<?php $__env->startSection('content'); ?>
<div class="container">
    <h1 class="mb-4">✏️ Editar Detalle de Venta #<?php echo e($detalleVenta->id); ?></h1>

    
    <?php if($errors->any()): ?>
        <div class="alert alert-danger">
            <strong>Se encontraron algunos errores:</strong>
            <ul class="mb-0 mt-2">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    
    <form action="<?php echo e(route('detalle_ventas.update', $detalleVenta->id)); ?>" method="POST" class="card shadow-sm p-4">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        
        <div class="mb-3">
            <label for="venta_id" class="form-label fw-semibold">Venta</label>
            <select name="venta_id" id="venta_id" class="form-select" required>
                <option value="">Seleccione una venta</option>
                <?php $__currentLoopData = $ventas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $venta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($venta->id); ?>" <?php echo e(old('venta_id', $detalleVenta->venta_id) == $venta->id ? 'selected' : ''); ?>>
                        Venta #<?php echo e($venta->id); ?> — <?php echo e($venta->created_at->format('d/m/Y')); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>

        
        <div class="mb-3">
            <label for="producto_id" class="form-label fw-semibold">Producto</label>
            <select name="producto_id" id="producto_id" class="form-select" required>
                <option value="">Seleccione un producto</option>
                <?php $__currentLoopData = $productos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $producto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($producto->id); ?>" <?php echo e(old('producto_id', $detalleVenta->producto_id) == $producto->id ? 'selected' : ''); ?>>
                        <?php echo e($producto->nombre); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>

        
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="nombre_producto" class="form-label fw-semibold">Nombre del Producto</label>
                <input type="text" id="nombre_producto" name="nombre_producto" class="form-control" 
                       value="<?php echo e(old('nombre_producto', $detalleVenta->nombre_producto)); ?>" readonly>
            </div>

            <div class="col-md-6 mb-3">
                <label for="codigo_producto" class="form-label fw-semibold">Código del Producto</label>
                <input type="text" id="codigo_producto" name="codigo_producto" class="form-control" 
                       value="<?php echo e(old('codigo_producto', $detalleVenta->codigo_producto)); ?>" readonly>
            </div>
        </div>

        
        <div class="row">
            <div class="col-md-4 mb-3">
                <label for="cantidad" class="form-label fw-semibold">Cantidad</label>
                <input type="number" name="cantidad" id="cantidad" class="form-control" min="1"
                       value="<?php echo e(old('cantidad', $detalleVenta->cantidad)); ?>" required>
            </div>

            <div class="col-md-4 mb-3">
                <label for="precio_unitario" class="form-label fw-semibold">Precio Unitario</label>
                <input type="number" name="precio_unitario" id="precio_unitario" class="form-control"
                       step="0.01" min="0" value="<?php echo e(old('precio_unitario', $detalleVenta->precio_unitario)); ?>" required>
            </div>

            <div class="col-md-4 mb-3">
                <label for="descuento_aplicado" class="form-label fw-semibold">Descuento (opcional)</label>
                <input type="number" name="descuento_aplicado" id="descuento_aplicado" class="form-control"
                       step="0.01" min="0" value="<?php echo e(old('descuento_aplicado', $detalleVenta->descuento_aplicado)); ?>">
            </div>
        </div>

        
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="impuesto" class="form-label fw-semibold">Impuesto (%)</label>
                <input type="number" name="impuesto" id="impuesto" class="form-control"
                       step="0.01" min="0" value="<?php echo e(old('impuesto', $detalleVenta->impuesto)); ?>">
            </div>

            <div class="col-md-6 mb-3">
                <label for="subtotal" class="form-label fw-semibold">Subtotal</label>
                <input type="number" name="subtotal" id="subtotal" class="form-control" 
                       step="0.01" min="0" value="<?php echo e(old('subtotal', $detalleVenta->subtotal)); ?>" readonly>
                <small class="text-muted">Calculado automáticamente (Cantidad × Precio – Descuento + Impuesto)</small>
            </div>
        </div>

        
        <div class="d-flex justify-content-between mt-4">
            <a href="<?php echo e(route('detalle_ventas.index')); ?>" class="btn btn-outline-secondary">
                Cancelar
            </a>
            <button type="submit" class="btn btn-primary">
                💾 Actualizar Detalle
            </button>
        </div>
    </form>
</div>


<script>
document.addEventListener('DOMContentLoaded', () => {
    const cantidad = document.getElementById('cantidad');
    const precio = document.getElementById('precio_unitario');
    const descuento = document.getElementById('descuento_aplicado');
    const impuesto = document.getElementById('impuesto');
    const subtotal = document.getElementById('subtotal');

    function calcularSubtotal() {
        const c = parseFloat(cantidad.value) || 0;
        const p = parseFloat(precio.value) || 0;
        const d = parseFloat(descuento.value) || 0;
        const i = parseFloat(impuesto.value) || 0;

        const base = (c * p) - d;
        const total = base + (base * (i / 100));
        subtotal.value = total.toFixed(2);
    }

    [cantidad, precio, descuento, impuesto].forEach(el => {
        el.addEventListener('input', calcularSubtotal);
    });

    calcularSubtotal();
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Usuario\Documents\My Web Sites\POS\Backend\POSRed\resources\views\detalle_ventas\edit.blade.php ENDPATH**/ ?>