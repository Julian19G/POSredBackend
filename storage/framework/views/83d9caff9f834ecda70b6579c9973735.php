<?php $__env->startSection('content'); ?>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<style>
    #mapa-rutas { height: calc(100vh - 180px); min-height: 500px; border-radius: 12px; }
    .leaflet-popup-content { min-width: 180px; }
</style>

<div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="mb-0">🗺 Mapa de Domicilios Pendientes</h1>
            <small class="text-muted">Cali, Colombia — <?php echo e($domicilios->count()); ?> domicilio(s) en ruta</small>
        </div>
        <a href="<?php echo e(route('domicilios.index')); ?>" class="btn btn-outline-secondary btn-sm">← Lista</a>
    </div>

    <?php if($domicilios->isEmpty()): ?>
    <div class="alert alert-info">No hay domicilios pendientes con ubicación registrada.</div>
    <?php endif; ?>

    <div id="mapa-rutas"></div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
const DOMICILIOS = <?php echo json_encode($domicilios, 15, 512) ?>;
const CALI = [3.4516467, -76.5319854];

const map = L.map('mapa-rutas').setView(CALI, 13);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© <a href="https://openstreetmap.org">OpenStreetMap</a> contributors',
    maxZoom: 19
}).addTo(map);

const iconoEstados = {
    pendiente: { color: '#ffc107', emoji: '⏳' },
    enviado:   { color: '#0d6efd', emoji: '🚚' },
};

DOMICILIOS.forEach(function (d) {
    if (!d.lat || !d.lng) return;

    const cfg = iconoEstados[d.estado] ?? iconoEstados.pendiente;

    const icono = L.divIcon({
        html: `<div style="
            background:${cfg.color};
            width:32px;height:32px;
            border-radius:50% 50% 50% 0;
            transform:rotate(-45deg);
            border:2px solid #fff;
            box-shadow:0 2px 6px rgba(0,0,0,.3);
            display:flex;align-items:center;justify-content:center;
        "><span style="transform:rotate(45deg);font-size:14px">${cfg.emoji}</span></div>`,
        iconSize: [32, 32],
        iconAnchor: [16, 32],
        popupAnchor: [0, -34],
        className: ''
    });

    L.marker([d.lat, d.lng], { icon: icono })
        .addTo(map)
        .bindPopup(`
            <strong>${d.cliente}</strong><br>
            📍 ${d.direccion}<br>
            ${d.zona ? '🗺 ' + d.zona + '<br>' : ''}
            <span class="badge" style="background:${cfg.color};color:#212529">${d.estado}</span><br>
            <a href="${d.url}" class="btn btn-sm btn-outline-primary mt-1" style="font-size:.75rem">Ver pedido</a>
        `);
});

// Ajustar zoom para mostrar todos los marcadores
const conUbicacion = DOMICILIOS.filter(d => d.lat && d.lng);
if (conUbicacion.length > 0) {
    const bounds = L.latLngBounds(conUbicacion.map(d => [d.lat, d.lng]));
    map.fitBounds(bounds, { padding: [40, 40] });
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Usuario\Documents\My Web Sites\POS\Backend\POSRed\resources\views/domicilios/mapa.blade.php ENDPATH**/ ?>