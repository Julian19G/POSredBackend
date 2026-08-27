<?php $__env->startSection('content'); ?>
<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-4 mt-3">
        <h1 class="mb-0"><?php echo e($cliente->nombre); ?></h1>
        <div class="d-flex gap-2">
            <a href="<?php echo e(route('clientes.edit', $cliente)); ?>" class="btn btn-warning">✏️ Editar</a>
            <a href="<?php echo e(route('clientes.index')); ?>" class="btn btn-secondary">← Volver</a>
        </div>
    </div>

    <div class="row g-4">

        
        <div class="col-md-5">

            <div class="card border-0 shadow-sm rounded-4 mb-3">
                <div class="card-body">
                    <h5 class="mb-3">Datos del cliente</h5>
                    <table class="table table-sm table-borderless mb-0">
                        <?php if($cliente->email): ?>
                        <tr><th>Email</th><td><?php echo e($cliente->email); ?></td></tr>
                        <?php endif; ?>
                        <?php if($cliente->telefono): ?>
                        <tr><th>Teléfono</th><td>📞 <?php echo e($cliente->telefono); ?></td></tr>
                        <?php endif; ?>
                        <?php if($cliente->whatsapp): ?>
                        <tr>
                            <th>WhatsApp</th>
                            <td>
                                <a href="https://wa.me/57<?php echo e(preg_replace('/\D/', '', $cliente->whatsapp)); ?>" target="_blank">
                                    💬 <?php echo e($cliente->whatsapp); ?>

                                </a>
                            </td>
                        </tr>
                        <?php endif; ?>
                        <?php if($cliente->instagram): ?>
                        <tr><th>Instagram</th><td>📸 {{ $cliente->instagram }}</td></tr>
                        <?php endif; ?>
                        <?php if($cliente->fecha_nacimiento): ?>
                        <tr><th>Cumpleaños</th><td>🎂 <?php echo e($cliente->fecha_nacimiento->format('d/m/Y')); ?></td></tr>
                        <?php endif; ?>
                        <?php if($cliente->ciudad || $cliente->barrio): ?>
                        <tr>
                            <th>Ubicación</th>
                            <td>
                                <?php echo e(collect([$cliente->barrio, $cliente->ciudad])->filter()->join(', ')); ?>

                            </td>
                        </tr>
                        <?php endif; ?>
                        <?php if($cliente->direccion): ?>
                        <tr><th>Dirección</th><td><?php echo e($cliente->direccion); ?></td></tr>
                        <?php endif; ?>
                        <?php if($cliente->referidoPor): ?>
                        <tr>
                            <th>Referido por</th>
                            <td>
                                <a href="<?php echo e(route('clientes.show', $cliente->referidoPor)); ?>">
                                    <?php echo e($cliente->referidoPor->nombre); ?>

                                </a>
                            </td>
                        </tr>
                        <?php endif; ?>
                        <?php if($cliente->referidos->count()): ?>
                        <tr><th>Referidos</th><td><?php echo e($cliente->referidos->count()); ?> cliente(s)</td></tr>
                        <?php endif; ?>
                    </table>

                    <?php if($cliente->notas): ?>
                    <hr class="my-3">
                    <div class="small text-muted fw-semibold mb-1">Notas</div>
                    <div class="small fst-italic"><?php echo e($cliente->notas); ?></div>
                    <?php endif; ?>
                </div>
            </div>

            
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body">
                    <h6 class="mb-3">Resumen</h6>
                    <div class="row text-center g-2">
                        <div class="col-4">
                            <div class="fs-4 fw-bold text-primary"><?php echo e($ventas->total()); ?></div>
                            <div class="small text-muted">Ventas</div>
                        </div>
                        <div class="col-4">
                            <div class="fw-bold text-success" style="font-size:1.1rem">
                                $<?php echo e(number_format($totalGastado, 0, ',', '.')); ?>

                            </div>
                            <div class="small text-muted">Total</div>
                        </div>
                        <div class="col-4">
                            <div class="fs-4 fw-bold text-warning"><?php echo e($pendientes); ?></div>
                            <div class="small text-muted">Pendientes</div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        
        <div class="col-md-7">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 pt-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Historial de compras</h5>
                    <a href="<?php echo e(route('ventas.create')); ?>" class="btn btn-sm btn-primary">+ Nueva venta</a>
                </div>
                <div class="card-body p-0">
                    <?php if($ventas->isEmpty()): ?>
                        <p class="text-muted p-3">Este cliente no tiene ventas aún.</p>
                    <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">#</th>
                                    <th>Fecha</th>
                                    <th>Total</th>
                                    <th>Pago</th>
                                    <th>Estado</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $ventas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td class="ps-3 text-muted small"><?php echo e($v->id); ?></td>
                                    <td><small><?php echo e($v->created_at->format('d/m/Y')); ?></small></td>
                                    <td class="fw-semibold">$<?php echo e(number_format($v->total, 0, ',', '.')); ?></td>
                                    <td>
                                        <?php if($v->pedido?->metodo_pago): ?>
                                            <span class="badge bg-light text-dark"><?php echo e(Str::ucfirst($v->pedido->metodo_pago)); ?></span>
                                        <?php else: ?>
                                            <span class="text-muted small">—</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php switch($v->estado):
                                            case ('pagada'): ?>    <span class="badge bg-success">Pagada</span>    <?php break; ?>
                                            <?php case ('pendiente'): ?> <span class="badge bg-warning text-dark">Pendiente</span> <?php break; ?>
                                            <?php case ('cancelada'): ?> <span class="badge bg-danger">Cancelada</span>  <?php break; ?>
                                        <?php endswitch; ?>
                                    </td>
                                    <td>
                                        <a href="<?php echo e(route('ventas.show', $v->id)); ?>" class="btn btn-sm btn-outline-secondary py-0 px-2">Ver</a>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                    <?php if($ventas->hasPages()): ?>
                        <div class="p-3"><?php echo e($ventas->links()); ?></div>
                    <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Usuario\Documents\My Web Sites\POS\Backend\POSRed\resources\views\clientes\show.blade.php ENDPATH**/ ?>