<?php $__env->startSection('content'); ?>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>

<div class="container py-3">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">🚚 Domicilio #<?php echo e($domicilio->id); ?></h1>
        <div class="d-flex gap-2">
            <a href="<?php echo e(route('domicilios.edit', $domicilio)); ?>" class="btn btn-warning btn-sm">✏️ Editar</a>
            <a href="<?php echo e(route('domicilios.index')); ?>" class="btn btn-outline-secondary btn-sm">← Volver</a>
        </div>
    </div>

    <?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <?php echo e(session('success')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>

    <div class="row g-4">

        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3">📍 Información del domicilio</h6>

                    <dl class="row mb-0">
                        <dt class="col-sm-4 text-muted small">Cliente</dt>
                        <dd class="col-sm-8">
                            <?php if($domicilio->venta->cliente ?? null): ?>
                                <a href="<?php echo e(route('clientes.show', $domicilio->venta->cliente_id)); ?>">
                                    <?php echo e($domicilio->venta->cliente->nombre); ?>

                                </a>
                                <br><small class="text-muted"><?php echo e($domicilio->venta->cliente->telefono); ?></small>
                            <?php else: ?> —
                            <?php endif; ?>
                        </dd>

                        <dt class="col-sm-4 text-muted small">Dirección</dt>
                        <dd class="col-sm-8 fw-semibold"><?php echo e($domicilio->direccion); ?></dd>

                        <?php if($domicilio->referencia_ubicacion): ?>
                        <dt class="col-sm-4 text-muted small">Referencia</dt>
                        <dd class="col-sm-8"><?php echo e($domicilio->referencia_ubicacion); ?></dd>
                        <?php endif; ?>

                        <?php if($domicilio->zona): ?>
                        <dt class="col-sm-4 text-muted small">Zona</dt>
                        <dd class="col-sm-8"><?php echo e($domicilio->zona->nombre); ?></dd>
                        <?php endif; ?>

                        <dt class="col-sm-4 text-muted small">Ciudad</dt>
                        <dd class="col-sm-8"><?php echo e($domicilio->ciudad ?? '—'); ?>, <?php echo e($domicilio->departamento ?? ''); ?></dd>

                        <dt class="col-sm-4 text-muted small">Comentarios</dt>
                        <dd class="col-sm-8 fst-italic"><?php echo e($domicilio->comentarios ?? '—'); ?></dd>

                        <dt class="col-sm-4 text-muted small">Estado</dt>
                        <dd class="col-sm-8">
                            <span class="badge bg-<?php echo e($domicilio->estado === 'entregado' ? 'success' : ($domicilio->estado === 'cancelado' ? 'danger' : 'warning')); ?>">
                                <?php echo e(ucfirst($domicilio->estado)); ?>

                            </span>
                        </dd>

                        <dt class="col-sm-4 text-muted small">Costo envío</dt>
                        <dd class="col-sm-8">$<?php echo e(number_format($domicilio->costo_envio ?? 0, 0, ',', '.')); ?></dd>

                        <?php if($domicilio->fecha_envio): ?>
                        <dt class="col-sm-4 text-muted small">Enviado</dt>
                        <dd class="col-sm-8"><?php echo e($domicilio->fecha_envio->format('d/m/Y H:i')); ?></dd>
                        <?php endif; ?>

                        <?php if($domicilio->fecha_entrega): ?>
                        <dt class="col-sm-4 text-muted small">Entregado</dt>
                        <dd class="col-sm-8"><?php echo e($domicilio->fecha_entrega->format('d/m/Y H:i')); ?></dd>
                        <?php endif; ?>

                        <dt class="col-sm-4 text-muted small">Venta</dt>
                        <dd class="col-sm-8">
                            <a href="<?php echo e(route('ventas.show', $domicilio->venta_id)); ?>">#<?php echo e($domicilio->venta_id); ?></a>
                        </dd>
                    </dl>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-2">🗺 Ubicación</h6>
                    <?php if($domicilio->tieneUbicacion()): ?>
                    <div id="mapa-show" style="height:280px;border-radius:10px"></div>
                    <?php else: ?>
                    <div class="d-flex flex-column align-items-center justify-content-center text-muted"
                         style="height:280px;background:#f8f9fa;border-radius:10px">
                        <span style="font-size:2rem">📌</span>
                        <p class="mb-2">Sin coordenadas registradas</p>
                        <a href="<?php echo e(route('domicilios.edit', $domicilio)); ?>" class="btn btn-sm btn-outline-primary">
                            Agregar ubicación
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div>

</div>

<?php if($domicilio->tieneUbicacion()): ?>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
const map = L.map('mapa-show').setView([<?php echo e($domicilio->latitud); ?>, <?php echo e($domicilio->longitud); ?>], 16);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap'
}).addTo(map);
L.marker([<?php echo e($domicilio->latitud); ?>, <?php echo e($domicilio->longitud); ?>])
    .addTo(map)
    .bindPopup(`<strong><?php echo e(addslashes($domicilio->direccion)); ?></strong><br><?php echo e(addslashes($domicilio->venta->cliente->nombre ?? '')); ?>`)
    .openPopup();
</script>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Usuario\Documents\My Web Sites\POS\Backend\POSRed\resources\views\domicilios\show.blade.php ENDPATH**/ ?>