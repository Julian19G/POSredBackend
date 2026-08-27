

<?php $__env->startSection('content'); ?>
<div class="container mt-4">
    <div class="card shadow-lg border-0 rounded-3">
        <div class="card-header bg-dark text-white">
            <h4 class="mb-0">🧾 Registrar Detalle de Venta</h4>
        </div>
        <div class="card-body">
            
            <?php if($errors->any()): ?>
                <div class="alert alert-danger">
                    <strong>¡Ups!</strong> Hay algunos errores en el formulario.
                    <ul class="mb-0 mt-2">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="<?php echo e(route('detalle_ventas.store')); ?>" method="POST" id="detalleVentaForm">
                <?php echo csrf_field(); ?>

                
                <div class="mb-3">
                    <label for="venta_id" class="form-label fw-bold">Venta asociada</label>
                    <select name="venta_id" id="venta_id" class="form-select" required>
                        <option value="">Seleccione una venta...</option>
                        <?php $__currentLoopData = $ventas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $venta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($venta->id); ?>" <?php echo e(old('venta_id') == $venta->id ? 'selected' : ''); ?>>
                                Venta #<?php echo e($venta->id); ?> — <?php echo e($venta->created_at->format('d/m/Y')); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                
                <div class="mb-3">
                    <label for="producto_id" class="form-label fw-bold">Producto</label>
                    <select name="producto_id" id="producto_id" class="form-select" required>
                        <option value="">Seleccione un producto...</option>
                        <?php $__currentLoopData = $productos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $producto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($producto->id); ?>" data-precio="<?php echo e($producto->precio); ?>">
                                <?php echo e($producto->nombre); ?> — $<?php echo e(number_format($producto->precio, 2, ',', '.')); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="row">
                    
                    <div class="col-md-4 mb-3">
                        <label for="cantidad" class="form-label fw-bold">Cantidad</label>
                        <input type="number" name="cantidad" id="cantidad" class="form-control" min="1" value="<?php echo e(old('cantidad', 1)); ?>" required>
                    </div>

                    
                    <div class="col-md-4 mb-3">
                        <label for="precio_unitario" class="form-label fw-bold">Precio Unitario</label>
                        <input type="number" name="precio_unitario" id="precio_unitario" class="form-control" step="0.01" min="0" value="<?php echo e(old('precio_unitario')); ?>" required>
                    </div>

                    
                    <div class="col-md-4 mb-3">
                        <label for="descuento_aplicado" class="form-label fw-bold">Descuento</label>
                        <input type="number" name="descuento_aplicado" id="descuento_aplicado" class="form-control" step="0.01" min="0" value="<?php echo e(old('descuento_aplicado', 0)); ?>">
                    </div>
                </div>

                <div class="row">
                    
                    <div class="col-md-4 mb-3">
                        <label for="impuesto" class="form-label fw-bold">Impuesto</label>
                        <input type="number" name="impuesto" id="impuesto" class="form-control" step="0.01" min="0" value="<?php echo e(old('impuesto', 0)); ?>">
                    </div>

                    
                    <div class="col-md-8 mb-3">
                        <label for="subtotal" class="form-label fw-bold">Subtotal (automático)</label>
                        <input type="number" name="subtotal" id="subtotal" class="form-control bg-light" step="0.01" min="0" readonly value="<?php echo e(old('subtotal')); ?>">
                        <small class="text-muted">Subtotal = (Cantidad × Precio) - Descuento + Impuesto</small>
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="<?php echo e(route('detalle_ventas.index')); ?>" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Cancelar
                    </a>
                    <button type="submit" class="btn btn-dark">
                        <i class="bi bi-save"></i> Guardar Detalle
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
    const cantidadEl = document.getElementById('cantidad');
    const precioEl = document.getElementById('precio_unitario');
    const descuentoEl = document.getElementById('descuento_aplicado');
    const impuestoEl = document.getElementById('impuesto');
    const subtotalEl = document.getElementById('subtotal');
    const productoSelect = document.getElementById('producto_id');

    productoSelect.addEventListener('change', function() {
        const precio = this.selectedOptions[0].getAttribute('data-precio') || 0;
        precioEl.value = parseFloat(precio).toFixed(2);
        calcularSubtotal();
    });

    [cantidadEl, precioEl, descuentoEl, impuestoEl].forEach(el => {
        el.addEventListener('input', calcularSubtotal);
    });

    function calcularSubtotal() {
        const cantidad = parseFloat(cantidadEl.value) || 0;
        const precio = parseFloat(precioEl.value) || 0;
        const descuento = parseFloat(descuentoEl.value) || 0;
        const impuesto = parseFloat(impuestoEl.value) || 0;

        const subtotal = (cantidad * precio) - descuento + impuesto;
        subtotalEl.value = subtotal.toFixed(2);
    }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Usuario\Documents\My Web Sites\POS\Backend\POSRed\resources\views\detalle_ventas\create.blade.php ENDPATH**/ ?>