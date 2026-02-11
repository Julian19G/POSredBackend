

<?php $__env->startSection('content'); ?>
<div class="container">
    <h1 class="mb-4">🛒 Registrar Nueva Venta</h1>

    
    <?php if($errors->any()): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="<?php echo e(route('ventas.store')); ?>" method="POST">
        <?php echo csrf_field(); ?>

        
        <div class="mb-3">
            <label for="cliente_id" class="form-label fw-semibold">Cliente</label>
            <select name="cliente_id" id="cliente_id" class="form-select" required>
                <option value="">Seleccione un cliente</option>
                <?php $__currentLoopData = $clientes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cliente): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($cliente->id); ?>"><?php echo e($cliente->nombre); ?> - <?php echo e($cliente->telefono); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>

        
        <h5 class="mt-4 fw-bold">Productos</h5>
        <div id="productos-container">
            <div class="producto-row row mb-3 g-2 align-items-end">
                <div class="col-md-6">
                    <label class="form-label">Producto</label>
                    <select name="productos[]" class="form-select" required>
                        <option value="">Seleccione un producto</option>
                        <?php $__currentLoopData = $productos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $producto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($producto->id); ?>">
                                <?php echo e($producto->nombre); ?> — $<?php echo e(number_format($producto->precio, 2, ',', '.')); ?> (Stock: <?php echo e($producto->stock); ?>)
                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Cantidad</label>
                    <input type="number" name="cantidades[]" class="form-control" min="1" value="1" required>
                </div>

                <div class="col-md-2 d-flex align-items-end">
                    <button type="button" class="btn btn-danger btn-remove">✖</button>
                </div>
            </div>
        </div>

        <button type="button" id="add-producto" class="btn btn-secondary mb-4">➕ Agregar otro producto</button>

        <hr>

        
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="descuentos_manual" class="form-label fw-semibold">descuentos manual ($)</label>
                <input type="number" step="0.01" name="descuentos_manual" id="descuentos_manual" class="form-control" value="0" min="0">
            </div>
            <div class="col-md-6">
                <label for="motivo_descuentos" class="form-label fw-semibold">Motivo del descuentos</label>
                <input type="text" name="motivo_descuentos" id="motivo_descuentos" class="form-control" placeholder="Ejemplo: cliente frecuente">
            </div>
        </div>

        
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="costo_envio" class="form-label fw-semibold">Costo de envío ($)</label>
                <input type="number" step="0.01" name="costo_envio" id="costo_envio" class="form-control" value="0" min="0">
            </div>
            <div class="col-md-6">
                <label for="direccion_envio" class="form-label fw-semibold">Dirección de envío</label>
                <input type="text" name="direccion_envio" id="direccion_envio" class="form-control" placeholder="Opcional, si aplica envío">
            </div>
        </div>

        
        <div class="mb-3">
            <label for="estado" class="form-label fw-semibold">Estado de la venta</label>
            <select name="estado" id="estado" class="form-select" required>
                <option value="pendiente" selected>Pendiente</option>
                <option value="pagada">Pagada</option>
                <option value="cancelada">Cancelada</option>
            </select>
        </div>

        
        <button type="submit" class="btn btn-primary mt-3">
            💾 Registrar Venta
        </button>
    </form>
</div>


<script>
    document.getElementById('add-producto').addEventListener('click', function () {
        const container = document.getElementById('productos-container');
        const firstRow = container.querySelector('.producto-row');
        const newRow = firstRow.cloneNode(true);

        newRow.querySelectorAll('select, input').forEach(el => {
            if (el.tagName === 'SELECT') el.selectedIndex = 0;
            if (el.type === 'number') el.value = el.min || 1;
        });

        container.appendChild(newRow);
    });

    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('btn-remove')) {
            const rows = document.querySelectorAll('.producto-row');
            if (rows.length > 1) e.target.closest('.producto-row').remove();
        }
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Usuario\Documents\My Web Sites\POS\Backend\POSRed\resources\views/ventas/create.blade.php ENDPATH**/ ?>