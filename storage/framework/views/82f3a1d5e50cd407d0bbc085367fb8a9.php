<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['icon', 'label', 'value', 'tone' => 'gold']));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['icon', 'label', 'value', 'tone' => 'gold']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<div class="dashboard-kpi" data-tone="<?php echo e($tone); ?>">
    <div class="dashboard-kpi-icon"><?php echo e($icon); ?></div>
    <div class="dashboard-kpi-content">
        <span class="dashboard-kpi-value"><?php echo e($value); ?></span>
        <span class="dashboard-kpi-label"><?php echo e($label); ?></span>
    </div>
</div>
<?php /**PATH C:\Users\Usuario\Documents\My Web Sites\POS\Backend\POSRed\resources\views\components\dashboard-kpi.blade.php ENDPATH**/ ?>