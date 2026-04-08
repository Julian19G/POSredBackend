

<?php $__env->startSection('content'); ?>
<div class="container">
    <h1 class="mb-4">Relaciones Pivote de Productos</h1>

    <table class="table table-bordered table-hover text-center">
        <thead class="table-dark">
            <tr>
                <th>Producto ID</th>
                <th>Nombre Producto</th>
                <th>Colores</th>
                <th>Efectos</th>
                <th>Sabores</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $productos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $producto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($producto->id); ?></td>
                    <td><?php echo e($producto->nombre); ?></td>

                    <!-- Colores con color real del HEX -->
                    <td>
                        <?php $__currentLoopData = $producto_colores->where('producto_id', $producto->id); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <span class="badge text-white" style="background-color: <?php echo e($pc->codigo_hex ?? '#000'); ?>;">
                                <?php echo e($pc->color_nombre); ?>

                            </span>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </td>

                    <!-- Efectos -->
                    <td>
                        <?php $__currentLoopData = $producto_efectos->where('producto_id', $producto->id); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pe): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <span class="badge bg-success"><?php echo e($pe->efecto_nombre); ?></span>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </td>

                    <!-- Sabores -->
                    <td>
                        <?php $__currentLoopData = $producto_sabores->where('producto_id', $producto->id); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ps): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <span class="badge bg-warning text-dark"><?php echo e($ps->sabor_nombre); ?></span>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Usuario\Documents\My Web Sites\POS\Backend\POSRed\resources\views/pivotes/index.blade.php ENDPATH**/ ?>