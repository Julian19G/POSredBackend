

<?php $__env->startSection('content'); ?>
<style>
    :root {
        --panel-bg: #14161c;
        --panel-bg-alt: #1b1e26;
        --panel-border: #262a35;
        --text-primary: #e8eaf0;
        --text-muted: #8b92a3;
        --accent: #7c6cf0;
        --accent-soft: rgba(124,108,240,.15);
    }

    body { background:#0b0c10; }

    .prod-card {
        border:1px solid var(--panel-border);
        border-radius:20px;
        background:
            radial-gradient(120% 120% at 0% 0%, rgba(124,108,240,.06), transparent 60%),
            var(--panel-bg);
        box-shadow:0 20px 50px -20px rgba(0,0,0,.6), 0 0 0 1px rgba(255,255,255,.02) inset;
        color: var(--text-primary);
    }
    .prod-card > .card-body { padding: 2.25rem 2rem; }

    .prod-sec {
        font-size:.72rem;
        text-transform:uppercase;
        letter-spacing:.12em;
        color: var(--accent);
        font-weight:700;
        margin:0 0 1.25rem;
        display:flex; align-items:center; gap:.5rem;
    }
    .prod-sec.divider {
        margin-top:2.25rem;
        padding-top:1.5rem;
        border-top:1px solid var(--panel-border);
    }

    .prod-card .form-label {
        color: var(--text-muted);
        font-weight:600;
        font-size:.85rem;
        margin-bottom:.4rem;
    }
    .prod-card small.text-muted { color:#5f6675 !important; font-size:.78rem; }

    .prod-card .form-control,
    .prod-card .form-select {
        background: var(--panel-bg-alt);
        color: var(--text-primary);
        border:1px solid var(--panel-border);
        border-radius:10px;
        padding:.65rem .9rem;
        font-size:.92rem;
        transition: border-color .15s ease, box-shadow .15s ease, background .15s ease;
    }
    .prod-card .form-control:hover,
    .prod-card .form-select:hover { border-color:#38404f; }
    .prod-card .form-control:focus,
    .prod-card .form-select:focus {
        background: var(--panel-bg-alt);
        color: var(--text-primary);
        border-color: var(--accent);
        box-shadow:0 0 0 .2rem var(--accent-soft);
    }
    .prod-card .form-control::placeholder { color:#4b5160; }

    /* filas de variantes / presets */
    .prod-card .bg-light {
        background: var(--panel-bg-alt) !important;
        border:1px solid var(--panel-border) !important;
        border-radius:12px !important;
        transition: border-color .15s ease;
    }
    .prod-card .bg-light:hover { border-color:#38404f !important; }

    .prod-card label.border {
        background: var(--panel-bg-alt);
        border-color: var(--panel-border) !important;
        border-radius:10px !important;
        color: var(--text-primary);
        transition: all .15s ease;
    }
    .prod-card label.border:has(input:checked) {
        border-color: var(--accent) !important;
        background: var(--accent-soft);
    }

    /* botones */
    .prod-card .btn-primary {
        background: var(--accent);
        border:none;
        border-radius:9px;
        font-weight:600;
        font-size:.85rem;
        padding:.5rem 1rem;
    }
    .prod-card .btn-primary:hover { background:#6a5ae0; }

    .prod-card .btn-outline-success {
        border-color: var(--accent);
        color: var(--accent);
        border-radius:9px;
        font-weight:600;
        background:transparent;
    }
    .prod-card .btn-outline-success:hover { background:var(--accent); color:#fff; }

    .prod-card .btn-outline-danger {
        border-radius:9px;
        font-weight:600;
    }

    h1.h3 { font-weight:700; letter-spacing:-.02em; }

    .prod-actions {
        position:sticky;
        bottom:0;
        background: linear-gradient(180deg, transparent, #0b0c10 30%);
        padding:1.5rem 0 .5rem;
    }
    .prod-actions .btn-success {
        background: var(--accent);
        border:none;
        border-radius:10px;
        font-weight:600;
        padding:.65rem 1.75rem;
        box-shadow:0 8px 20px -8px var(--accent-soft);
    }
    .prod-actions .btn-success:hover { background:#6a5ae0; }
    .prod-actions .btn-outline-secondary {
        border-color: var(--panel-border);
        color: var(--text-muted);
        border-radius:10px;
        padding:.65rem 1.75rem;
    }
    .prod-actions .btn-outline-secondary:hover {
        background: var(--panel-bg-alt);
        color: var(--text-primary);
    }
    .stepper { display:flex; gap:.5rem; margin-bottom:1.25rem; }
    .stepper-item { flex:1; padding:.7rem .8rem; border:1px solid var(--panel-border); border-radius:10px; color:var(--text-muted); font-size:.8rem; }
    .stepper-item.active { border-color:var(--accent); color:var(--text-primary); background:var(--accent-soft); }
    .stepper-item small { display:block; color:inherit; opacity:.7; }
    .step-pane.d-none { display:none !important; }
</style>

<div class="container py-4" style="max-width:880px">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h3 mb-0">➕ Nuevo producto</h1>
            <small class="text-muted">Completa la información y agrega sus presentaciones.</small>
        </div>
        <a href="<?php echo e(route('productos.index')); ?>" class="btn btn-outline-secondary btn-sm">← Volver</a>
    </div>

    <div class="stepper" aria-label="Pasos del producto">
        <div class="stepper-item active" data-step-indicator="1"><strong>1. Datos</strong><small>Información y stock</small></div>
        <div class="stepper-item" data-step-indicator="2"><strong>2. Atributos</strong><small>Características</small></div>
        <div class="stepper-item" data-step-indicator="3"><strong>3. Presentaciones</strong><small>Precios y revisión</small></div>
    </div>

    <form action="<?php echo e(route('productos.store')); ?>" method="POST" enctype="multipart/form-data" id="producto-form">
        <?php echo csrf_field(); ?>
        <div class="prod-card mb-3"><div class="card-body">

        <section class="step-pane" data-step="1">
        <div class="prod-sec">📝 Información básica</div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Nombre</label>
            <input type="text" name="nombre" class="form-control" value="<?php echo e(old('nombre')); ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Descripción</label>
            <textarea name="descripcion" class="form-control" rows="2"><?php echo e(old('descripcion')); ?></textarea>
        </div>

        <div class="prod-sec divider">📦 Stock, categoría y detalles</div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Stock total (unidades base)</label>
            <input type="number" name="stock" class="form-control" value="<?php echo e(old('stock')); ?>" step="any" min="0" required>
            <small class="text-muted">La disponibilidad de cada presentación se calcula sola (ej. 450 ÷ 10 = 45 paquetes x10).</small>
        </div>

        <div class="mb-3">
            <label class="form-label">Categoría</label>
            <select name="categoria_id" id="categoria_select" class="form-select" data-flores="<?php echo e($floresId); ?>">
                <option value="">-- Selecciona una categoría --</option>
                <?php $__currentLoopData = $categorias; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $categoria): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($categoria->id); ?>"
                        <?php echo e(old('categoria_id') == $categoria->id ? 'selected' : ''); ?>>
                        <?php echo e($categoria->nombre); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>

        
        <div class="mb-3" id="tipo-flor-wrap" style="display:none">
            <label class="form-label">🌸 Tipos de flor</label>
            <div id="tipos-flor-container">
                <div class="d-flex gap-2 mb-2">
                    <select name="tipos_flor[]" class="form-select">
                        <option value="">-- Seleccionar tipo de flor --</option>
                        <?php $__currentLoopData = $tiposFlor; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tf): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($tf->id); ?>"><?php echo e($tf->icono); ?> <?php echo e($tf->nombre); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <button type="button" class="btn btn-danger remove-tipo-flor">X</button>
                </div>
            </div>
            <button type="button" id="add-tipo-flor" class="btn btn-primary btn-sm">Agregar tipo de flor</button>
        </div>

        <div class="mb-3">
            <label class="form-label">Imagen</label>
            <input type="file" name="imagen" class="form-control" accept="image/*">
        </div>

        <div class="mb-3">
            <label class="form-label">Estado</label>
            <select class="form-select" name="activo">
                <option value="1" <?php echo e(old('activo', 1) == 1 ? 'selected' : ''); ?>>Activo</option>
                <option value="0" <?php echo e(old('activo') == 0 ? 'selected' : ''); ?>>Inactivo</option>
            </select>
        </div>
        </section>

        <section class="step-pane d-none" data-step="2">
        <div class="prod-sec divider">✨ Atributos</div>

        
        <div class="mb-3">
            <label class="form-label">Sabores</label>
            <div id="sabores-container">
                <div class="d-flex gap-2 mb-2">
                    <select name="sabores[]" class="form-select">
                        <option value="">-- Seleccionar sabor --</option>
                        <?php $__currentLoopData = $sabores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sabor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($sabor->id); ?>"><?php echo e($sabor->nombre); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <button type="button" class="btn btn-danger remove-sabor">X</button>
                </div>
            </div>
            <button type="button" id="add-sabor" class="btn btn-primary btn-sm">Agregar sabor</button>
        </div>

        
        <div class="mb-3">
            <label class="form-label">Efectos</label>
            <div id="efectos-container">
                <div class="d-flex gap-2 mb-2">
                    <select name="efectos[]" class="form-select">
                        <option value="">-- Seleccionar efecto --</option>
                        <?php $__currentLoopData = $efectos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $efecto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($efecto->id); ?>"><?php echo e($efecto->nombre); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <button type="button" class="btn btn-danger remove-efecto">X</button>
                </div>
            </div>
            <button type="button" id="add-efecto" class="btn btn-primary btn-sm">Agregar efecto</button>
        </div>

        
        <div class="mb-3">
            <label class="form-label">Colores</label>
            <div id="colores-container">
                <div class="d-flex gap-2 mb-2">
                    <select name="colores[]" class="form-select">
                        <option value="">-- Seleccionar color --</option>
                        <?php $__currentLoopData = $colores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $color): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($color->id); ?>"><?php echo e($color->nombre); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <button type="button" class="btn btn-danger remove-color">X</button>
                </div>
            </div>
            <button type="button" id="add-color" class="btn btn-primary btn-sm">Agregar color</button>
        </div>
        </section>

        <section class="step-pane d-none" data-step="3">
        <div class="prod-sec divider">🏷️ Presentaciones y precios</div>

        
        <div class="mb-2">
            <?php if($productosPlantilla->count()): ?>
            <div class="border rounded p-3 mb-3" style="border-color:var(--panel-border) !important;background:var(--panel-bg-alt)">
                <label for="producto-plantilla" class="form-label">Copiar de otro producto</label>
                <div class="d-flex gap-2">
                    <select id="producto-plantilla" class="form-select">
                        <option value="">-- Selecciona un producto --</option>
                        <?php $__currentLoopData = $productosPlantilla; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plantilla): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($plantilla->id); ?>"><?php echo e($plantilla->nombre); ?> (<?php echo e($plantilla->variantes->count()); ?> presentaciones)</option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <button type="button" id="copiar-plantilla" class="btn btn-primary text-nowrap">Copiar</button>
                </div>
                <small class="text-muted d-block mt-2">Copia sus presentaciones, cantidades y precios. Podrás editarlos antes de guardar.</small>
            </div>
            <?php endif; ?>
            <?php if($presentaciones->count()): ?>
            <div class="mb-3 d-flex flex-wrap gap-2">
                <?php $__currentLoopData = $presentaciones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pre): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <label class="border rounded px-3 py-2 d-inline-flex align-items-center gap-2" style="cursor:pointer">
                        <input type="checkbox" class="preset-check"
                               data-nombre="<?php echo e($pre->nombre); ?>" data-cantidad="<?php echo e($pre->cantidad); ?>">
                        <?php echo e($pre->nombre); ?> <span class="text-muted small">(<?php echo e($pre->cantidad); ?> u)</span>
                    </label>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <small class="text-muted d-block mb-2">Marca las presentaciones y solo pon el precio. ¿Falta una? Créala en
                <a href="<?php echo e(route('presentaciones.index')); ?>" target="_blank">Config → Presentaciones</a>.</small>
            <?php endif; ?>

            <div id="variantes-container"></div>

            <button type="button" id="add-variante" class="btn btn-outline-success btn-sm">
                ➕ Agregar presentación manual
            </button>
        </div>
        </section>

        </div></div>

        <div class="prod-actions d-flex gap-2">
            <button type="button" id="step-prev" class="btn btn-outline-secondary px-4 d-none">← Anterior</button>
            <button type="button" id="step-next" class="btn btn-primary px-4">Siguiente →</button>
            <button type="submit" id="step-submit" class="btn btn-success px-4 d-none">💾 Guardar producto</button>
            <a href="<?php echo e(route('productos.index')); ?>" class="btn btn-outline-secondary px-4">Cancelar</a>
        </div>
    </form>
</div>

<script>
    // ── Tipos de Flor ─────────────────────────────────────
    document.getElementById('add-tipo-flor')?.addEventListener('click', function () {
        let row = document.createElement('div');
        row.classList.add('d-flex', 'gap-2', 'mb-2');
        row.innerHTML = `
            <select name="tipos_flor[]" class="form-select">
                <option value="">-- Seleccionar tipo de flor --</option>
                <?php $__currentLoopData = $tiposFlor; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tf): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($tf->id); ?>"><?php echo e($tf->icono); ?> <?php echo e($tf->nombre); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <button type="button" class="btn btn-danger remove-tipo-flor">X</button>`;
        document.getElementById('tipos-flor-container').appendChild(row);
        row.querySelector('.remove-tipo-flor').addEventListener('click', () => row.remove());
    });
    document.querySelectorAll('.remove-tipo-flor').forEach(btn => btn.addEventListener('click', function () {
        this.parentElement.remove();
    }));

    // ── Sabores ──────────────────────────────────────────
    document.getElementById('add-sabor').addEventListener('click', function () {
        let row = document.createElement('div');
        row.classList.add('d-flex', 'gap-2', 'mb-2');
        row.innerHTML = `
            <select name="sabores[]" class="form-select">
                <option value="">-- Seleccionar sabor --</option>
                <?php $__currentLoopData = $sabores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sabor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($sabor->id); ?>"><?php echo e($sabor->nombre); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <button type="button" class="btn btn-danger remove-sabor">X</button>`;
        document.getElementById('sabores-container').appendChild(row);
        row.querySelector('.remove-sabor').addEventListener('click', () => row.remove());
    });
    document.querySelectorAll('.remove-sabor').forEach(btn => btn.addEventListener('click', function () {
        this.parentElement.remove();
    }));

    // ── Efectos ──────────────────────────────────────────
    document.getElementById('add-efecto').addEventListener('click', function () {
        let row = document.createElement('div');
        row.classList.add('d-flex', 'gap-2', 'mb-2');
        row.innerHTML = `
            <select name="efectos[]" class="form-select">
                <option value="">-- Seleccionar efecto --</option>
                <?php $__currentLoopData = $efectos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $efecto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($efecto->id); ?>"><?php echo e($efecto->nombre); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <button type="button" class="btn btn-danger remove-efecto">X</button>`;
        document.getElementById('efectos-container').appendChild(row);
        row.querySelector('.remove-efecto').addEventListener('click', () => row.remove());
    });
    document.querySelectorAll('.remove-efecto').forEach(btn => btn.addEventListener('click', function () {
        this.parentElement.remove();
    }));

    // ── Colores ──────────────────────────────────────────
    document.getElementById('add-color').addEventListener('click', function () {
        let row = document.createElement('div');
        row.classList.add('d-flex', 'gap-2', 'mb-2');
        row.innerHTML = `
            <select name="colores[]" class="form-select">
                <option value="">-- Seleccionar color --</option>
                <?php $__currentLoopData = $colores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $color): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($color->id); ?>"><?php echo e($color->nombre); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <button type="button" class="btn btn-danger remove-color">X</button>`;
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
        row.classList.add('variante-row', 'border', 'rounded', 'p-3', 'mb-2', 'bg-light');
        if (presetKey) row.dataset.presetKey = presetKey;

        if (fijo) {
            row.innerHTML = `
                <div class="row g-2 align-items-end">
                    <div class="col-md-6">
                        <div class="fw-semibold">${nombre} <span class="text-muted small">· ${cantidad} unidades</span></div>
                        <input type="hidden" name="variantes[${i}][nombre]" value="${nombre}">
                        <input type="hidden" name="variantes[${i}][cantidad_por_variante]" value="${cantidad}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Precio</label>
                        <input type="number" step="0.01" name="variantes[${i}][precio]" class="form-control"
                               placeholder="0.00" min="0" required autofocus>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="button" class="btn btn-outline-danger w-100 remove-variante">Quitar</button>
                    </div>
                </div>`;
        } else {
            row.innerHTML = `
                <div class="row g-2 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label">Presentación</label>
                        <input type="text" name="variantes[${i}][nombre]" class="form-control"
                               value="${nombre}" placeholder="Ej: Paquete x10" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Cantidad (u)</label>
                        <input type="number" name="variantes[${i}][cantidad_por_variante]" class="form-control"
                               value="${cantidad}" step="any" min="0.01" placeholder="10 o 0.5" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Precio</label>
                        <input type="number" step="0.01" name="variantes[${i}][precio]" class="form-control"
                               placeholder="0.00" min="0" required>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="button" class="btn btn-outline-danger w-100 remove-variante">Quitar</button>
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
        tipoWrap.style.display = (catSel.value && catSel.value === floresId) ? '' : 'none';
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
        currentStep = step;
        panes.forEach(pane => pane.classList.toggle('d-none', Number(pane.dataset.step) !== step));
        indicators.forEach(indicator => indicator.classList.toggle('active', Number(indicator.dataset.stepIndicator) === step));
        previousButton.classList.toggle('d-none', step === 1);
        nextButton.classList.toggle('d-none', step === 3);
        submitButton.classList.toggle('d-none', step !== 3);
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
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Usuario\Documents\My Web Sites\POS\Backend\POSRed\resources\views/productos/create.blade.php ENDPATH**/ ?>