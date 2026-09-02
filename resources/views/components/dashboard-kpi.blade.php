@props(['icon', 'label', 'value', 'tone' => 'gold'])

<div class="dashboard-kpi" data-tone="{{ $tone }}">
    <div class="dashboard-kpi-icon">{{ $icon }}</div>
    <div class="dashboard-kpi-content">
        <span class="dashboard-kpi-value">{{ $value }}</span>
        <span class="dashboard-kpi-label">{{ $label }}</span>
    </div>
</div>
