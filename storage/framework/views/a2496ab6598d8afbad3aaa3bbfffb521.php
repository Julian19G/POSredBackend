

<?php $__env->startSection('content'); ?>
<div class="container">
    <h1 class="mb-4">✏️ Editar Venta #<?php echo e($venta->id); ?></h1>

    
    <?php if($errors->any()): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="<?php echo e(route('ventas.update', $venta->id)); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        
        <div class="mb-3">
            <label for="cliente_id" class="form-label fw-semibold">Cliente</label>
            <select name="cliente_id" id="cliente_id" class="form-select" required>
                <?php $__currentLoopData = $clientes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cliente): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($cliente->id); ?>" <?php echo e($venta->cliente_id == $cliente->id ? 'selected' : ''); ?>>
                        <?php echo e($cliente->nombre); ?> - <?php echo e($cliente->telefono); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>

        
        <h5 class="mt-4">🧾 Productos</h5>
        <div id="productos-container">
            <?php $__currentLoopData = $venta->detalles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detalle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="producto-row row mb-3 g-2 align-items-end">
                <div class="col-md-4">
                    <label class="form-label">Producto</label>
                    <select name="productos[]" class="form-select" required>
                        <option value="">Seleccione un producto</option>
                        <?php $__currentLoopData = $productos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $producto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($producto->id); ?>" <?php echo e($detalle->producto_id == $producto->id ? 'selected' : ''); ?>>
                                <?php echo e($producto->nombre); ?> - $<?php echo e(number_format($producto->precio, 2, ',', '.')); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Cantidad</label>
                    <input type="number" name="cantidades[]" class="form-control" min="1" value="<?php echo e($detalle->cantidad); ?>" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Descuento manual ($)</label>
                    <input type="number" name="descuentos[]" class="form-control" step="0.01" min="0" value="<?php echo e($detalle->descuento_manual ?? 0); ?>">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Subtotal</label>
                    <input type="text" class="form-control subtotal" value="$<?php echo e(number_format(($detalle->precio_unitario * $detalle->cantidad) - $detalle->descuento_manual, 2, ',', '.')); ?>" readonly>
                </div>
                <div class="col-md-1 text-center">
                    <button type="button" class="btn btn-danger btn-remove">✖</button>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <button type="button" id="add-producto" class="btn btn-secondary mb-3">➕ Agregar otro producto</button>

        
        <h5 class="mt-4">🚚 Envío</h5>
        <div class="row mb-3">
            <div class="col-md-4">
                <label class="form-label">¿Requiere envío?</label>
                <select name="envio" id="envio" class="form-select" required>
                    <option value="0" <?php echo e(!$venta->envio ? 'selected' : ''); ?>>No</option>
                    <option value="1" <?php echo e($venta->envio ? 'selected' : ''); ?>>Sí</option>
                </select>
            </div>
            <div class="col-md-4">
                <label for="costo_envio" class="form-label">Costo de envío ($)</label>
                <input type="number" name="costo_envio" id="costo_envio" class="form-control" step="0.01" min="0" value="<?php echo e($venta->costo_envio ?? 0); ?>">
            </div>
            <div class="col-md-4">
                <label for="direccion_envio" class="form-label">Dirección</label>
                <input type="text" name="direccion_envio" id="direccion_envio" class="form-control" value="<?php echo e($venta->direccion_envio); ?>">
            </div>
        </div>

        
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="descuento_manual" class="form-label fw-semibold">Descuento total ($)</label>
                <input type="number" name="descuento_manual" id="descuento_manual" class="form-control" step="0.01" min="0" value="<?php echo e($venta->descuento_manual); ?>">
            </div>
            <div class="col-md-6">
                <label for="motivo_descuento" class="form-label fw-semibold">Motivo del descuento</label>
                <input type="text" name="motivo_descuento" id="motivo_descuento" class="form-control" value="<?php echo e($venta->motivo_descuento); ?>">
            </div>
        </div>

        
        <div class="mb-3">
            <label for="estado" class="form-label fw-semibold">Estado de la venta</label>
            <select name="estado" id="estado" class="form-select" required>
                <option value="pendiente" <?php echo e($venta->estado == 'pendiente' ? 'selected' : ''); ?>>Pendiente</option>
                <option value="pagada" <?php echo e($venta->estado == 'pagada' ? 'selected' : ''); ?>>Pagada</option>
                <option value="cancelada" <?php echo e($venta->estado == 'cancelada' ? 'selected' : ''); ?>>Cancelada</option>
            </select>
        </div>

        
        <div class="mt-4 p-3 border rounded bg-light">
            <p class="mb-1"><strong>Subtotal:</strong> $<span id="subtotal"><?php echo e(number_format($venta->subtotal, 2, ',', '.')); ?></span></p>
            <p class="mb-1"><strong>Descuento total:</strong> $<span id="descuento"><?php echo e(number_format($venta->descuento_manual, 2, ',', '.')); ?></span></p>
            <p class="mb-1"><strong>Costo de envío:</strong> $<span id="envio_total"><?php echo e(number_format($venta->costo_envio, 2, ',', '.')); ?></span></p>
            <h4><strong>Total:</strong> $<span id="total"><?php echo e(number_format($venta->total, 2, ',', '.')); ?></span></h4>
        </div>

        
        <button type="submit" class="btn btn-primary mt-3">💾 Actualizar Venta</button>
    </form>
</div>


<script>
    const container = document.getElementById('productos-container');
    const addBtn = document.getElementById('add-producto');

    addBtn.addEventListener('click', () => {
        const firstRow = container.querySelector('.producto-row');
        const newRow = firstRow.cloneNode(true);

        newRow.querySelectorAll('select, input').forEach(el => {
            if (el.tagName === 'SELECT') el.selectedIndex = 0;
            else el.value = el.name.includes('cantidad') ? 1 : 0;
        });

        container.appendChild(newRow);
        actualizarTotales();
    });

    document.addEventListener('click', e => {
        if (e.target.classList.contains('btn-remove')) {
            const rows = container.querySelectorAll('.producto-row');
            if (rows.length > 1) e.target.closest('.producto-row').remove();
            actualizarTotales();
        }
    });

    document.addEventListener('input', actualizarTotales);

    function actualizarTotales() {
        let subtotal = 0;

        document.querySelectorAll('.producto-row').forEach(row => {
            const precio = parseFloat(row.querySelector('select').selectedOptions[0]?.text.match(/\$(\d+([.,]\d+)?)/)?.[1].replace('.', '').replace(',', '.') || 0);
            const cantidad = parseFloat(row.querySelector('input[name="cantidades[]"]').value) || 0;
            const descuento = parseFloat(row.querySelector('input[name="descuentos[]"]').value) || 0;
            const sub = (precio * cantidad) - descuento;
            subtotal += sub;

            const subtotalInput = row.querySelector('.subtotal');
            subtotalInput.value = `$${sub.toFixed(2)}`;
        });

        const descuentoTotal = parseFloat(document.getElementById('descuento_manual').value || 0);
        const costoEnvio = parseFloat(document.getElementById('costo_envio').value || 0);
        const total = subtotal - descuentoTotal + costoEnvio;

        document.getElementById('subtotal').textContent = subtotal.toLocaleString('es-CO', { minimumFractionDigits: 2 });
        document.getElementById('descuento').textContent = descuentoTotal.toLocaleString('es-CO', { minimumFractionDigits: 2 });
        document.getElementById('envio_total').textContent = costoEnvio.toLocaleString('es-CO', { minimumFractionDigits: 2 });
        document.getElementById('total').textContent = total.toLocaleString('es-CO', { minimumFractionDigits: 2 });
    }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Usuario\Documents\My Web Sites\POS\Backend\POSRed\resources\views/ventas/edit.blade.php ENDPATH**/ ?>