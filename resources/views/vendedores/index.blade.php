@extends('layouts.app')
@section('content')
<div class="catalog-page vendedor-page">
<div class="catalog-shell">

    @if(session('success'))
    <div class="vendedor-toast" role="status">
        <span class="vendedor-toast-icon" aria-hidden="true">✓</span>
        <span>{{ session('success') }}</span>
        <button type="button" class="vendedor-toast-close" aria-label="Cerrar mensaje"
                onclick="this.parentElement.remove()">×</button>
    </div>
    @endif

    <div class="catalog-header">
        <div>
            <h1 class="catalog-title">👥 Vendedores</h1>
            <p class="catalog-subtitle">Gestiona tu equipo de ventas y comisiones.</p>
        </div>
        <a href="{{ route('vendedores.create') }}" class="catalog-button vendedor-cta">➕ Nuevo vendedor</a>
    </div>

    <div class="catalog-table-wrap vendedor-table-wrap">
        <table class="catalog-table vendedor-table">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Contacto</th>
                    <th>Instagram</th>
                    <th class="vendedor-align-right">Comisión</th>
                    <th class="vendedor-align-center">Ventas</th>
                    <th class="vendedor-align-right">Total vendido</th>
                    <th>Estado</th>
                    <th class="vendedor-align-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
            @forelse($vendedores as $v)
            <tr class="vendedor-row">
                <td class="vendedor-name" data-label="Nombre">{{ $v->nombre }}</td>
                <td class="vendedor-contact" data-label="Contacto">
                    @if($v->telefono)
                        <span><svg aria-hidden="true" viewBox="0 0 24 24" fill="none"><path d="M6.6 3.5h2.7l1.4 4-1.8 1.8a15.4 15.4 0 0 0 5.8 5.8l1.8-1.8 4 1.4v2.7c0 1-.8 1.7-1.7 1.7C10.8 19.1 4.9 13.2 4.9 6.2c0-.9.7-1.7 1.7-1.7Z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/></svg>{{ $v->telefono }}</span>
                    @endif
                    @if($v->whatsapp)
                        <span><svg aria-hidden="true" viewBox="0 0 24 24" fill="none"><path d="M19.5 4.5A9.1 9.1 0 0 0 5.2 15.7L4 20l4.4-1.2A9.1 9.1 0 1 0 19.5 4.5Z" stroke="currentColor" stroke-width="1.6"/><path d="M8.2 7.8c.3-.3.7-.3 1 0l1 1.4c.2.3.2.6 0 .9l-.5.6c.7 1.2 1.6 2.1 2.8 2.8l.6-.5c.3-.2.6-.2.9 0l1.4 1c.3.3.3.7 0 1-.5.6-1.2.8-1.9.6-3.6-.9-6.3-3.6-7.2-7.2-.2-.7 0-1.4.6-1.9Z" stroke="currentColor" stroke-width="1.3" stroke-linejoin="round"/></svg>{{ $v->whatsapp }}</span>
                    @endif
                    @if($v->email)
                        <span><svg aria-hidden="true" viewBox="0 0 24 24" fill="none"><rect x="3.5" y="5.5" width="17" height="13" rx="2" stroke="currentColor" stroke-width="1.6"/><path d="m5 7 7 5 7-5" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/></svg>{{ $v->email }}</span>
                    @endif
                    @if(!$v->telefono && !$v->whatsapp && !$v->email)<span>—</span>@endif
                </td>
                <td data-label="Instagram">
                    @if($v->instagram)<span class="vendedor-instagram">@{{ $v->instagram }}</span>@else<span class="vendedor-muted">—</span>@endif
                </td>
                <td class="vendedor-align-right vendedor-commission" data-label="Comisión">{{ $v->comision_porcentaje }}%</td>
                <td class="vendedor-align-center" data-label="Ventas"><span class="vendedor-sales-count">{{ $v->ventas_count }}</span></td>
                <td class="vendedor-align-right vendedor-total" data-label="Total vendido">${{ number_format($v->ventas_sum_total ?? 0, 0, ',', '.') }}</td>
                <td data-label="Estado">
                    <span class="vendedor-status {{ $v->activo ? 'vendedor-status-active' : 'vendedor-status-inactive' }}">
                        {{ $v->activo ? 'Activo' : 'Inactivo' }}
                    </span>
                </td>
                <td class="vendedor-align-center vendedor-actions-cell" data-label="Acciones">
                    <div class="catalog-actions vendedor-actions">
                        <a href="{{ route('vendedores.show', $v) }}" class="catalog-action catalog-action-info" title="Ver vendedor" aria-label="Ver vendedor">
                            <svg aria-hidden="true" viewBox="0 0 24 24" fill="none"><path d="M2.5 12s3.5-6 9.5-6 9.5 6 9.5 6-3.5 6-9.5 6-9.5-6-9.5-6Z" stroke="currentColor" stroke-width="1.8"/><circle cx="12" cy="12" r="2.5" stroke="currentColor" stroke-width="1.8"/></svg>
                        </a>
                        <a href="{{ route('vendedores.edit', $v) }}" class="catalog-action catalog-action-edit" title="Editar vendedor" aria-label="Editar vendedor">
                            <svg aria-hidden="true" viewBox="0 0 24 24" fill="none"><path d="m4 16.5-.8 3.3 3.3-.8L18.8 6.7a2.1 2.1 0 0 0-3-3L4 16.5Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/></svg>
                        </a>
                        <button type="button" class="catalog-action catalog-action-info vendedor-copy-button"
                                data-enlace="{{ $v->enlace_referido }}" title="Copiar link de referido" aria-label="Copiar link de referido">
                            <svg aria-hidden="true" viewBox="0 0 24 24" fill="none"><path d="M9.5 14.5 14.5 9.5M7.5 17.5l-1 1a3.5 3.5 0 0 1-5-5l4-4a3.5 3.5 0 0 1 5 0M16.5 6.5l1-1a3.5 3.5 0 0 1 5 5l-4 4a3.5 3.5 0 0 1-5 0" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/></svg>
                        </button>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="vendedor-empty"><span aria-hidden="true">👥</span>No hay vendedores registrados.</td>
            </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div></div>

<script>
    document.querySelectorAll('.vendedor-copy-button').forEach(btn => {
        btn.addEventListener('click', function () {
            navigator.clipboard.writeText(this.dataset.enlace).then(() => {
                const original = this.innerHTML;
                this.innerHTML = '<span class="vendedor-copy-check">✓</span>';
                this.classList.add('vendedor-copy-success');
                setTimeout(() => {
                    this.innerHTML = original;
                    this.classList.remove('vendedor-copy-success');
                }, 1500);
            });
        });
    });
</script>
@endsection
