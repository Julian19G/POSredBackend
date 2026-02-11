

<?php $__env->startSection('content'); ?>
<div class="container">
    <h1 class="mb-4">➕ Crear Descuento</h1>

    <form action="<?php echo e(route('descuentos.store')); ?>" method="POST">
        <?php echo csrf_field(); ?>

        
        <div class="mb-3">
            <label class="form-label">Nombre</label>
            <input type="text" name="nombre" class="form-control" required>
        </div>

        
        <div class="mb-3">
            <label class="form-label">Código</label>
            <input type="text" name="codigo" class="form-control" required>
        </div>

        
        <div class="mb-3">
            <label class="form-label">Tipo</label>
            <select name="tipo" class="form-select" required>
                <option value="porcentaje">Porcentaje</option>
                <option value="fijo">Valor fijo</option>
            </select>
        </div>

        
        <div class="mb-3">
            <label class="form-label">Valor</label>
            <input type="number" step="0.01" name="valor" class="form-control" required>
        </div>

        
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" name="activo" value="1">
            <label class="form-check-label">Activo</label>
        </div>

        
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" name="aplicable_manual" value="1">
            <label class="form-check-label">Aplicable manualmente</label>
        </div>

        
        <div class="mb-3">
            <label class="form-label">Fecha inicio</label>
            <input type="date" name="fecha_inicio" class="form-control" required>
        </div>

        
        <div class="mb-3">
            <label class="form-label">Fecha fin</label>
            <input type="date" name="fecha_fin" class="form-control" required>
        </div>

        
        <div class="mb-3">
            <label class="form-label">Uso máximo total</label>
            <input type="number" name="uso_maximo" class="form-control">
        </div>

        
        <div class="mb-3">
            <label class="form-label">Uso máximo por cliente</label>
            <input type="number" name="uso_cliente_maximo" class="form-control">
        </div>

        <button class="btn btn-primary">Guardar</button>
        <a href="<?php echo e(route('descuentos.index')); ?>" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Usuario\Documents\My Web Sites\POS\Backend\POSRed\resources\views/descuentos/create.blade.php ENDPATH**/ ?>