

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

        
        <div class="row mb-3 g-3">
            <div class="col-md-7">
                <label for="cliente_id" class="form-label fw-semibold">Cliente</label>
                <select name="cliente_id" id="cliente_id" class="form-select" required>
                    <?php $__currentLoopData = $clientes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cliente): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($cliente->id); ?>" <?php echo e($venta->cliente_id == $cliente->id ? 'selected' : ''); ?>>
                            <?php echo e($cliente->nombre); ?><?php echo e($cliente->telefono ? ' - ' . $cliente->telefono : ''); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-md-5">
                <label class="form-label fw-semibold">Vendedor</label>
                <select name="vendedor_id" class="form-select">
                    <option value="">— Sin asignar —</option>
                    <?php $__currentLoopData = $vendedores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($v->id); ?>" <?php echo e($venta->vendedor_id == $v->id ? 'selected' : ''); ?>>
                            <?php echo e($v->nombre); ?>

                            <?php if($v->comision_porcentaje > 0): ?> (<?php echo e($v->comision_porcentaje); ?>%) <?php endif; ?>
                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
        </div>

        
        <h5 class="mt-4">🧾 Productos</h5>
        <div id="productos-container">
            <?php $__currentLoopData = $venta->detalles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detalle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="producto-row row mb-2 g-2 align-items-end">
                <input type="hidden" name="productos[]" value="<?php echo e($detalle->producto_id); ?>">
                <input type="hidden" name="variantes[]"  value="<?php echo e($detalle->variante_id ?? 0); ?>">

                <div class="col-md-4">
                    <label class="form-label small">Producto / variante</label>
                    <select name="variante_display[]" class="form-select form-select-sm variante-select" disabled>
                        <?php $__currentLoopData = $productos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $producto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php $__currentLoopData = $producto->variantes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $variante): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($variante->id); ?>"
                                    data-precio="<?php echo e($variante->precio); ?>"
                                    data-producto="<?php echo e($producto->id); ?>"
                                    <?php echo e($detalle->variante_id == $variante->id ? 'selected' : ''); ?>>
                                    <?php echo e($producto->nombre); ?> – <?php echo e($variante->nombre); ?>

                                    ($<?php echo e(number_format($variante->precio, 0, ',', '.')); ?>)
                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <small class="text-muted">
                        <?php echo e($detalle->nombre_producto); ?>

                        <?php if($detalle->nombre_variante): ?> – <?php echo e($detalle->nombre_variante); ?> <?php endif; ?>
                    </small>
                </div>
                <div class="col-md-2">
                    <label class="form-label small">Precio unit.</label>
                    <input type="number" name="precios_manuales[]" class="form-control form-control-sm precio-input"
                           step="1" min="0" value="<?php echo e($detalle->precio_unitario); ?>">
                </div>
                <div class="col-md-2">
                    <label class="form-label small">Cantidad</label>
                    <input type="number" name="cantidades[]" class="form-control form-control-sm cantidad-input"
                           min="1" value="<?php echo e($detalle->cantidad); ?>" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label small">Subtotal</label>
                    <input type="text" class="form-control form-control-sm subtotal-input" readonly
                           value="<?php echo e(number_format($detalle->precio_unitario * $detalle->cantidad, 0, ',', '.')); ?>">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="button" class="btn btn-sm btn-outline-danger btn-remove w-100">✖ Quitar</button>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <button type="button" id="add-producto" class="btn btn-outline-secondary btn-sm mb-3">➕ Agregar producto</button>

        
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
            <p class="mb-1"><strong>Subtotal:</strong> $<span id="subtotal"><?php echo e(number_format($venta->subtotal, 0, ',', '.')); ?></span></p>
            <p class="mb-1"><strong>Descuento total:</strong> $<span id="descuento"><?php echo e(number_format($venta->descuento_manual, 0, ',', '.')); ?></span></p>
            <p class="mb-1"><strong>Costo de envío:</strong> $<span id="envio_total"><?php echo e(number_format($venta->costo_envio, 0, ',', '.')); ?></span></p>
            <h4><strong>Total:</strong> $<span id="total"><?php echo e(number_format($venta->total, 0, ',', '.')); ?></span></h4>
        </div>

        
        <button type="submit" class="btn btn-primary mt-3">💾 Actualizar Venta</button>
    </form>
</div>

<script>
const container = document.getElementById('productos-container');

// Recalculate row subtotal and grand total
function recalcular() {
    let subtotal = 0;
    container.querySelectorAll('.producto-row').forEach(row => {
        const precio   = parseFloat(row.querySelector('.precio-input').value)   || 0;
        const cantidad = parseFloat(row.querySelector('.cantidad-input').value)  || 0;
        const sub      = precio * cantidad;
        row.querySelector('.subtotal-input').value = sub.toLocaleString('es-CO');
        subtotal += sub;
    });

    const descuentoTotal = parseFloat(document.getElementById('descuento_manual').value || 0);
    const costoEnvio     = parseFloat(document.getElementById('costo_envio').value      || 0);
    const total          = subtotal - descuentoTotal + costoEnvio;

    document.getElementById('subtotal').textContent    = subtotal.toLocaleString('es-CO');
    document.getElementById('descuento').textContent   = descuentoTotal.toLocaleString('es-CO');
    document.getElementById('envio_total').textContent = costoEnvio.toLocaleString('es-CO');
    document.getElementById('total').textContent       = total.toLocaleString('es-CO');
}

container.addEventListener('input', recalcular);
document.getElementById('descuento_manual').addEventListener('input', recalcular);
document.getElementById('costo_envio').addEventListener('input', recalcular);

document.addEventListener('click', e => {
    if (e.target.classList.contains('btn-remove')) {
        const rows = container.querySelectorAll('.producto-row');
        if (rows.length > 1) { e.target.closest('.producto-row').remove(); recalcular(); }
    }
});

document.getElementById('add-producto').addEventListener('click', () => {
    const firstRow = container.querySelector('.producto-row');
    const newRow   = firstRow.cloneNode(true);
    // Clear variante hidden to 0 and producto hidden to first product
    newRow.querySelector('input[name="variantes[]"]').value  = '0';
    newRow.querySelector('input[name="productos[]"]').value  = '<?php echo e($productos->first()?->id ?? 0); ?>';
    newRow.querySelector('.precio-input').value    = '';
    newRow.querySelector('.cantidad-input').value  = '1';
    newRow.querySelector('.subtotal-input').value  = '';
    const label = newRow.querySelector('small');
    if (label) label.textContent = 'Sin variante — ingresa precio manualmente';
    container.appendChild(newRow);
});

recalcular();
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Usuario\Documents\My Web Sites\POS\Backend\POSRed\resources\views\ventas\edit.blade.php ENDPATH**/ ?>