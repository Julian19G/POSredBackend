

<?php $__env->startSection('content'); ?>
<div class="container">
    <h1 class="mb-4">Editar Producto</h1>

    
    <?php if($errors->any()): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    
    <form action="<?php echo e(route('productos.update', $producto)); ?>" method="POST" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        
        <div class="mb-3">
            <label for="nombre" class="form-label fw-semibold">Nombre</label>
            <input type="text" name="nombre" id="nombre" class="form-control" 
                   value="<?php echo e(old('nombre', $producto->nombre)); ?>" required>
        </div>

        
        <div class="mb-3">
            <label for="descripcion" class="form-label fw-semibold">Descripción</label>
            <textarea name="descripcion" id="descripcion" class="form-control" rows="3"><?php echo e(old('descripcion', $producto->descripcion)); ?></textarea>
        </div>

        
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="precio" class="form-label fw-semibold">Precio</label>
                <input type="number" step="0.01" name="precio" id="precio" class="form-control" 
                       value="<?php echo e(old('precio', $producto->precio)); ?>" required>
            </div>

            <div class="col-md-6 mb-3">
                <label for="stock" class="form-label fw-semibold">Stock</label>
                <input type="number" name="stock" id="stock" class="form-control" 
                       value="<?php echo e(old('stock', $producto->stock)); ?>" required>
            </div>
        </div>

        
        <div class="mb-3">
            <label for="categoria_id" class="form-label fw-semibold">Categoría</label>
            <select name="categoria_id" id="categoria_id" class="form-select">
                <option value="">-- Seleccionar categoría --</option>
                <?php $__currentLoopData = $categorias; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $categoria): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($categoria->id); ?>" 
                        <?php echo e(old('categoria_id', $producto->categoria_id) == $categoria->id ? 'selected' : ''); ?>>
                        <?php echo e($categoria->nombre); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>

        
        <div class="mb-3">
            <label for="imagen" class="form-label fw-semibold">Imagen</label>
            <?php if($producto->imagen): ?>
                <div class="mb-2">
                    <img src="<?php echo e(asset('storage/' . $producto->imagen)); ?>" alt="Imagen del producto" 
                         class="img-thumbnail shadow-sm" style="max-width: 150px;">
                </div>
            <?php endif; ?>
            <input type="file" name="imagen" id="imagen" class="form-control">
        </div>

        
        <div class="mb-4">
            <label for="activo" class="form-label fw-semibold">Estado</label>
            <select class="form-select" name="activo" id="activo">
                <option value="1" <?php echo e(old('activo', $producto->activo) == 1 ? 'selected' : ''); ?>>Activo</option>
                <option value="0" <?php echo e(old('activo', $producto->activo) == 0 ? 'selected' : ''); ?>>Inactivo</option>
            </select>
        </div>

                
        <div class="mb-3">
            <label class="form-label">Sabores</label>

            <div id="sabores-container">

                
                <?php $__currentLoopData = old('sabores', $producto->sabores->pluck('id')->toArray()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $saborSeleccionado): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="d-flex gap-2 mb-2">
                        <select name="sabores[]" class="form-select">
                            <?php $__currentLoopData = $sabores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sabor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($sabor->id); ?>"
                                    <?php echo e($sabor->id == $saborSeleccionado ? 'selected' : ''); ?>>
                                    <?php echo e($sabor->nombre); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <button type="button" class="btn btn-danger remove-sabor">X</button>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                
                <?php if($producto->sabores->count() == 0): ?>
                    <div class="d-flex gap-2 mb-2">
                        <select name="sabores[]" class="form-select">
                            <?php $__currentLoopData = $sabores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sabor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($sabor->id); ?>"><?php echo e($sabor->nombre); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <button type="button" class="btn btn-danger remove-sabor">X</button>
                    </div>
                <?php endif; ?>

            </div>

            <button type="button" id="add-sabor" class="btn btn-primary btn-sm">
                Agregar sabor
            </button>
        </div>

        <script>
        document.getElementById('add-sabor').addEventListener('click', function () {
            let container = document.getElementById('sabores-container');

            let row = document.createElement('div');
            row.classList.add('d-flex', 'gap-2', 'mb-2');

            row.innerHTML = `
                <select name="sabores[]" class="form-select">
                    <?php $__currentLoopData = $sabores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sabor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($sabor->id); ?>"><?php echo e($sabor->nombre); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <button type="button" class="btn btn-danger remove-sabor">X</button>
            `;

            container.appendChild(row);

            row.querySelector('.remove-sabor').addEventListener('click', function () {
                row.remove();
            });
        });

        document.querySelectorAll('.remove-sabor').forEach(btn => {
            btn.addEventListener('click', function () {
                this.parentElement.remove();
            });
        });
        </script>



        
        <div class="mb-3">
            <label class="form-label">Efectos</label>

            <div id="efectos-container">

                <?php $__currentLoopData = old('efectos', $producto->efectos->pluck('id')->toArray()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $efectoSeleccionado): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="d-flex gap-2 mb-2">
                        <select name="efectos[]" class="form-select">
                            <?php $__currentLoopData = $efectos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $efecto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($efecto->id); ?>"
                                    <?php echo e($efecto->id == $efectoSeleccionado ? 'selected' : ''); ?>>
                                    <?php echo e($efecto->nombre); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <button type="button" class="btn btn-danger remove-efecto">X</button>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                <?php if($producto->efectos->count() == 0): ?>
                    <div class="d-flex gap-2 mb-2">
                        <select name="efectos[]" class="form-select">
                            <?php $__currentLoopData = $efectos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $efecto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($efecto->id); ?>"><?php echo e($efecto->nombre); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <button type="button" class="btn btn-danger remove-efecto">X</button>
                    </div>
                <?php endif; ?>

            </div>

            <button type="button" id="add-efecto" class="btn btn-primary btn-sm">
                Agregar efecto
            </button>
        </div>

        <script>
        document.getElementById('add-efecto').addEventListener('click', function () {
            let container = document.getElementById('efectos-container');

            let row = document.createElement('div');
            row.classList.add('d-flex', 'gap-2', 'mb-2');

            row.innerHTML = `
                <select name="efectos[]" class="form-select">
                    <?php $__currentLoopData = $efectos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $efecto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($efecto->id); ?>"><?php echo e($efecto->nombre); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <button type="button" class="btn btn-danger remove-efecto">X</button>
            `;

            container.appendChild(row);

            row.querySelector('.remove-efecto').addEventListener('click', function () {
                row.remove();
            });
        });

        document.querySelectorAll('.remove-efecto').forEach(btn => {
            btn.addEventListener('click', function () {
                this.parentElement.remove();
            });
        });
        </script>



        
        <div class="mb-3">
            <label class="form-label">Colores</label>

            <div id="colores-container">

                <?php $__currentLoopData = old('colores', $producto->colores->pluck('id')->toArray()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $colorSeleccionado): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="d-flex gap-2 mb-2">
                        <select name="colores[]" class="form-select">
                            <?php $__currentLoopData = $colores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $color): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($color->id); ?>"
                                    <?php echo e($color->id == $colorSeleccionado ? 'selected' : ''); ?>>
                                    <?php echo e($color->nombre); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <button type="button" class="btn btn-danger remove-color">X</button>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                <?php if($producto->colores->count() == 0): ?>
                    <div class="d-flex gap-2 mb-2">
                        <select name="colores[]" class="form-select">
                            <?php $__currentLoopData = $colores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $color): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($color->id); ?>"><?php echo e($color->nombre); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <button type="button" class="btn btn-danger remove-color">X</button>
                    </div>
                <?php endif; ?>

            </div>

            <button type="button" id="add-color" class="btn btn-primary btn-sm">
                Agregar color
            </button>
        </div>

        <script>
        document.getElementById('add-color').addEventListener('click', function () {
            let container = document.getElementById('colores-container');

            let row = document.createElement('div');
            row.classList.add('d-flex', 'gap-2', 'mb-2');

            row.innerHTML = `
                <select name="colores[]" class="form-select">
                    <?php $__currentLoopData = $colores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $color): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($color->id); ?>"><?php echo e($color->nombre); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <button type="button" class="btn btn-danger remove-color">X</button>
            `;

            container.appendChild(row);

            row.querySelector('.remove-color').addEventListener('click', function () {
                row.remove();
            });
        });

        document.querySelectorAll('.remove-color').forEach(btn => {
            btn.addEventListener('click', function () {
                this.parentElement.remove();
            });
        });
        </script>


        
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-success px-4">Actualizar</button>
            <a href="<?php echo e(route('productos.index')); ?>" class="btn btn-secondary px-4">Cancelar</a>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Usuario\Documents\My Web Sites\POS\Backend\POSRed\resources\views/productos/edit.blade.php ENDPATH**/ ?>