<?php $__env->startSection('content'); ?>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>

<div class="container py-3" style="max-width:780px">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">✏️ Editar Domicilio #<?php echo e($domicilio->id); ?></h1>
        <a href="<?php echo e(route('domicilios.show', $domicilio)); ?>" class="btn btn-outline-secondary btn-sm">← Volver</a>
    </div>

    <?php if($errors->any()): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <ul class="mb-0"><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($e); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>

    <form action="<?php echo e(route('domicilios.update', $domicilio)); ?>" method="POST">
        <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>

        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3">📍 Dirección</h6>
                <div class="row g-3">

                    <div class="col-12">
                        <label class="form-label fw-semibold">Dirección <span class="text-danger">*</span></label>
                        <input type="text" name="direccion" id="campo-direccion"
                               class="form-control <?php $__errorArgs = ['direccion'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               value="<?php echo e(old('direccion', $domicilio->direccion)); ?>" required>
                        <?php $__errorArgs = ['direccion'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Zona / Barrio</label>
                        <select name="zona_id" class="form-select <?php $__errorArgs = ['zona_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <option value="">— Sin zona específica —</option>
                            <?php $__currentLoopData = $zonas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $z): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($z->id); ?>"
                                    <?php echo e(old('zona_id', $domicilio->zona_id) == $z->id ? 'selected' : ''); ?>>
                                    <?php echo e($z->nombre); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php $__errorArgs = ['zona_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Referencia de ubicación</label>
                        <input type="text" name="referencia_ubicacion"
                               class="form-control <?php $__errorArgs = ['referencia_ubicacion'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               value="<?php echo e(old('referencia_ubicacion', $domicilio->referencia_ubicacion)); ?>"
                               placeholder="Ej: cerca al parque, frente a…">
                        <?php $__errorArgs = ['referencia_ubicacion'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Ciudad</label>
                        <input type="text" name="ciudad" class="form-control"
                               value="<?php echo e(old('ciudad', $domicilio->ciudad)); ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Departamento</label>
                        <input type="text" name="departamento" class="form-control"
                               value="<?php echo e(old('departamento', $domicilio->departamento)); ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">País</label>
                        <input type="text" name="pais" class="form-control"
                               value="<?php echo e(old('pais', $domicilio->pais ?? 'Colombia')); ?>">
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold">Comentarios</label>
                        <textarea name="comentarios" class="form-control" rows="2"><?php echo e(old('comentarios', $domicilio->comentarios)); ?></textarea>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="fw-bold mb-0">🗺 Ubicación en el mapa</h6>
                    <button type="button" id="btn-geocodificar" class="btn btn-sm btn-outline-primary">
                        🔍 Buscar dirección en mapa
                    </button>
                </div>
                <p class="text-muted small mb-2">Haz clic en el mapa o usa el botón para ubicar la dirección.</p>

                <div id="mapa-domicilio" style="height:320px;border-radius:10px;border:1px solid #dee2e6"></div>

                <div class="row g-2 mt-2">
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Latitud</label>
                        <input type="number" name="latitud" id="campo-lat" step="0.0000001"
                               class="form-control form-control-sm"
                               value="<?php echo e(old('latitud', $domicilio->latitud)); ?>" placeholder="3.4516…">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Longitud</label>
                        <input type="number" name="longitud" id="campo-lng" step="0.0000001"
                               class="form-control form-control-sm"
                               value="<?php echo e(old('longitud', $domicilio->longitud)); ?>" placeholder="-76.5320…">
                    </div>
                </div>
            </div>
        </div>

        
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3">📦 Logística</h6>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Estado</label>
                        <select name="estado" class="form-select">
                            <?php $__currentLoopData = ['pendiente','enviado','entregado','cancelado']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $est): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($est); ?>" <?php echo e(($domicilio->estado ?? '') === $est ? 'selected' : ''); ?>>
                                    <?php echo e(ucfirst($est)); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Costo de envío ($)</label>
                        <input type="number" name="costo_envio" class="form-control" min="0" step="1000"
                               value="<?php echo e(old('costo_envio', $domicilio->costo_envio)); ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Fecha estimada entrega</label>
                        <input type="date" name="fecha_entrega" class="form-control"
                               value="<?php echo e(old('fecha_entrega', $domicilio->fecha_entrega?->format('Y-m-d'))); ?>">
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-warning px-4">💾 Guardar cambios</button>
            <a href="<?php echo e(route('domicilios.show', $domicilio)); ?>" class="btn btn-outline-secondary">Cancelar</a>
        </div>

    </form>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
const CALI = [3.4516467, -76.5319854];
const latInicial = <?php echo e($domicilio->latitud ?? 'null'); ?>;
const lngInicial = <?php echo e($domicilio->longitud ?? 'null'); ?>;

const map = L.map('mapa-domicilio').setView(
    latInicial && lngInicial ? [latInicial, lngInicial] : CALI,
    latInicial ? 16 : 13
);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap'
}).addTo(map);

let marker = null;

function ponerMarcador(lat, lng) {
    if (marker) map.removeLayer(marker);
    marker = L.marker([lat, lng], { draggable: true }).addTo(map);
    document.getElementById('campo-lat').value = lat.toFixed(7);
    document.getElementById('campo-lng').value  = lng.toFixed(7);

    marker.on('dragend', function (e) {
        const pos = e.target.getLatLng();
        document.getElementById('campo-lat').value = pos.lat.toFixed(7);
        document.getElementById('campo-lng').value  = pos.lng.toFixed(7);
    });
}

if (latInicial && lngInicial) {
    ponerMarcador(latInicial, lngInicial);
}

map.on('click', function (e) {
    ponerMarcador(e.latlng.lat, e.latlng.lng);
});

document.getElementById('btn-geocodificar').addEventListener('click', function () {
    const dir = document.getElementById('campo-direccion').value.trim();
    if (!dir) { alert('Ingresa la dirección primero.'); return; }

    const query = encodeURIComponent(dir + ', Cali, Colombia');
    fetch(`https://nominatim.openstreetmap.org/search?q=${query}&format=json&limit=1`)
        .then(r => r.json())
        .then(data => {
            if (!data.length) { alert('No se encontró la dirección en el mapa. Ubícala manualmente.'); return; }
            const lat = parseFloat(data[0].lat);
            const lng = parseFloat(data[0].lon);
            map.setView([lat, lng], 17);
            ponerMarcador(lat, lng);
        })
        .catch(() => alert('Error al buscar la dirección. Verifica tu conexión.'));
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Usuario\Documents\My Web Sites\POS\Backend\POSRed\resources\views/domicilios/edit.blade.php ENDPATH**/ ?>