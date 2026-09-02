

<?php $__env->startSection('content'); ?>
<div class="product-detail-page">
    <div class="product-detail-shell">
        <div class="product-detail-header">
            <div>
                <span class="product-detail-eyebrow">Catálogo</span>
                <h1 class="product-detail-title"><?php echo e($producto->nombre); ?></h1>
                <p class="product-detail-subtitle">Información completa y presentaciones registradas.</p>
            </div>
            <a href="<?php echo e(route('productos.index')); ?>" class="product-secondary product-detail-back">
                <svg aria-hidden="true" viewBox="0 0 24 24" fill="none"><path d="m15 18-6-6 6-6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                Volver al catálogo
            </a>
        </div>

        <div class="product-detail-card">
            <div class="product-detail-overview">
                <div class="product-detail-image-wrap">
                    <?php if($producto->imagen): ?>
                        <img src="<?php echo e(asset('storage/' . $producto->imagen)); ?>" alt="<?php echo e($producto->nombre); ?>" class="product-detail-image">
                    <?php else: ?>
                        <svg aria-hidden="true" class="product-detail-image-placeholder" viewBox="0 0 24 24" fill="none"><rect x="3" y="4" width="18" height="16" rx="2" stroke="currentColor" stroke-width="1.4"/><circle cx="8" cy="9" r="1.5" stroke="currentColor" stroke-width="1.4"/><path d="m4 17 5-5 3 3 2-2 6 5" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/></svg>
                        <span>Sin imagen</span>
                    <?php endif; ?>
                </div>

                <div class="product-detail-summary">
                    <div class="product-detail-title-row">
                        <div>
                            <span class="product-detail-label">Producto</span>
                            <h2><?php echo e($producto->nombre); ?></h2>
                        </div>
                        <span class="product-detail-status <?php echo e($producto->activo ? 'product-detail-status-active' : 'product-detail-status-inactive'); ?>"><?php echo e($producto->activo ? 'Activo' : 'Inactivo'); ?></span>
                    </div>
                    <div class="product-detail-description">
                        <span class="product-detail-label">Descripción</span>
                        <p><?php echo e($producto->descripcion ?: 'Sin descripción disponible.'); ?></p>
                    </div>
                    <div class="product-detail-meta">
                        <div><span class="product-detail-label">Stock total</span><strong><?php echo e($producto->stock); ?></strong></div>
                        <div><span class="product-detail-label">Categoría</span><strong><?php echo e($producto->categoria?->nombre ?? 'Sin categoría'); ?></strong></div>
                    </div>
                </div>
            </div>

            <section class="product-detail-section">
                <div class="product-detail-section-heading">
                    <span class="product-detail-eyebrow">Características</span>
                    <h2>Atributos del producto</h2>
                </div>
                <div class="product-detail-attributes">
                    <div class="product-detail-attribute">
                        <span class="product-detail-label">Sabores</span>
                        <div class="product-detail-chips">
                            <?php $__empty_1 = true; $__currentLoopData = $producto->sabores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sabor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><span class="product-detail-chip"><?php echo e($sabor->nombre); ?></span><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><span class="product-detail-empty">Sin sabores registrados</span><?php endif; ?>
                        </div>
                    </div>
                    <div class="product-detail-attribute">
                        <span class="product-detail-label">Efectos</span>
                        <div class="product-detail-chips">
                            <?php $__empty_1 = true; $__currentLoopData = $producto->efectos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $efecto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><span class="product-detail-chip"><?php echo e($efecto->nombre); ?></span><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><span class="product-detail-empty">Sin efectos registrados</span><?php endif; ?>
                        </div>
                    </div>
                    <div class="product-detail-attribute">
                        <span class="product-detail-label">Colores</span>
                        <div class="product-detail-chips">
                            <?php $__empty_1 = true; $__currentLoopData = $producto->colores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $color): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><span class="product-detail-chip"><?php echo e($color->nombre); ?></span><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><span class="product-detail-empty">Sin colores registrados</span><?php endif; ?>
                        </div>
                    </div>
                </div>
            </section>

            <section class="product-detail-section">
                <div class="product-detail-section-heading">
                    <span class="product-detail-eyebrow">Presentaciones</span>
                    <h2>Variantes y precios</h2>
                </div>
                <?php if($producto->variantes->count()): ?>
                    <div class="product-detail-variants">
                        <?php $__currentLoopData = $producto->variantes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="product-detail-variant">
                                <div><strong><?php echo e($v->nombre); ?></strong><span><?php echo e($v->cantidad_por_variante); ?> unidades</span></div>
                                <strong class="product-detail-price">$<?php echo e(number_format($v->precio, 0, ',', '.')); ?></strong>
                                <span class="product-detail-stock">Stock <?php echo e($v->stock); ?></span>
                                <span class="product-detail-status <?php echo e($v->activo ? 'product-detail-status-active' : 'product-detail-status-inactive'); ?>"><?php echo e($v->activo ? 'Activo' : 'Inactivo'); ?></span>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php else: ?>
                    <p class="product-detail-empty product-detail-empty-block">Este producto no tiene variantes registradas.</p>
                <?php endif; ?>
            </section>
        </div>

        <div class="product-detail-actions">
            <a href="<?php echo e(route('productos.edit', $producto)); ?>" class="product-primary"><svg aria-hidden="true" viewBox="0 0 24 24" fill="none"><path d="m4 16.5-.8 3.3 3.3-.8L18.8 6.7a2.1 2.1 0 0 0-3-3L4 16.5Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/></svg>Editar producto</a>
            <a href="<?php echo e(route('productos.index')); ?>" class="product-secondary">Volver al catálogo</a>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Usuario\Documents\My Web Sites\POS\Backend\POSRed\resources\views/productos/show.blade.php ENDPATH**/ ?>