

<?php $__env->startSection('content'); ?>
<?php
    $esDomiciliario = $esDomiciliario ?? false;
    $esAdmin = $esAdmin ?? false;
?>
<div class="dashboard-page">
    <?php if($esDomiciliario): ?>
        <?php if($sinDomiciliario ?? false): ?>
            <section class="dashboard-empty"><span class="dashboard-empty-icon">⚠️</span><h1>Perfil pendiente</h1><p>Contacta al administrador para configurar tu perfil de domiciliario.</p></section>
        <?php else: ?>
            <header class="dashboard-hero">
                <div><span class="dashboard-kicker">Operación de entregas</span><h1>Hola, <?php echo e($domiciliario->nombre); ?></h1><p><?php echo e($domiciliario->vehiculoLabel()); ?> · <?php echo e(now()->format('d/m/Y H:i')); ?></p></div>
                <div class="dashboard-hero-actions"><a href="<?php echo e(route('rutas.disponibles')); ?>" class="dashboard-button dashboard-button-primary">Ver disponibles <span><?php echo e($disponiblesCount); ?></span></a><a href="<?php echo e(route('rutas.index')); ?>" class="dashboard-button dashboard-button-ghost">Mis rutas</a></div>
            </header>
            <section class="dashboard-kpis dashboard-kpis-five">
                <?php if (isset($component)) { $__componentOriginal455d26112cc8348452e1c48a0ad2cf91 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal455d26112cc8348452e1c48a0ad2cf91 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard-kpi','data' => ['icon' => '◎','label' => 'Disponibles','value' => $disponiblesCount,'tone' => 'gold']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dashboard-kpi'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => '◎','label' => 'Disponibles','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($disponiblesCount),'tone' => 'gold']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal455d26112cc8348452e1c48a0ad2cf91)): ?>
<?php $attributes = $__attributesOriginal455d26112cc8348452e1c48a0ad2cf91; ?>
<?php unset($__attributesOriginal455d26112cc8348452e1c48a0ad2cf91); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal455d26112cc8348452e1c48a0ad2cf91)): ?>
<?php $component = $__componentOriginal455d26112cc8348452e1c48a0ad2cf91; ?>
<?php unset($__componentOriginal455d26112cc8348452e1c48a0ad2cf91); ?>
<?php endif; ?>
                <?php if (isset($component)) { $__componentOriginal455d26112cc8348452e1c48a0ad2cf91 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal455d26112cc8348452e1c48a0ad2cf91 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard-kpi','data' => ['icon' => '↗','label' => 'Aceptados','value' => $aceptadosCount,'tone' => 'info']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dashboard-kpi'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => '↗','label' => 'Aceptados','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($aceptadosCount),'tone' => 'info']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal455d26112cc8348452e1c48a0ad2cf91)): ?>
<?php $attributes = $__attributesOriginal455d26112cc8348452e1c48a0ad2cf91; ?>
<?php unset($__attributesOriginal455d26112cc8348452e1c48a0ad2cf91); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal455d26112cc8348452e1c48a0ad2cf91)): ?>
<?php $component = $__componentOriginal455d26112cc8348452e1c48a0ad2cf91; ?>
<?php unset($__componentOriginal455d26112cc8348452e1c48a0ad2cf91); ?>
<?php endif; ?>
                <?php if (isset($component)) { $__componentOriginal455d26112cc8348452e1c48a0ad2cf91 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal455d26112cc8348452e1c48a0ad2cf91 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard-kpi','data' => ['icon' => '→','label' => 'En camino','value' => $enCaminoCount,'tone' => 'warning']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dashboard-kpi'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => '→','label' => 'En camino','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($enCaminoCount),'tone' => 'warning']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal455d26112cc8348452e1c48a0ad2cf91)): ?>
<?php $attributes = $__attributesOriginal455d26112cc8348452e1c48a0ad2cf91; ?>
<?php unset($__attributesOriginal455d26112cc8348452e1c48a0ad2cf91); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal455d26112cc8348452e1c48a0ad2cf91)): ?>
<?php $component = $__componentOriginal455d26112cc8348452e1c48a0ad2cf91; ?>
<?php unset($__componentOriginal455d26112cc8348452e1c48a0ad2cf91); ?>
<?php endif; ?>
                <?php if (isset($component)) { $__componentOriginal455d26112cc8348452e1c48a0ad2cf91 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal455d26112cc8348452e1c48a0ad2cf91 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard-kpi','data' => ['icon' => '✓','label' => 'Entregas hoy','value' => $entregasHoy,'tone' => 'success']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dashboard-kpi'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => '✓','label' => 'Entregas hoy','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($entregasHoy),'tone' => 'success']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal455d26112cc8348452e1c48a0ad2cf91)): ?>
<?php $attributes = $__attributesOriginal455d26112cc8348452e1c48a0ad2cf91; ?>
<?php unset($__attributesOriginal455d26112cc8348452e1c48a0ad2cf91); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal455d26112cc8348452e1c48a0ad2cf91)): ?>
<?php $component = $__componentOriginal455d26112cc8348452e1c48a0ad2cf91; ?>
<?php unset($__componentOriginal455d26112cc8348452e1c48a0ad2cf91); ?>
<?php endif; ?>
                <?php if (isset($component)) { $__componentOriginal455d26112cc8348452e1c48a0ad2cf91 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal455d26112cc8348452e1c48a0ad2cf91 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard-kpi','data' => ['icon' => '$','label' => 'Ganado hoy','value' => '$'.number_format($gananciaHoy, 0, ',', '.'),'tone' => 'gold']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dashboard-kpi'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => '$','label' => 'Ganado hoy','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute('$'.number_format($gananciaHoy, 0, ',', '.')),'tone' => 'gold']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal455d26112cc8348452e1c48a0ad2cf91)): ?>
<?php $attributes = $__attributesOriginal455d26112cc8348452e1c48a0ad2cf91; ?>
<?php unset($__attributesOriginal455d26112cc8348452e1c48a0ad2cf91); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal455d26112cc8348452e1c48a0ad2cf91)): ?>
<?php $component = $__componentOriginal455d26112cc8348452e1c48a0ad2cf91; ?>
<?php unset($__componentOriginal455d26112cc8348452e1c48a0ad2cf91); ?>
<?php endif; ?>
            </section>
            <section class="dashboard-grid dashboard-grid-two">
                <div class="dashboard-panel"><div class="dashboard-panel-heading"><div><span class="dashboard-kicker">Rendimiento</span><h2>Ganancias de entregas</h2></div><span class="dashboard-live">Actualizado</span></div><div class="dashboard-chart-wrap"><canvas id="dashboard-trend-chart"></canvas></div></div>
                <div class="dashboard-panel"><div class="dashboard-panel-heading"><div><span class="dashboard-kicker">Balance</span><h2>Resumen de ganancias</h2></div></div><div class="dashboard-balance-list"><div><span>Hoy</span><strong>$<?php echo e(number_format($gananciaHoy, 0, ',', '.')); ?></strong></div><div><span>Este mes · <?php echo e($entregasMes); ?> entregas</span><strong>$<?php echo e(number_format($gananciaMes, 0, ',', '.')); ?></strong></div><div><span>Total histórico</span><strong>$<?php echo e(number_format($gananciaTotal, 0, ',', '.')); ?></strong></div><div><span>Domicilios externos</span><strong><?php echo e($externosRealizados); ?></strong></div></div></div>
            </section>
            <?php if($rutaActiva): ?>
                <section class="dashboard-panel dashboard-route"><div class="dashboard-panel-heading"><div><span class="dashboard-kicker">Ruta activa</span><h2><?php echo e($rutaActiva->tipoLabel()); ?> #<?php echo e($rutaActiva->id); ?></h2></div><a href="<?php echo e(route('rutas.show', $rutaActiva)); ?>" class="dashboard-text-link">Continuar →</a></div><div class="dashboard-progress"><span style="width:<?php echo e($rutaActiva->domicilios->count() ? round($rutaActiva->domicilios->where('estado','entregado')->count() / $rutaActiva->domicilios->count() * 100) : 0); ?>%"></span></div><p class="dashboard-muted"><?php echo e($rutaActiva->domicilios->where('estado','entregado')->count()); ?>/<?php echo e($rutaActiva->domicilios->count()); ?> entregados</p></section>
            <?php endif; ?>
            <section class="dashboard-grid dashboard-grid-two"><div class="dashboard-panel"><div class="dashboard-panel-heading"><div><span class="dashboard-kicker">Historial</span><h2>Últimas entregas</h2></div></div><div class="dashboard-activity-list"><?php $__empty_1 = true; $__currentLoopData = $historialEntregas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $entrega): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><div><span class="dashboard-activity-icon">✓</span><div><strong><?php echo e($entrega->nombre_cliente); ?></strong><small><?php echo e($entrega->direccion); ?> · <?php echo e($entrega->fecha_entrega_real?->format('d/m/Y H:i')); ?></small></div><b>$<?php echo e(number_format($entrega->tarifa_monto, 0, ',', '.')); ?></b></div><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><p class="dashboard-muted">Aún no hay entregas registradas.</p><?php endif; ?></div></div><div class="dashboard-panel"><div class="dashboard-panel-heading"><div><span class="dashboard-kicker">Acciones</span><h2>Accesos rápidos</h2></div></div><div class="dashboard-quick-grid"><a href="<?php echo e(route('rutas.disponibles')); ?>">◎<span>Tomar domicilio</span></a><a href="<?php echo e(route('rutas.index')); ?>">▤<span>Mis rutas</span></a><a href="<?php echo e(route('profile.edit')); ?>">◌<span>Mi perfil</span></a><form action="<?php echo e(route('logout')); ?>" method="POST"><?php echo csrf_field(); ?><button type="submit">↪<span>Cerrar sesión</span></button></form></div></div></section>
        <?php endif; ?>
    <?php elseif(!$esAdmin && ($sinVendedor ?? false)): ?>
        <section class="dashboard-empty"><span class="dashboard-empty-icon">⚠️</span><h1>Cuenta pendiente</h1><p>Tu cuenta aún no está vinculada a un vendedor.</p><a href="<?php echo e(route('ventas.create')); ?>" class="dashboard-button dashboard-button-primary">Registrar venta</a></section>
    <?php elseif($esAdmin): ?>
        <?php $hoyStr = now()->toDateString(); $mesStr = now()->startOfMonth()->toDateString(); ?>
        <header class="dashboard-hero"><div><span class="dashboard-kicker">Centro de operaciones</span><h1>Panel de control</h1><p>Hola, <?php echo e(auth()->user()->name); ?> · <?php echo e(now()->format('d/m/Y H:i')); ?></p></div><div class="dashboard-hero-actions"><a href="<?php echo e(route('ventas.create')); ?>" class="dashboard-button dashboard-button-primary">+ Nueva venta</a><a href="<?php echo e(route('productos.create')); ?>" class="dashboard-button dashboard-button-ghost">Nuevo producto</a><a href="<?php echo e(route('clientes.create')); ?>" class="dashboard-button dashboard-button-ghost">Nuevo cliente</a></div></header>
        <section class="dashboard-kpis dashboard-kpis-six">
            <?php if (isset($component)) { $__componentOriginal455d26112cc8348452e1c48a0ad2cf91 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal455d26112cc8348452e1c48a0ad2cf91 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard-kpi','data' => ['icon' => '₿','label' => 'Ventas brutas hoy','value' => '$'.number_format($ventasBrutasHoy, 0, ',', '.'),'tone' => 'gold']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dashboard-kpi'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => '₿','label' => 'Ventas brutas hoy','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute('$'.number_format($ventasBrutasHoy, 0, ',', '.')),'tone' => 'gold']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal455d26112cc8348452e1c48a0ad2cf91)): ?>
<?php $attributes = $__attributesOriginal455d26112cc8348452e1c48a0ad2cf91; ?>
<?php unset($__attributesOriginal455d26112cc8348452e1c48a0ad2cf91); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal455d26112cc8348452e1c48a0ad2cf91)): ?>
<?php $component = $__componentOriginal455d26112cc8348452e1c48a0ad2cf91; ?>
<?php unset($__componentOriginal455d26112cc8348452e1c48a0ad2cf91); ?>
<?php endif; ?>
            <?php if (isset($component)) { $__componentOriginal455d26112cc8348452e1c48a0ad2cf91 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal455d26112cc8348452e1c48a0ad2cf91 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard-kpi','data' => ['icon' => '◷','label' => 'Ventas brutas mes','value' => '$'.number_format($ventasBrutasMes, 0, ',', '.'),'tone' => 'info']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dashboard-kpi'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => '◷','label' => 'Ventas brutas mes','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute('$'.number_format($ventasBrutasMes, 0, ',', '.')),'tone' => 'info']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal455d26112cc8348452e1c48a0ad2cf91)): ?>
<?php $attributes = $__attributesOriginal455d26112cc8348452e1c48a0ad2cf91; ?>
<?php unset($__attributesOriginal455d26112cc8348452e1c48a0ad2cf91); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal455d26112cc8348452e1c48a0ad2cf91)): ?>
<?php $component = $__componentOriginal455d26112cc8348452e1c48a0ad2cf91; ?>
<?php unset($__componentOriginal455d26112cc8348452e1c48a0ad2cf91); ?>
<?php endif; ?>
            <?php if (isset($component)) { $__componentOriginal455d26112cc8348452e1c48a0ad2cf91 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal455d26112cc8348452e1c48a0ad2cf91 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard-kpi','data' => ['icon' => '✓','label' => 'Ventas pagadas mes','value' => '$'.number_format($ingresosMes, 0, ',', '.'),'tone' => 'success']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dashboard-kpi'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => '✓','label' => 'Ventas pagadas mes','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute('$'.number_format($ingresosMes, 0, ',', '.')),'tone' => 'success']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal455d26112cc8348452e1c48a0ad2cf91)): ?>
<?php $attributes = $__attributesOriginal455d26112cc8348452e1c48a0ad2cf91; ?>
<?php unset($__attributesOriginal455d26112cc8348452e1c48a0ad2cf91); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal455d26112cc8348452e1c48a0ad2cf91)): ?>
<?php $component = $__componentOriginal455d26112cc8348452e1c48a0ad2cf91; ?>
<?php unset($__componentOriginal455d26112cc8348452e1c48a0ad2cf91); ?>
<?php endif; ?>
            <?php if (isset($component)) { $__componentOriginal455d26112cc8348452e1c48a0ad2cf91 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal455d26112cc8348452e1c48a0ad2cf91 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard-kpi','data' => ['icon' => '!','label' => 'Pendiente por cobrar','value' => '$'.number_format($pendienteCobro, 0, ',', '.'),'tone' => 'warning']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dashboard-kpi'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => '!','label' => 'Pendiente por cobrar','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute('$'.number_format($pendienteCobro, 0, ',', '.')),'tone' => 'warning']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal455d26112cc8348452e1c48a0ad2cf91)): ?>
<?php $attributes = $__attributesOriginal455d26112cc8348452e1c48a0ad2cf91; ?>
<?php unset($__attributesOriginal455d26112cc8348452e1c48a0ad2cf91); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal455d26112cc8348452e1c48a0ad2cf91)): ?>
<?php $component = $__componentOriginal455d26112cc8348452e1c48a0ad2cf91; ?>
<?php unset($__componentOriginal455d26112cc8348452e1c48a0ad2cf91); ?>
<?php endif; ?>
            <?php if (isset($component)) { $__componentOriginal455d26112cc8348452e1c48a0ad2cf91 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal455d26112cc8348452e1c48a0ad2cf91 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard-kpi','data' => ['icon' => '$','label' => 'Comisiones pendientes','value' => '$'.number_format($comisionesTotales, 0, ',', '.'),'tone' => 'danger']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dashboard-kpi'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => '$','label' => 'Comisiones pendientes','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute('$'.number_format($comisionesTotales, 0, ',', '.')),'tone' => 'danger']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal455d26112cc8348452e1c48a0ad2cf91)): ?>
<?php $attributes = $__attributesOriginal455d26112cc8348452e1c48a0ad2cf91; ?>
<?php unset($__attributesOriginal455d26112cc8348452e1c48a0ad2cf91); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal455d26112cc8348452e1c48a0ad2cf91)): ?>
<?php $component = $__componentOriginal455d26112cc8348452e1c48a0ad2cf91; ?>
<?php unset($__componentOriginal455d26112cc8348452e1c48a0ad2cf91); ?>
<?php endif; ?>
            <?php if (isset($component)) { $__componentOriginal455d26112cc8348452e1c48a0ad2cf91 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal455d26112cc8348452e1c48a0ad2cf91 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard-kpi','data' => ['icon' => '↗','label' => 'Pedidos activos','value' => $pedidosPendientes,'tone' => 'info']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dashboard-kpi'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => '↗','label' => 'Pedidos activos','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($pedidosPendientes),'tone' => 'info']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal455d26112cc8348452e1c48a0ad2cf91)): ?>
<?php $attributes = $__attributesOriginal455d26112cc8348452e1c48a0ad2cf91; ?>
<?php unset($__attributesOriginal455d26112cc8348452e1c48a0ad2cf91); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal455d26112cc8348452e1c48a0ad2cf91)): ?>
<?php $component = $__componentOriginal455d26112cc8348452e1c48a0ad2cf91; ?>
<?php unset($__componentOriginal455d26112cc8348452e1c48a0ad2cf91); ?>
<?php endif; ?>
        </section>
        <section class="dashboard-grid dashboard-grid-four"><div class="dashboard-panel dashboard-panel-span-two"><div class="dashboard-panel-heading"><div><span class="dashboard-kicker">Tendencia</span><h2>Ventas y domicilios · últimos 14 días</h2></div></div><div class="dashboard-chart-wrap"><canvas id="dashboard-trend-chart"></canvas></div></div><div class="dashboard-panel"><div class="dashboard-panel-heading"><div><span class="dashboard-kicker">Logística</span><h2>Domicilios</h2></div></div><div class="dashboard-balance-list"><div><span>Cobrado en domicilios</span><strong>$<?php echo e(number_format($totalCobradoDomiciliosMes, 0, ',', '.')); ?></strong></div><div><span>Generado para domiciliarios</span><strong>$<?php echo e(number_format($generadoDomiciliariosMes, 0, ',', '.')); ?></strong></div><div><span>Ganancia neta estimada</span><strong>$<?php echo e(number_format($gananciaNetaDomiciliosMes, 0, ',', '.')); ?></strong></div><div><span>Externos entregados</span><strong><?php echo e($domiciliosExternosMes); ?></strong></div></div></div><div class="dashboard-panel"><div class="dashboard-panel-heading"><div><span class="dashboard-kicker">Estado ahora</span><h2>Operación</h2></div></div><div class="dashboard-balance-list"><div><span>Pedidos activos</span><strong><?php echo e($pedidosPendientes); ?></strong></div><div><span>Domicilios pendientes</span><strong><?php echo e($domiciliosPendientes); ?></strong></div><div><span>En camino</span><strong><?php echo e($domiciliosEnCamino); ?></strong></div><div><span>Entregados hoy</span><strong><?php echo e($domiciliosEntregadosHoy); ?></strong></div><div><span>Productos agotados</span><strong><?php echo e($productosAgotados); ?></strong></div></div></div></section>
        <?php if($comisionesTotales > 0 || $domiciliosPendientes > 0 || $productosAgotados > 0): ?><section class="dashboard-alerts"><?php if($comisionesTotales > 0): ?><a href="<?php echo e(route('vendedores.index')); ?>">Comisiones pendientes <strong>$<?php echo e(number_format($comisionesTotales, 0, ',', '.')); ?></strong><span>Revisar →</span></a><?php endif; ?> <?php if($domiciliosPendientes > 0): ?><a href="<?php echo e(route('domicilios.index')); ?>">Domicilios sin asignar <strong><?php echo e($domiciliosPendientes); ?></strong><span>Ver →</span></a><?php endif; ?> <?php if($productosAgotados > 0): ?><a href="<?php echo e(route('productos.index', ['stock_bajo' => 1])); ?>">Productos agotados <strong><?php echo e($productosAgotados); ?></strong><span>Ver →</span></a><?php endif; ?></section><?php endif; ?>
        <section class="dashboard-grid dashboard-grid-two"><div class="dashboard-panel"><div class="dashboard-panel-heading"><div><span class="dashboard-kicker">Actividad reciente</span><h2>Últimas ventas</h2></div><a href="<?php echo e(route('ventas.index')); ?>" class="dashboard-text-link">Ver todas →</a></div><div class="dashboard-table-wrap"><table class="dashboard-table"><thead><tr><th>#</th><th>Cliente</th><th>Vendedor</th><th>Total</th><th>Estado</th></tr></thead><tbody><?php $__empty_1 = true; $__currentLoopData = $ventasRecientes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $venta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><tr><td><?php echo e($venta->id); ?></td><td><?php echo e($venta->cliente->nombre ?? '—'); ?></td><td><?php echo e($venta->vendedor->nombre ?? '—'); ?></td><td class="dashboard-money">$<?php echo e(number_format($venta->total, 0, ',', '.')); ?></td><td><span class="dashboard-status dashboard-status-<?php echo e($venta->estado); ?>"><?php echo e($venta->estado_label); ?></span></td></tr><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><tr><td colspan="5">Sin ventas aún.</td></tr><?php endif; ?></tbody></table></div></div><div class="dashboard-panel"><div class="dashboard-panel-heading"><div><span class="dashboard-kicker">Inventario</span><h2>Top productos</h2></div><a href="<?php echo e(route('productos.index')); ?>" class="dashboard-text-link">Catálogo →</a></div><div class="dashboard-ranking"><?php $__empty_1 = true; $__currentLoopData = $topProductos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $producto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><div><span><?php echo e(str_pad($i + 1, 2, '0', STR_PAD_LEFT)); ?></span><strong><?php echo e($producto->nombre_producto); ?></strong><small><?php echo e($producto->total_vendido); ?> uds · $<?php echo e(number_format($producto->total_ingresos, 0, ',', '.')); ?></small></div><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><p class="dashboard-muted">Sin datos todavía.</p><?php endif; ?></div></div></section>
    <?php else: ?>
        <?php $domiciliario = auth()->user()->domiciliario; ?>
        <header class="dashboard-hero"><div><span class="dashboard-kicker">Tu operación</span><h1>Hola, <?php echo e($vendedor->nombre); ?></h1><p>Vendedor<?php echo e($domiciliario ? ' · también domiciliario' : ''); ?> · <?php echo e(now()->format('d/m/Y H:i')); ?></p></div><div class="dashboard-hero-actions"><a href="<?php echo e(route('ventas.create')); ?>" class="dashboard-button dashboard-button-primary">+ Nueva venta</a><a href="<?php echo e(route('vendedores.show', $vendedor)); ?>" class="dashboard-button dashboard-button-ghost">Mis ganancias</a><?php if($domiciliario): ?><a href="<?php echo e(route('rutas.disponibles')); ?>" class="dashboard-button dashboard-button-ghost">Tomar domicilio</a><?php endif; ?></div></header>
        <section class="dashboard-kpis dashboard-kpis-five"><?php if (isset($component)) { $__componentOriginal455d26112cc8348452e1c48a0ad2cf91 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal455d26112cc8348452e1c48a0ad2cf91 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard-kpi','data' => ['icon' => '₿','label' => 'Ventas hoy','value' => $ventasHoy,'tone' => 'gold']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dashboard-kpi'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => '₿','label' => 'Ventas hoy','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($ventasHoy),'tone' => 'gold']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal455d26112cc8348452e1c48a0ad2cf91)): ?>
<?php $attributes = $__attributesOriginal455d26112cc8348452e1c48a0ad2cf91; ?>
<?php unset($__attributesOriginal455d26112cc8348452e1c48a0ad2cf91); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal455d26112cc8348452e1c48a0ad2cf91)): ?>
<?php $component = $__componentOriginal455d26112cc8348452e1c48a0ad2cf91; ?>
<?php unset($__componentOriginal455d26112cc8348452e1c48a0ad2cf91); ?>
<?php endif; ?> <?php if (isset($component)) { $__componentOriginal455d26112cc8348452e1c48a0ad2cf91 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal455d26112cc8348452e1c48a0ad2cf91 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard-kpi','data' => ['icon' => '◷','label' => 'Ventas este mes','value' => $ventasMes,'tone' => 'info']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dashboard-kpi'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => '◷','label' => 'Ventas este mes','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($ventasMes),'tone' => 'info']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal455d26112cc8348452e1c48a0ad2cf91)): ?>
<?php $attributes = $__attributesOriginal455d26112cc8348452e1c48a0ad2cf91; ?>
<?php unset($__attributesOriginal455d26112cc8348452e1c48a0ad2cf91); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal455d26112cc8348452e1c48a0ad2cf91)): ?>
<?php $component = $__componentOriginal455d26112cc8348452e1c48a0ad2cf91; ?>
<?php unset($__componentOriginal455d26112cc8348452e1c48a0ad2cf91); ?>
<?php endif; ?> <?php if (isset($component)) { $__componentOriginal455d26112cc8348452e1c48a0ad2cf91 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal455d26112cc8348452e1c48a0ad2cf91 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard-kpi','data' => ['icon' => '$','label' => 'Comisión generada','value' => '$'.number_format($comisionTotal, 0, ',', '.'),'tone' => 'success']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dashboard-kpi'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => '$','label' => 'Comisión generada','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute('$'.number_format($comisionTotal, 0, ',', '.')),'tone' => 'success']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal455d26112cc8348452e1c48a0ad2cf91)): ?>
<?php $attributes = $__attributesOriginal455d26112cc8348452e1c48a0ad2cf91; ?>
<?php unset($__attributesOriginal455d26112cc8348452e1c48a0ad2cf91); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal455d26112cc8348452e1c48a0ad2cf91)): ?>
<?php $component = $__componentOriginal455d26112cc8348452e1c48a0ad2cf91; ?>
<?php unset($__componentOriginal455d26112cc8348452e1c48a0ad2cf91); ?>
<?php endif; ?> <?php if (isset($component)) { $__componentOriginal455d26112cc8348452e1c48a0ad2cf91 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal455d26112cc8348452e1c48a0ad2cf91 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard-kpi','data' => ['icon' => '!','label' => 'Comisión pendiente','value' => '$'.number_format($comisionPendiente, 0, ',', '.'),'tone' => 'warning']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dashboard-kpi'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => '!','label' => 'Comisión pendiente','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute('$'.number_format($comisionPendiente, 0, ',', '.')),'tone' => 'warning']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal455d26112cc8348452e1c48a0ad2cf91)): ?>
<?php $attributes = $__attributesOriginal455d26112cc8348452e1c48a0ad2cf91; ?>
<?php unset($__attributesOriginal455d26112cc8348452e1c48a0ad2cf91); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal455d26112cc8348452e1c48a0ad2cf91)): ?>
<?php $component = $__componentOriginal455d26112cc8348452e1c48a0ad2cf91; ?>
<?php unset($__componentOriginal455d26112cc8348452e1c48a0ad2cf91); ?>
<?php endif; ?> <?php if($domiciliario): ?> <?php if (isset($component)) { $__componentOriginal455d26112cc8348452e1c48a0ad2cf91 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal455d26112cc8348452e1c48a0ad2cf91 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dashboard-kpi','data' => ['icon' => '🛵','label' => 'Ganancia domicilios mes','value' => '$'.number_format($gananciaDomiciliosMes, 0, ',', '.'),'tone' => 'gold']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dashboard-kpi'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => '🛵','label' => 'Ganancia domicilios mes','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute('$'.number_format($gananciaDomiciliosMes, 0, ',', '.')),'tone' => 'gold']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal455d26112cc8348452e1c48a0ad2cf91)): ?>
<?php $attributes = $__attributesOriginal455d26112cc8348452e1c48a0ad2cf91; ?>
<?php unset($__attributesOriginal455d26112cc8348452e1c48a0ad2cf91); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal455d26112cc8348452e1c48a0ad2cf91)): ?>
<?php $component = $__componentOriginal455d26112cc8348452e1c48a0ad2cf91; ?>
<?php unset($__componentOriginal455d26112cc8348452e1c48a0ad2cf91); ?>
<?php endif; ?> <?php endif; ?></section>
        <section class="dashboard-grid dashboard-grid-two"><div class="dashboard-panel"><div class="dashboard-panel-heading"><div><span class="dashboard-kicker">Rendimiento</span><h2>Actividad de los últimos 14 días</h2></div></div><div class="dashboard-chart-wrap"><canvas id="dashboard-trend-chart"></canvas></div></div><div class="dashboard-panel"><div class="dashboard-panel-heading"><div><span class="dashboard-kicker">Balance</span><h2>Ganancia combinada</h2></div></div><div class="dashboard-balance-list"><div><span>Comisiones de ventas</span><strong>$<?php echo e(number_format($comisionTotal, 0, ',', '.')); ?></strong></div><div><span>Comisión ya pagada</span><strong>$<?php echo e(number_format($comisionCobrada, 0, ',', '.')); ?></strong></div><div><span>Domicilios este mes</span><strong>$<?php echo e(number_format($gananciaDomiciliosMes ?? 0, 0, ',', '.')); ?></strong></div><div><span>Total histórico domicilios</span><strong>$<?php echo e(number_format($gananciaDomiciliosTotal ?? 0, 0, ',', '.')); ?></strong></div></div></div></section>
        <section class="dashboard-grid dashboard-grid-two"><div class="dashboard-panel"><div class="dashboard-panel-heading"><div><span class="dashboard-kicker">Actividad reciente</span><h2>Mis ventas</h2></div><a href="<?php echo e(route('ventas.index')); ?>" class="dashboard-text-link">Ver todas →</a></div><div class="dashboard-table-wrap"><table class="dashboard-table"><thead><tr><th>#</th><th>Cliente</th><th>Total</th><th>Estado</th></tr></thead><tbody><?php $__empty_1 = true; $__currentLoopData = $ventasRecientes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $venta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><tr><td><?php echo e($venta->id); ?></td><td><?php echo e($venta->cliente->nombre ?? '—'); ?></td><td class="dashboard-money">$<?php echo e(number_format($venta->total, 0, ',', '.')); ?></td><td><span class="dashboard-status dashboard-status-<?php echo e($venta->estado); ?>"><?php echo e($venta->estado_label); ?></span></td></tr><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><tr><td colspan="4">Sin ventas aún.</td></tr><?php endif; ?></tbody></table></div></div><div class="dashboard-panel"><div class="dashboard-panel-heading"><div><span class="dashboard-kicker">Productos</span><h2>Más vendidos</h2></div></div><div class="dashboard-ranking"><?php $__empty_1 = true; $__currentLoopData = $topProductos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $producto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><div><span><?php echo e(str_pad($i + 1, 2, '0', STR_PAD_LEFT)); ?></span><strong><?php echo e($producto->nombre_producto); ?></strong><small><?php echo e($producto->total_vendido); ?> uds · $<?php echo e(number_format($producto->total_ingresos, 0, ',', '.')); ?></small></div><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><p class="dashboard-muted">Sin datos todavía.</p><?php endif; ?></div></div></section>
    <?php endif; ?>
</div>
<?php if(isset($chartLabels)): ?>
<script>
window.dashboardChartData = <?php echo json_encode(['labels' => $chartLabels, 'ventas' => $chartVentas, 'domicilios' => $chartDomicilios]) ?>;
document.addEventListener('DOMContentLoaded', function () {
    const canvas = document.getElementById('dashboard-trend-chart');
    if (!canvas || !window.Chart || !window.dashboardChartData) return;
    const styles = getComputedStyle(document.documentElement);
    const gold = styles.getPropertyValue('--pg-gold').trim();
    const info = styles.getPropertyValue('--pg-info').trim();
    const muted = styles.getPropertyValue('--pg-muted').trim();
    const border = styles.getPropertyValue('--pg-border').trim();
    new Chart(canvas, { type: 'line', data: { labels: window.dashboardChartData.labels, datasets: [
        { label: 'Ventas', data: window.dashboardChartData.ventas, borderColor: gold, backgroundColor: 'rgba(205,168,106,.12)', fill: true, tension: .35, pointRadius: 3 },
        { label: 'Domicilios', data: window.dashboardChartData.domicilios, borderColor: info, backgroundColor: 'transparent', tension: .35, pointRadius: 3 }
    ]}, options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { labels: { color: muted, usePointStyle: true } } }, scales: { x: { grid: { color: border }, ticks: { color: muted } }, y: { grid: { color: border }, ticks: { color: muted, callback: value => '$' + Number(value).toLocaleString('es-CO') } } } } });
});
</script>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Usuario\Documents\My Web Sites\POS\Backend\POSRed\resources\views/dashboard.blade.php ENDPATH**/ ?>