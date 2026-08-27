<?php $__env->startSection('content'); ?>
<div class="container py-4" style="max-width:720px">

    <h1 class="mb-1">🌐 Publicar tienda</h1>
    <p class="text-muted">Activa un enlace público temporal (túnel Cloudflare) para que clientes entren a la tienda desde internet. Al desactivar, se cierra y no queda nada abierto.</p>

    <div class="card border-0 shadow-sm rounded-4 mb-3">
        <div class="card-body p-4">

            <?php if($corriendo && $url): ?>
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="badge bg-success">● En línea</span>
                    <?php if($desde): ?><span class="text-muted small">desde <?php echo e($desde); ?></span><?php endif; ?>
                </div>

                <label class="form-label small fw-semibold">Link de la tienda</label>
                <div class="input-group mb-2">
                    <input type="text" id="tunnel-url" class="form-control" value="<?php echo e($url); ?>" readonly>
                    <button type="button" class="btn btn-outline-primary" id="btn-copiar">Copiar</button>
                </div>
                <div class="small text-muted mb-3">
                    Link de referido: <code><?php echo e($url); ?>/&lt;codigo&gt;/register</code>
                </div>

                <form action="<?php echo e(route('tunnel.stop')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <button class="btn btn-danger">⏹ Desactivar túnel</button>
                </form>

            <?php elseif($iniciando): ?>
                <div class="mb-3"><span class="badge bg-warning text-dark">● Generando link…</span></div>
                <p class="text-muted">
                    <span class="spinner-border spinner-border-sm"></span>
                    Creando el túnel, esto tarda unos segundos…
                </p>
                <form action="<?php echo e(route('tunnel.stop')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <button class="btn btn-outline-danger btn-sm">Cancelar</button>
                </form>

            <?php else: ?>
                <div class="mb-3"><span class="badge bg-secondary">● Apagado</span></div>

                <?php if(!$tieneCloudflared): ?>
                    <div class="alert alert-warning">
                        No se encontró <code>cloudflared</code>. Instálalo una vez con:
                        <code>winget install --id Cloudflare.cloudflared</code>
                    </div>
                <?php endif; ?>

                <p class="text-muted small">Asegúrate de tener corriendo el <strong>frontend (Vite :5173)</strong> y el backend (:8000) antes de activar.</p>

                <form action="<?php echo e(route('tunnel.start')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <button class="btn btn-success" <?php echo e($tieneCloudflared ? '' : 'disabled'); ?>>▶ Activar túnel</button>
                </form>
            <?php endif; ?>

        </div>
    </div>

    <div class="text-muted small">
        ⚠️ El link es temporal y cambia cada vez que lo activas. Vive mientras tu PC esté encendida con los servicios y el túnel corriendo.
    </div>
</div>

<script>
    // Copiar link
    const btnCopiar = document.getElementById('btn-copiar');
    if (btnCopiar) {
        btnCopiar.addEventListener('click', function () {
            const input = document.getElementById('tunnel-url');
            navigator.clipboard.writeText(input.value).then(() => {
                this.textContent = '¡Copiado!';
                setTimeout(() => this.textContent = 'Copiar', 1500);
            });
        });
    }

    // Si está generando, consultar el estado hasta que aparezca el link
    <?php if($iniciando): ?>
    (function () {
        const t = setInterval(function () {
            fetch('<?php echo e(route('tunnel.status')); ?>', { headers: { 'Accept': 'application/json' } })
                .then(r => r.ok ? r.json() : null)
                .then(d => {
                    if (!d) return;
                    if (d.state === 'online' || d.state === 'off') {
                        clearInterval(t);
                        location.reload();
                    }
                })
                .catch(() => {});
        }, 2500);
    })();
    <?php endif; ?>
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Usuario\Documents\My Web Sites\POS\Backend\POSRed\resources\views\tunnel\index.blade.php ENDPATH**/ ?>