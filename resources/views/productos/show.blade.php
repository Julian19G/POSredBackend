@extends('layouts.app')

@section('content')
<div class="product-detail-page">
    <div class="product-detail-shell">
        <div class="product-detail-header">
            <div>
                <span class="product-detail-eyebrow">Catálogo</span>
                <h1 class="product-detail-title">{{ $producto->nombre }}</h1>
                <p class="product-detail-subtitle">Información completa y presentaciones registradas.</p>
            </div>
            <a href="{{ route('productos.index') }}" class="product-secondary product-detail-back">
                <svg aria-hidden="true" viewBox="0 0 24 24" fill="none"><path d="m15 18-6-6 6-6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                Volver al catálogo
            </a>
        </div>

        <div class="product-detail-card">
            <div class="product-detail-overview">
                <div class="product-detail-image-wrap">
                    @if($producto->imagen)
                        <img src="{{ asset('storage/' . $producto->imagen) }}" alt="{{ $producto->nombre }}" class="product-detail-image">
                    @else
                        <svg aria-hidden="true" class="product-detail-image-placeholder" viewBox="0 0 24 24" fill="none"><rect x="3" y="4" width="18" height="16" rx="2" stroke="currentColor" stroke-width="1.4"/><circle cx="8" cy="9" r="1.5" stroke="currentColor" stroke-width="1.4"/><path d="m4 17 5-5 3 3 2-2 6 5" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/></svg>
                        <span>Sin imagen</span>
                    @endif
                </div>

                <div class="product-detail-summary">
                    <div class="product-detail-title-row">
                        <div>
                            <span class="product-detail-label">Producto</span>
                            <h2>{{ $producto->nombre }}</h2>
                        </div>
                        <span class="product-detail-status {{ $producto->activo ? 'product-detail-status-active' : 'product-detail-status-inactive' }}">{{ $producto->activo ? 'Activo' : 'Inactivo' }}</span>
                    </div>
                    <div class="product-detail-description">
                        <span class="product-detail-label">Descripción</span>
                        <p>{{ $producto->descripcion ?: 'Sin descripción disponible.' }}</p>
                    </div>
                    <div class="product-detail-meta">
                        <div><span class="product-detail-label">Stock total</span><strong>{{ $producto->stock }}</strong></div>
                        <div><span class="product-detail-label">Categoría</span><strong>{{ $producto->categoria?->nombre ?? 'Sin categoría' }}</strong></div>
                    </div>
                </div>
            </div>

            <section class="product-detail-section">
                <div class="product-detail-section-heading">
                    <span class="product-detail-eyebrow">Características</span>
                    <h2>Atributos del producto</h2>
                </div>
                <div class="product-detail-attributes">
                    <div class="product-detail-attribute">
                        <span class="product-detail-label">Sabores</span>
                        <div class="product-detail-chips">
                            @forelse($producto->sabores as $sabor)<span class="product-detail-chip">{{ $sabor->nombre }}</span>@empty<span class="product-detail-empty">Sin sabores registrados</span>@endforelse
                        </div>
                    </div>
                    <div class="product-detail-attribute">
                        <span class="product-detail-label">Efectos</span>
                        <div class="product-detail-chips">
                            @forelse($producto->efectos as $efecto)<span class="product-detail-chip">{{ $efecto->nombre }}</span>@empty<span class="product-detail-empty">Sin efectos registrados</span>@endforelse
                        </div>
                    </div>
                    <div class="product-detail-attribute">
                        <span class="product-detail-label">Colores</span>
                        <div class="product-detail-chips">
                            @forelse($producto->colores as $color)<span class="product-detail-chip">{{ $color->nombre }}</span>@empty<span class="product-detail-empty">Sin colores registrados</span>@endforelse
                        </div>
                    </div>
                </div>
            </section>

            <section class="product-detail-section">
                <div class="product-detail-section-heading">
                    <span class="product-detail-eyebrow">Presentaciones</span>
                    <h2>Variantes y precios</h2>
                </div>
                @if($producto->variantes->count())
                    <div class="product-detail-variants">
                        @foreach($producto->variantes as $v)
                            <div class="product-detail-variant">
                                <div><strong>{{ $v->nombre }}</strong><span>{{ $v->cantidad_por_variante }} unidades</span></div>
                                <strong class="product-detail-price">${{ number_format($v->precio, 0, ',', '.') }}</strong>
                                <span class="product-detail-stock">Stock {{ $v->stock }}</span>
                                <span class="product-detail-status {{ $v->activo ? 'product-detail-status-active' : 'product-detail-status-inactive' }}">{{ $v->activo ? 'Activo' : 'Inactivo' }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="product-detail-empty product-detail-empty-block">Este producto no tiene variantes registradas.</p>
                @endif
            </section>
        </div>

        <div class="product-detail-actions">
            <a href="{{ route('productos.edit', $producto) }}" class="product-primary"><svg aria-hidden="true" viewBox="0 0 24 24" fill="none"><path d="m4 16.5-.8 3.3 3.3-.8L18.8 6.7a2.1 2.1 0 0 0-3-3L4 16.5Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/></svg>Editar producto</a>
            <a href="{{ route('productos.index') }}" class="product-secondary">Volver al catálogo</a>
        </div>
    </div>
</div>
@endsection
