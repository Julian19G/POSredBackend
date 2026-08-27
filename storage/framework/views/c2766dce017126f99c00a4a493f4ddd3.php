

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

    .prod-card .btn-outline-danger { border-radius:9px; font-weight:600; }
    .prod-card .btn-danger { border-radius:9px; font-weight:600; }

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
    .prod-actions .btn-secondary {
        background: var(--panel-bg-alt);
        border:1px solid var(--panel-border);
        color: var(--text-muted);
        border-radius:10px;
        padding:.65rem 1.75rem;
    }
    .prod-actions .btn-secondary:hover {
        background: var(--panel-border);
        color: var(--text-primary);
    }

    .img-thumbnail {
        background: var(--panel-bg-alt);
        border:1px solid var(--panel-border) !important;
        border-radius:12px !important;
    }

    .alert-danger {
        background: rgba(220,53,69,.1);
        border:1px solid rgba(220,53,69,.3);
        color:#f3a4ac;
        border-radius:12px;
    }
</style>

<div class="container py-4" style="max-width:880px">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h3 mb-0">✏️ Editar producto</h1>
            <small class="text-muted">Actualiza la información y sus presentaciones.</small>
        </div>
        <a href="<?php echo e(route('productos.index')); ?>" class="btn btn-outline-secondary btn-sm">← Volver</a>
    </div>

    
    <?php if($errors->any()): ?>
        <div class="alert alert-danger mb-3">
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

        <div class="prod-card mb-3"><div class="card-body">

        <div class="prod-sec">📝 Información básica</div>

        <div class="mb-3">
            <label for="nombre" class="form-label fw-semibold">Nombre</label>
            <input type="text" name="nombre" id="nombre" class="form-control"
                   value="<?php echo e(old('nombre', $producto->nombre)); ?>" required>
        </div>

        <div class="mb-3">
            <label for="descripcion" class="form-label fw-semibold">Descripción</label>
            <textarea name="descripcion" id="descripcion" class="form-control" rows="2"><?php echo e(old('descripcion', $producto->descripcion)); ?></textarea>
        </div>

        <div class="prod-sec divider">📦 Stock, categoría y detalles</div>

        <div class="mb-3">
            <label for="stock" class="form-label fw-semibold">Stock total (unidades base)</label>
            <input type="number" name="stock" id="stock" class="form-control" step="any" min="0"
                   value="<?php echo e(old('stock', $producto->stock)); ?>" required>
            <small class="text-muted">La disponibilidad de cada presentación se calcula sola (ej. 450 ÷ 10 = 45 paquetes x10).</small>
        </div>

        <div class="mb-3">
            <label for="categoria_id" class="form-label">Categoría</label>
            <select name="categoria_id" id="categoria_id" class="form-select" data-flores="<?php echo e($floresId); ?>">
                <option value="">-- Seleccionar categoría --</option>
                <?php $__currentLoopData = $categorias; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $categoria): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($categoria->id); ?>"
                        <?php echo e(old('categoria_id', $producto->categoria_id) == $categoria->id ? 'selected' : ''); ?>>
                        <?php echo e($categoria->nombre); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>

        
        <div class="mb-3" id="tipo-flor-wrap" style="display:none">
            <label class="form-label">🌸 Tipo de flor</label>
            <select name="tipo_flor_id" class="form-select">
                <option value="">-- Sin especificar --</option>
                <?php $__currentLoopData = $tiposFlor; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tf): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($tf->id); ?>" <?php echo e(old('tipo_flor_id', $producto->tipo_flor_id) == $tf->id ? 'selected' : ''); ?>>
                        <?php echo e($tf->icono); ?> <?php echo e($tf->nombre); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="imagen" class="form-label">Imagen</label>
            <?php if($producto->imagen): ?>
                <div class="mb-2">
                    <img src="<?php echo e(asset('storage/' . $producto->imagen)); ?>" alt="Imagen del producto"
                         class="img-thumbnail shadow-sm" style="max-width: 150px;">
                </div>
            <?php endif; ?>
            <input type="file" name="imagen" id="imagen" class="form-control">
        </div>

        <div class="mb-3">
            <label for="activo" class="form-label">Estado</label>
            <select class="form-select" name="activo" id="activo">
                <option value="1" <?php echo e(old('activo', $producto->activo) == 1 ? 'selected' : ''); ?>>Activo</option>
                <option value="0" <?php echo e(old('activo', $producto->activo) == 0 ? 'selected' : ''); ?>>Inactivo</option>
            </select>
        </div>

        
        <div class="mb-3" id="motivo-inactivo-wrapper">
            <label for="motivo_inactivo" class="form-label">Motivo de inhabilitación</label>
            <select class="form-select" name="motivo_inactivo" id="motivo_inactivo">
                <option value="">-- Seleccionar motivo --</option>
                <?php $__currentLoopData = \App\Models\Producto::MOTIVOS_INACTIVO; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $motivo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($motivo); ?>"
                        <?php echo e(old('motivo_inactivo', $producto->motivo_inactivo) == $motivo ? 'selected' : ''); ?>>
                        <?php echo e($motivo); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>

            
            <div class="mt-2" id="motivo-detalle-wrapper">
                <label for="motivo_inactivo_detalle" class="form-label">Describe el motivo</label>
                <textarea name="motivo_inactivo_detalle" id="motivo_inactivo_detalle"
                          class="form-control" rows="2"
                          placeholder="Especifica por qué se inhabilita el producto"><?php echo e(old('motivo_inactivo_detalle', $producto->motivo_inactivo_detalle)); ?></textarea>
            </div>
        </div>

        <div class="prod-sec divider">✨ Atributos</div>

        
        <div class="mb-3">
            <label class="form-label">Sabores</label>
            <div id="sabores-container">
                <?php $__currentLoopData = old('sabores', $producto->sabores->pluck('id')->toArray()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $saborSeleccionado): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="d-flex gap-2 mb-2">
                        <select name="sabores[]" class="form-select">
                            <option value="">-- Seleccionar sabor --</option>
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
                            <option value="">-- Seleccionar sabor --</option>
                            <?php $__currentLoopData = $sabores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sabor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($sabor->id); ?>"><?php echo e($sabor->nombre); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <button type="button" class="btn btn-danger remove-sabor">X</button>
                    </div>
                <?php endif; ?>
            </div>
            <button type="button" id="add-sabor" class="btn btn-primary btn-sm">Agregar sabor</button>
        </div>

        
        <div class="mb-3">
            <label class="form-label">Efectos</label>
            <div id="efectos-container">
                <?php $__currentLoopData = old('efectos', $producto->efectos->pluck('id')->toArray()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $efectoSeleccionado): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="d-flex gap-2 mb-2">
                        <select name="efectos[]" class="form-select">
                            <option value="">-- Seleccionar efecto --</option>
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
                            <option value="">-- Seleccionar efecto --</option>
                            <?php $__currentLoopData = $efectos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $efecto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($efecto->id); ?>"><?php echo e($efecto->nombre); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <button type="button" class="btn btn-danger remove-efecto">X</button>
                    </div>
                <?php endif; ?>
            </div>
            <button type="button" id="add-efecto" class="btn btn-primary btn-sm">Agregar efecto</button>
        </div>

        
        <div class="mb-3">
            <label class="form-label">Colores</label>
            <div id="colores-container">
                <?php $__currentLoopData = old('colores', $producto->colores->pluck('id')->toArray()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $colorSeleccionado): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="d-flex gap-2 mb-2">
                        <select name="colores[]" class="form-select">
                            <option value="">-- Seleccionar color --</option>
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
                            <option value="">-- Seleccionar color --</option>
                            <?php $__currentLoopData = $colores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $color): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($color->id); ?>"><?php echo e($color->nombre); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <button type="button" class="btn btn-danger remove-color">X</button>
                    </div>
                <?php endif; ?>
            </div>
            <button type="button" id="add-color" class="btn btn-primary btn-sm">Agregar color</button>
        </div>

        <div class="prod-sec divider">🏷️ Presentaciones y precios</div>

        
        <div class="mb-2">
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
            <small class="text-muted d-block mb-2">Marca una presentación para agregarla. Las que ya tiene el producto aparecen abajo.</small>
            <?php endif; ?>

            <div id="variantes-container">
                <?php $__currentLoopData = $producto->variantes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $variante): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="variante-row border rounded p-3 mb-2 bg-light">
                        <input type="hidden" name="variantes[<?php echo e($i); ?>][id]" value="<?php echo e($variante->id); ?>">
                        <div class="row g-2 align-items-end">
                            <div class="col-md-4">
                                <label class="form-label">Presentación</label>
                                <input type="text" name="variantes[<?php echo e($i); ?>][nombre]"
                                    class="form-control" value="<?php echo e(old("variantes.$i.nombre", $variante->nombre)); ?>" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Cantidad (u)</label>
                                <input type="number" name="variantes[<?php echo e($i); ?>][cantidad_por_variante]"
                                    class="form-control" value="<?php echo e(old("variantes.$i.cantidad_por_variante", $variante->cantidad_por_variante)); ?>" step="any" min="0.01" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Precio</label>
                                <input type="number" step="0.01" name="variantes[<?php echo e($i); ?>][precio]"
                                    class="form-control" value="<?php echo e(old("variantes.$i.precio", $variante->precio)); ?>" min="0" required>
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="button" class="btn btn-outline-danger w-100 remove-variante">Quitar</button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <button type="button" id="add-variante" class="btn btn-outline-success btn-sm">
                ➕ Agregar presentación manual
            </button>
        </div>

        </div></div>

        <div class="prod-actions d-flex gap-2">
            <button type="submit" class="btn btn-success px-4">💾 Actualizar</button>
            <a href="<?php echo e(route('productos.index')); ?>" class="btn btn-secondary px-4">Cancelar</a>
        </div>
    </form>
</div>

<script>
    // ── Estado / motivo de inhabilitación ─────────────────
    (function () {
        const estado        = document.getElementById('activo');
        const motivoWrapper = document.getElementById('motivo-inactivo-wrapper');
        const motivoSelect  = document.getElementById('motivo_inactivo');
        const detalleWrap   = document.getElementById('motivo-detalle-wrapper');

        function toggleMotivo() {
            motivoWrapper.style.display = estado.value === '0' ? '' : 'none';
            toggleDetalle();
        }
        function toggleDetalle() {
            const mostrar = estado.value === '0' && motivoSelect.value === 'Otro';
            detalleWrap.style.display = mostrar ? '' : 'none';
        }

        estado.addEventListener('change', toggleMotivo);
        motivoSelect.addEventListener('change', toggleDetalle);
        toggleMotivo();
    })();

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
    let varianteIndex = <?php echo e($producto->variantes->count()); ?>;
    const contVar = document.getElementById('variantes-container');

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

    // Filas existentes: enlazar su botón Quitar
    document.querySelectorAll('#variantes-container .remove-variante').forEach(btn =>
        btn.addEventListener('click', function () { this.closest('.variante-row').remove(); }));

    // ── Tipo de flor: mostrar solo si la categoría es Flores ──
    const catSel   = document.getElementById('categoria_id');
    const floresId = catSel?.dataset.flores;
    const tipoWrap = document.getElementById('tipo-flor-wrap');
    function toggleTipoFlor() {
        if (!tipoWrap) return;
        tipoWrap.style.display = (catSel.value && catSel.value === floresId) ? '' : 'none';
    }
    if (catSel) { catSel.addEventListener('change', toggleTipoFlor); toggleTipoFlor(); }
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Usuario\Documents\My Web Sites\POS\Backend\POSRed\resources\views\productos\edit.blade.php ENDPATH**/ ?>