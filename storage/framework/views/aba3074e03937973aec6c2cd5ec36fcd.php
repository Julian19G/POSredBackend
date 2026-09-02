

<?php $__env->startSection('content'); ?>
<div class="product-page">
<div class="product-shell">

    <div class="product-header">
        <div>
            <h1 class="product-title">➕ Nuevo producto</h1>
            <small class="product-subtitle">Completa la información y agrega sus presentaciones.</small>
        </div>
        <a href="<?php echo e(route('productos.index')); ?>" class="product-back">← Volver</a>
    </div>

    <div class="product-stepper" aria-label="Pasos del producto">
        <div class="product-step active" data-step-indicator="1"><strong>1. Datos</strong><small>Información y stock</small></div>
        <div class="product-step" data-step-indicator="2"><strong>2. Atributos</strong><small>Características</small></div>
        <div class="product-step" data-step-indicator="3"><strong>3. Presentaciones</strong><small>Precios y revisión</small></div>
    </div>

    <form action="<?php echo e(route('productos.store')); ?>" method="POST" enctype="multipart/form-data" id="producto-form">
        <?php echo csrf_field(); ?>
        <div class="product-card"><div class="product-card-body">

        <section class="step-pane" data-step="1">
        <div class="product-section">📝 Información básica</div>

        <div class="product-field">
            <label class="product-label">Nombre</label>
            <input type="text" name="nombre" class="product-input" value="<?php echo e(old('nombre')); ?>" required>
        </div>

        <div class="product-field">
            <label class="product-label">Descripción</label>
            <textarea name="descripcion" class="product-input" rows="2"><?php echo e(old('descripcion')); ?></textarea>
        </div>

        <div class="product-section divider">📦 Stock, categoría y detalles</div>

        <div class="product-field">
            <label class="product-label">Stock total (unidades base)</label>
            <input type="number" name="stock" class="product-input" value="<?php echo e(old('stock')); ?>" step="any" min="0" required>
            <small class="product-help">La disponibilidad de cada presentación se calcula sola (ej. 450 ÷ 10 = 45 paquetes x10).</small>
        </div>

        <div class="product-field">
            <label class="product-label">Categoría</label>
            <select name="categoria_id" id="categoria_select" class="product-input" data-flores="<?php echo e($floresId); ?>">
                <option value="">-- Selecciona una categoría --</option>
                <?php $__currentLoopData = $categorias; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $categoria): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($categoria->id); ?>"
                        <?php echo e(old('categoria_id') == $categoria->id ? 'selected' : ''); ?>>
                        <?php echo e($categoria->nombre); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>

        
        <div class="product-field hidden" id="tipo-flor-wrap">
            <label class="product-label">🌸 Tipos de flor</label>
            <div id="tipos-flor-container">
                <div class="product-row mt-2">
                    <select name="tipos_flor[]" class="product-input">
                        <option value="">-- Seleccionar tipo de flor --</option>
                        <?php $__currentLoopData = $tiposFlor; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tf): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($tf->id); ?>"><?php echo e($tf->icono); ?> <?php echo e($tf->nombre); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <button type="button" class="product-remove remove-tipo-flor">Quitar</button>
                </div>
            </div>
            <button type="button" id="add-tipo-flor" class="product-add">Agregar tipo de flor</button>
        </div>

        <div class="product-field">
            <label class="product-label">Imagen</label>
            <input type="file" name="imagen" class="product-input" accept="image/*">
        </div>

        <div class="product-field">
            <label class="product-label">Estado</label>
            <select class="product-input" name="activo">
                <option value="1" <?php echo e(old('activo', 1) == 1 ? 'selected' : ''); ?>>Activo</option>
                <option value="0" <?php echo e(old('activo') == 0 ? 'selected' : ''); ?>>Inactivo</option>
            </select>
        </div>
        </section>

        <section class="step-pane hidden" data-step="2">
        <div class="product-section divider">✨ Atributos</div>

        
        <div class="product-field">
            <label class="product-label">Sabores</label>
            <div id="sabores-container">
                <div class="product-row mt-2">
                    <select name="sabores[]" class="product-input">
                        <option value="">-- Seleccionar sabor --</option>
                        <?php $__currentLoopData = $sabores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sabor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($sabor->id); ?>"><?php echo e($sabor->nombre); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <button type="button" class="product-remove remove-sabor">Quitar</button>
                </div>
            </div>
            <button type="button" id="add-sabor" class="product-add">Agregar sabor</button>
        </div>

        
        <div class="product-field">
            <label class="product-label">Efectos</label>
            <div id="efectos-container">
                <div class="product-row mt-2">
                    <select name="efectos[]" class="product-input">
                        <option value="">-- Seleccionar efecto --</option>
                        <?php $__currentLoopData = $efectos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $efecto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($efecto->id); ?>"><?php echo e($efecto->nombre); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <button type="button" class="product-remove remove-efecto">Quitar</button>
                </div>
            </div>
            <button type="button" id="add-efecto" class="product-add">Agregar efecto</button>
        </div>

        
        <div class="product-field">
            <label class="product-label">Colores</label>
            <div id="colores-container">
                <div class="product-row mt-2">
                    <select name="colores[]" class="product-input">
                        <option value="">-- Seleccionar color --</option>
                        <?php $__currentLoopData = $colores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $color): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($color->id); ?>"><?php echo e($color->nombre); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <button type="button" class="product-remove remove-color">Quitar</button>
                </div>
            </div>
            <button type="button" id="add-color" class="product-add">Agregar color</button>
        </div>
        </section>

        <section class="step-pane hidden" data-step="3">
        <div class="product-section divider">🏷️ Presentaciones y precios</div>

        
        <div>
            <?php if($productosPlantilla->count()): ?>
            <div class="product-template">
                <label for="producto-plantilla" class="product-label">Copiar de otro producto</label>
                <div class="product-row">
                    <select id="producto-plantilla" class="product-input">
                        <option value="">-- Selecciona un producto --</option>
                        <?php $__currentLoopData = $productosPlantilla; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plantilla): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($plantilla->id); ?>"><?php echo e($plantilla->nombre); ?> (<?php echo e($plantilla->variantes->count()); ?> presentaciones)</option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <button type="button" id="copiar-plantilla" class="product-primary whitespace-nowrap">Copiar</button>
                </div>
                <small class="product-help">Copia sus presentaciones, cantidades y precios. Podrás editarlos antes de guardar.</small>
            </div>
            <?php endif; ?>
            <?php if($presentaciones->count()): ?>
            <div class="mt-3 flex flex-wrap gap-2">
                <?php $__currentLoopData = $presentaciones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pre): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <label class="product-preset">
                        <input type="checkbox" class="preset-check"
                               data-nombre="<?php echo e($pre->nombre); ?>" data-cantidad="<?php echo e($pre->cantidad); ?>">
                        <?php echo e($pre->nombre); ?> <span class="text-xs text-slate-500">(<?php echo e($pre->cantidad); ?> u)</span>
                    </label>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <small class="product-help mt-2">Marca las presentaciones y solo pon el precio. ¿Falta una? Créala en
                <a href="<?php echo e(route('presentaciones.index')); ?>" target="_blank">Config → Presentaciones</a>.</small>
            <?php endif; ?>

            <div id="variantes-container"></div>

            <button type="button" id="add-variante" class="product-add">
                ➕ Agregar presentación manual
            </button>
        </div>
        </section>

        </div></div>

        <div class="product-actions">
            <button type="button" id="step-prev" class="product-secondary hidden">← Anterior</button>
            <button type="button" id="step-next" class="product-primary">Siguiente →</button>
            <button type="submit" id="step-submit" class="product-success hidden">💾 Guardar producto</button>
            <a href="<?php echo e(route('productos.index')); ?>" class="product-secondary">Cancelar</a>
        </div>
    </form>
</div>

<script>
    // ── Tipos de Flor ─────────────────────────────────────
    document.getElementById('add-tipo-flor')?.addEventListener('click', function () {
        let row = document.createElement('div');
        row.classList.add('product-row', 'mt-2');
        row.innerHTML = `
            <select name="tipos_flor[]" class="product-input">
                <option value="">-- Seleccionar tipo de flor --</option>
                <?php $__currentLoopData = $tiposFlor; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tf): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($tf->id); ?>"><?php echo e($tf->icono); ?> <?php echo e($tf->nombre); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <button type="button" class="product-remove remove-tipo-flor">Quitar</button>`;
        document.getElementById('tipos-flor-container').appendChild(row);
        row.querySelector('.remove-tipo-flor').addEventListener('click', () => row.remove());
    });
    document.querySelectorAll('.remove-tipo-flor').forEach(btn => btn.addEventListener('click', function () {
        this.parentElement.remove();
    }));

    // ── Sabores ──────────────────────────────────────────
    document.getElementById('add-sabor').addEventListener('click', function () {
        let row = document.createElement('div');
        row.classList.add('product-row', 'mt-2');
        row.innerHTML = `
            <select name="sabores[]" class="product-input">
                <option value="">-- Seleccionar sabor --</option>
                <?php $__currentLoopData = $sabores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sabor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($sabor->id); ?>"><?php echo e($sabor->nombre); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <button type="button" class="product-remove remove-sabor">Quitar</button>`;
        document.getElementById('sabores-container').appendChild(row);
        row.querySelector('.remove-sabor').addEventListener('click', () => row.remove());
    });
    document.querySelectorAll('.remove-sabor').forEach(btn => btn.addEventListener('click', function () {
        this.parentElement.remove();
    }));

    // ── Efectos ──────────────────────────────────────────
    document.getElementById('add-efecto').addEventListener('click', function () {
        let row = document.createElement('div');
        row.classList.add('product-row', 'mt-2');
        row.innerHTML = `
            <select name="efectos[]" class="product-input">
                <option value="">-- Seleccionar efecto --</option>
                <?php $__currentLoopData = $efectos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $efecto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($efecto->id); ?>"><?php echo e($efecto->nombre); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <button type="button" class="product-remove remove-efecto">Quitar</button>`;
        document.getElementById('efectos-container').appendChild(row);
        row.querySelector('.remove-efecto').addEventListener('click', () => row.remove());
    });
    document.querySelectorAll('.remove-efecto').forEach(btn => btn.addEventListener('click', function () {
        this.parentElement.remove();
    }));

    // ── Colores ──────────────────────────────────────────
    document.getElementById('add-color').addEventListener('click', function () {
        let row = document.createElement('div');
        row.classList.add('product-row', 'mt-2');
        row.innerHTML = `
            <select name="colores[]" class="product-input">
                <option value="">-- Seleccionar color --</option>
                <?php $__currentLoopData = $colores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $color): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($color->id); ?>"><?php echo e($color->nombre); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <button type="button" class="product-remove remove-color">Quitar</button>`;
        document.getElementById('colores-container').appendChild(row);
        row.querySelector('.remove-color').addEventListener('click', () => row.remove());
    });
    document.querySelectorAll('.remove-color').forEach(btn => btn.addEventListener('click', function () {
        this.parentElement.remove();
    }));

    // ── Presentaciones / Variantes ─────────────────────────
    let varianteIndex = 0;
    const contVar = document.getElementById('variantes-container');
    const productosPlantilla = <?php echo json_encode($productosPlantillaData, 15, 512) ?>;

    function agregarFila({ nombre = '', cantidad = '', fijo = false, presetKey = null } = {}) {
        const i = varianteIndex++;
        const row = document.createElement('div');
        row.classList.add('product-variant');
        if (presetKey) row.dataset.presetKey = presetKey;

        if (fijo) {
            row.innerHTML = `
                <div class="product-grid">
                    <div class="sm:col-span-1">
                        <div class="font-semibold">${nombre} <span class="text-xs text-slate-500">· ${cantidad} unidades</span></div>
                        <input type="hidden" name="variantes[${i}][nombre]" value="${nombre}">
                        <input type="hidden" name="variantes[${i}][cantidad_por_variante]" value="${cantidad}">
                    </div>
                    <div>
                        <label class="product-label">Precio</label>
                        <input type="number" step="0.01" name="variantes[${i}][precio]" class="product-input"
                               placeholder="0.00" min="0" required autofocus>
                    </div>
                    <div class="flex items-end">
                        <button type="button" class="product-remove w-full remove-variante">Quitar</button>
                    </div>
                </div>`;
        } else {
            row.innerHTML = `
                <div class="product-grid">
                    <div>
                        <label class="product-label">Presentación</label>
                        <input type="text" name="variantes[${i}][nombre]" class="product-input"
                               value="${nombre}" placeholder="Ej: Paquete x10" required>
                    </div>
                    <div>
                        <label class="product-label">Cantidad (u)</label>
                        <input type="number" name="variantes[${i}][cantidad_por_variante]" class="product-input"
                               value="${cantidad}" step="any" min="0.01" placeholder="10 o 0.5" required>
                    </div>
                    <div>
                        <label class="product-label">Precio</label>
                        <input type="number" step="0.01" name="variantes[${i}][precio]" class="product-input"
                               placeholder="0.00" min="0" required>
                    </div>
                    <div class="flex items-end">
                        <button type="button" class="product-remove w-full remove-variante">Quitar</button>
                    </div>
                </div>`;
        }
        contVar.appendChild(row);
        row.querySelector('.remove-variante').addEventListener('click', () => {
            if (row.dataset.presetKey) {
                const chk = document.querySelector('.preset-check[data-key="' + row.dataset.presetKey + '"]');
                if (chk) chk.checked = false;
            }
            row.remove();
        });
        return row;
    }

    document.getElementById('add-variante').addEventListener('click', () => agregarFila());

    document.getElementById('copiar-plantilla')?.addEventListener('click', () => {
        const id = document.getElementById('producto-plantilla').value;
        const plantilla = productosPlantilla.find(producto => String(producto.id) === id);
        if (!plantilla) return;

        contVar.innerHTML = '';
        document.querySelectorAll('.preset-check').forEach(check => check.checked = false);
        varianteIndex = 0;
        plantilla.variantes.forEach(variante => {
            const row = agregarFila({ nombre: variante.nombre, cantidad: variante.cantidad });
            row.querySelector('input[name$="[precio]"]').value = variante.precio;
        });
    });

    document.querySelectorAll('.preset-check').forEach(chk => {
        const key = chk.dataset.nombre + '|' + chk.dataset.cantidad;
        chk.dataset.key = key;
        chk.addEventListener('change', function () {
            if (this.checked) {
                agregarFila({ nombre: this.dataset.nombre, cantidad: this.dataset.cantidad, fijo: true, presetKey: key });
            } else {
                const row = contVar.querySelector('.variante-row[data-preset-key="' + key + '"]');
                if (row) row.remove();
            }
        });
    });

    // ── Tipo de flor: mostrar solo si la categoría es Flores ──
    const catSel    = document.getElementById('categoria_select');
    const floresId  = catSel?.dataset.flores;
    const tipoWrap  = document.getElementById('tipo-flor-wrap');
    function toggleTipoFlor() {
        if (!tipoWrap) return;
        tipoWrap.classList.toggle('hidden', !(catSel.value && catSel.value === floresId));
    }
    if (catSel) { catSel.addEventListener('change', toggleTipoFlor); toggleTipoFlor(); }

    let currentStep = 1;
    const panes = document.querySelectorAll('.step-pane');
    const indicators = document.querySelectorAll('[data-step-indicator]');
    const previousButton = document.getElementById('step-prev');
    const nextButton = document.getElementById('step-next');
    const submitButton = document.getElementById('step-submit');
    const form = document.getElementById('producto-form');

    function showStep(step) {
        const previousStep = currentStep;
        currentStep = step;
        const direction = step > previousStep ? 'step-exit-left' : 'step-exit-right';
        panes.forEach(pane => {
            if (Number(pane.dataset.step) === step) {
                pane.classList.remove('hidden', 'step-exit-left', 'step-exit-right');
                pane.classList.add(direction);
                requestAnimationFrame(() => pane.classList.remove(direction));
            } else {
                pane.classList.add('hidden');
            }
        });
        indicators.forEach(indicator => indicator.classList.toggle('active', Number(indicator.dataset.stepIndicator) === step));
        previousButton.classList.toggle('hidden', step === 1);
        nextButton.classList.toggle('hidden', step === 3);
        submitButton.classList.toggle('hidden', step !== 3);
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function validateCurrentStep() {
        const fields = document.querySelector('[data-step="' + currentStep + '"]').querySelectorAll('input, select, textarea');
        for (const field of fields) {
            if (!field.checkValidity()) {
                field.reportValidity();
                return false;
            }
        }
        return true;
    }

    nextButton.addEventListener('click', () => {
        if (validateCurrentStep()) showStep(currentStep + 1);
    });
    previousButton.addEventListener('click', () => showStep(currentStep - 1));
    form.addEventListener('submit', event => {
        if (!validateCurrentStep()) event.preventDefault();
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Usuario\Documents\My Web Sites\POS\Backend\POSRed\resources\views\productos\create.blade.php ENDPATH**/ ?>