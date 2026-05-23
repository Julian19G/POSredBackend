<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recibo #{{ $venta->id }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: monospace; font-size: 12px; width: 300px; margin: 0 auto; padding: 10px; }
        .center { text-align: center; }
        .bold { font-weight: bold; }
        .line { border-top: 1px dashed #000; margin: 6px 0; }
        .row { display: flex; justify-content: space-between; margin: 2px 0; }
        .total-row { font-size: 14px; font-weight: bold; }
        .descuento { color: #555; }
        @media print {
            body { width: 100%; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>

<div class="center bold" style="font-size:16px">POS</div>
<div class="center">Sistema de Ventas</div>
<div class="center">{{ now()->format('d/m/Y H:i') }}</div>

<div class="line"></div>

<div class="row"><span>Recibo #</span><span class="bold">{{ $venta->id }}</span></div>
<div class="row"><span>Cliente</span><span>{{ $venta->cliente->nombre ?? '—' }}</span></div>
@if($venta->cliente?->telefono)
<div class="row"><span>Tel.</span><span>{{ $venta->cliente->telefono }}</span></div>
@endif
@if($venta->pedido?->metodo_pago)
<div class="row"><span>Pago</span><span>{{ Str::ucfirst($venta->pedido->metodo_pago) }}</span></div>
@endif

<div class="line"></div>

@foreach($venta->detalles as $d)
<div>
    <div class="bold">{{ $d->nombre_producto }}</div>
    @if($d->nombre_variante)<div style="padding-left:8px;color:#555">{{ $d->nombre_variante }}</div>@endif
    <div class="row">
        <span style="padding-left:8px">{{ $d->cantidad }} x ${{ number_format($d->precio_unitario, 0, ',', '.') }}</span>
        <span>${{ number_format($d->subtotal, 0, ',', '.') }}</span>
    </div>
    @if($d->descuento_aplicado > 0)
    <div class="row descuento">
        <span style="padding-left:8px">Desc. aplicado</span>
        <span>-${{ number_format($d->descuento_aplicado, 0, ',', '.') }}</span>
    </div>
    @endif
</div>
@endforeach

<div class="line"></div>

<div class="row"><span>Subtotal</span><span>${{ number_format($venta->subtotal, 0, ',', '.') }}</span></div>
@if($venta->descuento_manual > 0)
<div class="row descuento">
    <span>Descuento @if($venta->motivo_descuento)({{ $venta->motivo_descuento }})@endif</span>
    <span>-${{ number_format($venta->descuento_manual, 0, ',', '.') }}</span>
</div>
@endif
@if($venta->costo_envio > 0)
<div class="row"><span>Envío</span><span>${{ number_format($venta->costo_envio, 0, ',', '.') }}</span></div>
@endif

<div class="line"></div>

<div class="row total-row"><span>TOTAL</span><span>${{ number_format($venta->total, 0, ',', '.') }}</span></div>

<div class="line"></div>

<div class="center" style="margin-top:8px">
    Estado: <strong>{{ Str::upper($venta->estado) }}</strong>
</div>
@if($venta->envio && $venta->direccion_envio)
<div class="center" style="margin-top:4px;font-size:11px">Envío a: {{ $venta->direccion_envio }}</div>
@endif

<div class="center" style="margin-top:12px;font-size:11px">¡Gracias por tu compra!</div>

<div class="line"></div>

<div class="center no-print" style="margin-top:12px">
    <button onclick="window.print()" style="padding:8px 24px;font-size:14px;cursor:pointer">🖨 Imprimir</button>
    <button onclick="window.close()" style="padding:8px 16px;margin-left:8px;cursor:pointer">Cerrar</button>
</div>

<script>
    // Auto-print al abrir
    window.onload = function() {
        // pequeño delay para que el CSS cargue
        setTimeout(() => window.print(), 300);
    };
</script>
</body>
</html>
