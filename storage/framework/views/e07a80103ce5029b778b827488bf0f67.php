

<?php $__env->startSection('content'); ?>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

<style>
    .step-indicator { display: flex; gap: 8px; margin-bottom: 2rem; }
    .step-dot {
        flex: 1; height: 6px; border-radius: 3px;
        background: #dee2e6; transition: background .3s;
    }
    .step-dot.active { background: #0d6efd; }
    .step-dot.done   { background: #198754; }

    .producto-row { background: #f8f9fa; border-radius: 10px; padding: 12px 8px; margin-bottom: 10px; }
    .select2-container { width: 100% !important; }

    #resumen-box { background: #f0f4ff; border-radius: 12px; padding: 20px; }
    #resumen-box .line { display: flex; justify-content: space-between; margin-bottom: 6px; }
    #resumen-box .total-line { font-size: 1.2rem; font-weight: 700; border-top: 2px solid #adb5bd; padding-top: 10px; margin-top: 6px; }

    .variante-info { font-size: 0.8rem; color: #6c757d; margin-top: 4px; }
</style>

<div class="container-fluid d-flex justify-content-center align-items-start py-5">
    <div class="col-lg-8 col-xl-7">
        <div class="card shadow-lg border-0 rounded-4">
            <div class="card-body p-5">

                <h1 class="mb-2 text-center">🛒 Registrar Nueva Venta</h1>

                <div class="step-indicator">
                    <div class="step-dot active" id="dot-1"></div>
                    <div class="step-dot" id="dot-2"></div>
                    <div class="step-dot" id="dot-3"></div>
                    <div class="step-dot" id="dot-4"></div>
                </div>

                <?php if($errors->any()): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form action="<?php echo e(route('ventas.store')); ?>" method="POST" id="venta-form">
                    <?php echo csrf_field(); ?>

                    
                    
                    
                    <div class="step" id="step-1">
                        <h4 class="mb-3">1️⃣ Cliente y Productos</h4>

                        <div class="row g-3 mb-4">
                            <div class="col-md-7">
                                <label class="form-label fw-semibold">Cliente <span class="text-danger">*</span></label>
                                <select name="cliente_id" id="cliente_select" class="form-select" required>
                                    <option value="">Buscar por nombre, teléfono…</option>
                                    <?php $__currentLoopData = $clientes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cliente): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($cliente->id); ?>"
                                            data-nombre="<?php echo e($cliente->nombre); ?>"
                                            <?php echo e(old('cliente_id') == $cliente->id ? 'selected' : ''); ?>>
                                            <?php echo e($cliente->nombre); ?> — <?php echo e($cliente->telefono); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <option value="nuevo">➕ Registrar nuevo cliente</option>
                                </select>
                            </div>
                            <div class="col-md-5">
                                <label class="form-label fw-semibold">Vendedor</label>
                                <select name="vendedor_id" id="vendedor_select" class="form-select">
                                    <option value="">— Sin asignar —</option>
                                    <?php $__currentLoopData = $vendedores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($v->id); ?>"
                                            <?php echo e(old('vendedor_id') == $v->id ? 'selected' : ''); ?>>
                                            <?php echo e($v->nombre); ?>

                                            <?php if($v->comision_porcentaje > 0): ?>
                                                (<?php echo e($v->comision_porcentaje); ?>%)
                                            <?php endif; ?>
                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>

                        <h5 class="fw-bold">Productos y variantes</h5>
                        <div id="productos-container"></div>

                        <div class="d-flex justify-content-between align-items-center mt-3 mb-2">
                            <button type="button" id="add-producto" class="btn btn-outline-secondary btn-sm">
                                ＋ Agregar producto
                            </button>
                            <button type="button" class="btn btn-primary next-btn">
                                Siguiente →
                            </button>
                        </div>
                    </div>

                    
                    <div class="step d-none" id="step-2">
                        <h4 class="mb-3">2️⃣ Descuento</h4>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">¿Aplicar descuento?</label>
                            <select id="usar_descuento" class="form-select">
                                <option value="no">No</option>
                                <option value="catalogo">Sí — desde catálogo</option>
                                <option value="manual">Sí — descuento manual</option>
                            </select>
                        </div>

                        <div id="descuento-catalogo" class="d-none mb-3">
                            <label class="form-label fw-semibold">Descuento disponible</label>
                            <select name="descuento_id" id="descuento_id" class="form-select">
                                <option value="">Seleccione un descuento</option>
                                <?php $__currentLoopData = $descuentos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($d->id); ?>"
                                        data-tipo="<?php echo e($d->tipo); ?>"
                                        data-valor="<?php echo e($d->valor); ?>">
                                        <?php echo e($d->nombre); ?>

                                        <?php if($d->codigo): ?> (<?php echo e($d->codigo); ?>) <?php endif; ?>
                                        —
                                        <?php if($d->tipo === 'porcentaje'): ?> <?php echo e($d->valor); ?>%
                                        <?php else: ?> $<?php echo e(number_format($d->valor, 2, ',', '.')); ?>

                                        <?php endif; ?>
                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        <div id="descuento-manual" class="d-none mb-3">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Monto de descuento ($)</label>
                                    <input type="number" name="descuento_manual" id="descuento_manual_input"
                                           class="form-control" min="0" step="0.01" placeholder="0.00">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Motivo</label>
                                    <input type="text" name="motivo_descuento" class="form-control"
                                           placeholder="Ej: cliente frecuente">
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <button type="button" class="btn btn-secondary prev-btn">← Anterior</button>
                            <button type="button" class="btn btn-primary next-btn">Siguiente →</button>
                        </div>
                    </div>

                    <div class="step d-none" id="step-3">
                        <h4 class="mb-3">3️⃣ Domicilio</h4>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">¿Requiere domicilio?</label>
                            <select name="envio" id="envio_select" class="form-select">
                                <option value="0">No</option>
                                <option value="1">Sí</option>
                            </select>
                        </div>

                        <div id="domicilio-campos" class="d-none">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Dirección</label>
                                    <input type="text" name="direccion" class="form-control"
                                           placeholder="Calle, número, barrio…">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Ciudad</label>
                                    <input type="text" name="ciudad" class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Departamento</label>
                                    <input type="text" name="departamento" class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">País</label>
                                    <input type="text" name="pais" class="form-control" value="Colombia">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Costo de envío ($)</label>
                                    <input type="number" name="costo_envio" id="costo_envio_input"
                                           class="form-control" min="0" step="0.01" placeholder="0.00" value="0">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Fecha estimada de entrega</label>
                                    <input type="date" name="fecha_entrega" class="form-control">
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Comentarios</label>
                                    <textarea name="comentarios" class="form-control" rows="2"
                                              placeholder="Instrucciones especiales, referencias…"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <button type="button" class="btn btn-secondary prev-btn">← Anterior</button>
                            <button type="button" class="btn btn-primary next-btn">Siguiente →</button>
                        </div>
                    </div>

                    <div class="step d-none" id="step-4">
                        <h4 class="mb-3">4️⃣ Resumen de la Venta</h4>

                        <div id="resumen-box">
                            <div class="line"><span class="text-muted">Cliente</span><strong id="r-cliente">—</strong></div>
                            <div id="r-productos-list" class="mb-2"></div>
                            <div class="line"><span class="text-muted">Subtotal</span><span id="r-subtotal">$0</span></div>
                            <div class="line" id="r-descuento-line" style="display:none!important">
                                <span class="text-muted">Descuento</span><span id="r-descuento" class="text-danger">−$0</span>
                            </div>
                            <div class="line" id="r-envio-line" style="display:none!important">
                                <span class="text-muted">Costo envío</span><span id="r-envio">$0</span>
                            </div>
                            <div class="line total-line">
                                <span>TOTAL</span><span id="r-total">$0</span>
                            </div>
                        </div>

                        <div class="mb-3 mt-3">
                            <label class="form-label fw-semibold">Método de pago <span class="text-muted fw-normal small">(opcional)</span></label>
                            <select name="metodo_pago" id="metodo_pago" class="form-select">
                                <option value="">— Sin especificar (pagar después) —</option>
                                <option value="efectivo">💵 Efectivo</option>
                                <option value="transferencia">🏦 Transferencia</option>
                                <option value="tarjeta">💳 Tarjeta</option>
                                <option value="cripto">🪙 Cripto</option>
                                <option value="otro">🔄 Otro</option>
                            </select>
                            <div class="form-text text-muted">
                                La venta se guarda como <strong>Pendiente</strong>. Puedes registrar el pago desde el módulo de Pedidos cuando lo recibas.
                            </div>
                        </div>

                        <div id="cambio-box" class="d-none mb-3">
                            <label class="form-label fw-semibold">Monto recibido ($) <span class="text-muted fw-normal small">(solo referencia, no bloquea)</span></label>
                            <input type="number" id="monto_recibido" class="form-control" min="0" step="1000" placeholder="0">
                            <div class="mt-2 p-2 bg-success-subtle rounded text-success fw-bold" id="cambio-result"></div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <button type="button" class="btn btn-secondary prev-btn">← Anterior</button>
                            <button type="submit" class="btn btn-success btn-lg px-5">
                                💾 Confirmar Venta
                            </button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
/* ============================================================
   DATA: productos con sus variantes desde Laravel
   ============================================================ */
const PRODUCTOS = <?php echo json_encode($productos, 15, 512) ?>;
// Estructura esperada:
// [{ id, nombre, stock, variantes: [{ id, nombre, precio, stock, cantidad_por_variante }] }]

/* ============================================================
   UTILIDADES
   ============================================================ */
const fmt = n => '$' + parseFloat(n || 0).toLocaleString('es-CO', { minimumFractionDigits: 0 });

/* ============================================================
   PASOS
   ============================================================ */
let currentStep = 1;
const totalSteps = 4;

function showStep(n) {
    document.querySelectorAll('.step').forEach(s => s.classList.add('d-none'));
    document.getElementById('step-' + n).classList.remove('d-none');
    for (let i = 1; i <= totalSteps; i++) {
        const dot = document.getElementById('dot-' + i);
        dot.classList.remove('active', 'done');
        if (i < n)  dot.classList.add('done');
        if (i === n) dot.classList.add('active');
    }
    currentStep = n;
}

document.querySelectorAll('.next-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        if (currentStep === 1 && !validarPaso1()) return;
        if (currentStep < totalSteps) {
            if (currentStep === totalSteps - 1) buildResumen();
            showStep(currentStep + 1);
        }
    });
});

document.querySelectorAll('.prev-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        if (currentStep > 1) showStep(currentStep - 1);
    });
});

/* ============================================================
   SELECT2 – CLIENTE
   ============================================================ */
$(document).ready(function () {
    $('#cliente_select').select2({
        theme: 'bootstrap-5',
        placeholder: 'Buscar cliente por nombre o teléfono…',
        allowClear: true,
        width: '100%'
    });

    $('#cliente_select').on('change', function () {
        if ($(this).val() === 'nuevo') {
            window.location.href = "<?php echo e(route('clientes.create')); ?>";
        }
    });
});

/* ============================================================
   VALIDACIÓN PASO 1
   ============================================================ */
function validarPaso1() {
    const cliente = document.getElementById('cliente_select').value;
    if (!cliente || cliente === 'nuevo') {
        alert('Seleccione un cliente válido.');
        return false;
    }

    const rows = document.querySelectorAll('.producto-row');
    if (rows.length === 0) {
        alert('Agregue al menos un producto.');
        return false;
    }

    for (const row of rows) {
        const producto = row.querySelector('.select-producto').value;
        const variante = row.querySelector('.select-variante').value;
        const qty      = row.querySelector('.input-cantidad').value;

        if (!producto) { alert('Seleccione el producto en todas las filas.'); return false; }
        if (!variante) { alert('Seleccione la variante en todas las filas.'); return false; }

        if (variante === '0') {
            const precio = parseFloat(row.querySelector('.input-precio-manual').value) || 0;
            if (precio <= 0) {
                alert('Ingrese un precio mayor a 0 para los productos sin variante.');
                return false;
            }
        }

        if (!qty || qty < 1) { alert('Las cantidades deben ser al menos 1.'); return false; }
    }
    return true;
}

/* ============================================================
   FILA DE PRODUCTO → VARIANTE
   ============================================================ */
function buildProductoRow() {
    const opcionesProducto = PRODUCTOS.map(p =>
        `<option value="${p.id}">${p.nombre} (Stock base: ${p.stock})</option>`
    ).join('');

    const div = document.createElement('div');
    div.className = 'producto-row row g-2 align-items-start mb-2';
    div.innerHTML = `
        <div class="col-md-4">
            <label class="form-label small fw-semibold">Producto</label>
            <select class="form-select select-producto" required>
                <option value="">— Seleccione producto —</option>
                ${opcionesProducto}
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label small fw-semibold">Variante / Presentación</label>
            <select class="form-select select-variante" name="variantes[]" required disabled>
                <option value="">— Primero elija producto —</option>
            </select>
            <div class="variante-info mt-1"></div>
            <div class="precio-manual-group mt-2 d-none">
                <label class="form-label small text-muted mb-1">Precio unitario</label>
                <input type="number" class="form-control form-control-sm input-precio-manual"
                       min="0" step="0.01" placeholder="0.00">
            </div>
        </div>
        <div class="col-md-2">
            <label class="form-label small fw-semibold">Cantidad</label>
            <input type="number" class="form-control input-cantidad" name="cantidades[]"
                   min="1" value="1" required>
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <button type="button" class="btn btn-danger btn-sm btn-remove w-100">✖</button>
        </div>
        <input type="hidden" class="input-producto-id" name="productos[]" value="">
        <input type="hidden" class="input-precio-manual-hidden" name="precios_manuales[]" value="0">`;

    const selectProducto      = div.querySelector('.select-producto');
    const selectVariante      = div.querySelector('.select-variante');
    const infoVariante        = div.querySelector('.variante-info');
    const precioManualGroup   = div.querySelector('.precio-manual-group');
    const inputPrecioManual   = div.querySelector('.input-precio-manual');
    const hiddenProductoId    = div.querySelector('.input-producto-id');
    const hiddenPrecioManual  = div.querySelector('.input-precio-manual-hidden');

    selectProducto.addEventListener('change', function () {
        const productoId = parseInt(this.value);
        const producto   = PRODUCTOS.find(p => p.id === productoId);

        hiddenProductoId.value = this.value || '';
        selectVariante.innerHTML = '<option value="">— Seleccione variante —</option>';
        infoVariante.textContent = '';
        precioManualGroup.classList.add('d-none');
        inputPrecioManual.value  = '';
        hiddenPrecioManual.value = '0';

        if (!producto) {
            selectVariante.disabled = true;
            return;
        }

        const variantesDisponibles = producto.variantes.filter(v => v.activo && v.stock > 0);

        if (!variantesDisponibles.length) {
            // Sin variantes: opción N/A + campo de precio manual
            const naOpt = document.createElement('option');
            naOpt.value              = '0';
            naOpt.dataset.precio     = '0';
            naOpt.dataset.noVariante = 'true';
            naOpt.textContent        = 'N/A — Sin variante';
            selectVariante.appendChild(naOpt);
            selectVariante.value    = '0';
            selectVariante.disabled = false;
            precioManualGroup.classList.remove('d-none');
            infoVariante.innerHTML  = '<span class="text-warning small">⚠ Sin variantes registradas. Ingrese el precio unitario.</span>';
            return;
        }

        variantesDisponibles.forEach(v => {
            const opt = document.createElement('option');
            opt.value           = v.id;
            opt.dataset.precio  = v.precio;
            opt.dataset.stock   = v.stock;
            opt.dataset.consume = v.cantidad_por_variante;
            opt.textContent     = `${v.nombre} — ${fmt(v.precio)} (Stock: ${v.stock})`;
            selectVariante.appendChild(opt);
        });

        selectVariante.disabled = false;
    });

    selectVariante.addEventListener('change', function () {
        const opt = this.options[this.selectedIndex];
        if (!opt || !opt.value) { infoVariante.textContent = ''; return; }

        if (opt.dataset.noVariante === 'true') {
            precioManualGroup.classList.remove('d-none');
            infoVariante.innerHTML = '<span class="text-warning small">⚠ Ingrese el precio unitario.</span>';
            return;
        }

        precioManualGroup.classList.add('d-none');
        const precio  = parseFloat(opt.dataset.precio);
        const stock   = parseInt(opt.dataset.stock);
        const consume = parseInt(opt.dataset.consume);

        infoVariante.innerHTML = `
            💵 Precio: <strong>${fmt(precio)}</strong> &nbsp;|&nbsp;
            📦 Stock variante: <strong>${stock}</strong> &nbsp;|&nbsp;
            🔢 Consume del producto: <strong>${consume} uds</strong>`;

        const inputCantidad = div.querySelector('.input-cantidad');
        inputCantidad.max = stock;
        if (parseInt(inputCantidad.value) > stock) inputCantidad.value = stock;
    });

    // Precio manual: sincroniza el hidden y el data-precio del N/A option
    inputPrecioManual.addEventListener('input', function () {
        const val = parseFloat(this.value) || 0;
        hiddenPrecioManual.value = val;
        const naOpt = selectVariante.querySelector('option[data-no-variante="true"]');
        if (naOpt) naOpt.dataset.precio = val;
    });

    div.querySelector('.btn-remove').addEventListener('click', () => {
        const rows = document.querySelectorAll('.producto-row');
        if (rows.length > 1) div.remove();
        else alert('Debe haber al menos un producto.');
    });

    return div;
}

// Primera fila al cargar
document.getElementById('productos-container').appendChild(buildProductoRow());

document.getElementById('add-producto').addEventListener('click', () => {
    document.getElementById('productos-container').appendChild(buildProductoRow());
});

/* ============================================================
   DESCUENTO
   ============================================================ */
document.getElementById('usar_descuento').addEventListener('change', function () {
    document.getElementById('descuento-catalogo').classList.add('d-none');
    document.getElementById('descuento-manual').classList.add('d-none');
    document.getElementById('descuento_id').value = '';
    document.getElementById('descuento_manual_input').value = '';

    if (this.value === 'catalogo') document.getElementById('descuento-catalogo').classList.remove('d-none');
    if (this.value === 'manual')   document.getElementById('descuento-manual').classList.remove('d-none');
});

/* ============================================================
   DOMICILIO
   ============================================================ */
document.getElementById('envio_select').addEventListener('change', function () {
    const campos = document.getElementById('domicilio-campos');
    this.value === '1' ? campos.classList.remove('d-none') : campos.classList.add('d-none');
});

/* ============================================================
   RESUMEN
   ============================================================ */
function getSubtotal() {
    let sub = 0;
    document.querySelectorAll('.producto-row').forEach(row => {
        const sel = row.querySelector('.select-variante');
        const qty = parseFloat(row.querySelector('.input-cantidad').value) || 0;
        const opt = sel.options[sel.selectedIndex];
        const precio = parseFloat(opt?.dataset?.precio || 0);
        sub += precio * qty;
    });
    return sub;
}

function getDescuento(subtotal) {
    const tipo = document.getElementById('usar_descuento').value;
    if (tipo === 'manual') {
        return parseFloat(document.getElementById('descuento_manual_input').value) || 0;
    }
    if (tipo === 'catalogo') {
        const sel = document.getElementById('descuento_id');
        const opt = sel.options[sel.selectedIndex];
        if (!opt || !opt.value) return 0;
        const dTipo  = opt.dataset.tipo;
        const dValor = parseFloat(opt.dataset.valor) || 0;
        return dTipo === 'porcentaje' ? (subtotal * dValor / 100) : dValor;
    }
    return 0;
}

function buildResumen() {
    // Cliente
    const cSel = document.getElementById('cliente_select');
    const cOpt = cSel.options[cSel.selectedIndex];
    document.getElementById('r-cliente').textContent = cOpt ? cOpt.text : '—';

    // Productos + variantes
    let prodHtml = '';
    let subtotal = 0;

    document.querySelectorAll('.producto-row').forEach(row => {
        const selProducto = row.querySelector('.select-producto');
        const selVariante = row.querySelector('.select-variante');
        const qty = parseFloat(row.querySelector('.input-cantidad').value) || 0;

        const optProducto = selProducto.options[selProducto.selectedIndex];
        const optVariante = selVariante.options[selVariante.selectedIndex];

        if (!optVariante || !optVariante.value) return;

        const precio = parseFloat(optVariante.dataset.precio || 0);
        const linea  = precio * qty;
        subtotal    += linea;

        const nombreProducto = optProducto?.text?.split('(')[0].trim() ?? '—';
        const nombreVariante = optVariante.text?.split('—')[0].trim() ?? '—';

        prodHtml += `
            <div class="line text-muted small">
                <span>${nombreProducto} <span class="badge bg-secondary">${nombreVariante}</span> × ${qty}</span>
                <span>${fmt(linea)}</span>
            </div>`;
    });

    document.getElementById('r-productos-list').innerHTML = prodHtml;
    document.getElementById('r-subtotal').textContent = fmt(subtotal);

    // Descuento
    const descuento = getDescuento(subtotal);
    const dLine = document.getElementById('r-descuento-line');
    if (descuento > 0) {
        document.getElementById('r-descuento').textContent = '−' + fmt(descuento);
        dLine.style.removeProperty('display');
    } else {
        dLine.style.setProperty('display', 'none', 'important');
    }

    // Envío
    const esEnvio = document.getElementById('envio_select').value === '1';
    const costoEnvio = esEnvio
        ? (parseFloat(document.getElementById('costo_envio_input').value) || 0)
        : 0;
    const eLine = document.getElementById('r-envio-line');
    if (esEnvio) {
        document.getElementById('r-envio').textContent = fmt(costoEnvio);
        eLine.style.removeProperty('display');
    } else {
        eLine.style.setProperty('display', 'none', 'important');
    }

    document.getElementById('r-total').textContent = fmt(subtotal - descuento + costoEnvio);
}

/* ============================================================
   MÉTODO DE PAGO / CAMBIO
   ============================================================ */
document.getElementById('metodo_pago').addEventListener('change', function () {
    const box = document.getElementById('cambio-box');
    box.classList.toggle('d-none', this.value !== 'efectivo');
    document.getElementById('cambio-result').textContent = '';
    document.getElementById('monto_recibido').value = '';
});

document.getElementById('monto_recibido').addEventListener('input', function () {
    const total = parseFloat(document.getElementById('r-total').textContent.replace(/[^0-9]/g, '')) || 0;
    const recibido = parseFloat(this.value) || 0;
    const cambio = recibido - total;
    const el = document.getElementById('cambio-result');
    if (recibido > 0) {
        el.textContent = cambio >= 0 ? 'Cambio: ' + fmt(cambio) : '⚠ Monto insuficiente';
        el.className = 'mt-2 p-2 rounded fw-bold ' + (cambio >= 0 ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger');
    } else {
        el.textContent = '';
    }
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Usuario\Documents\My Web Sites\POS\Backend\POSRed\resources\views/ventas/create.blade.php ENDPATH**/ ?>