<?php

namespace App\Notifications;

use App\Models\Pedido;
use Illuminate\Notifications\Notification;

class NuevaVentaNotification extends Notification
{
    public function __construct(public Pedido $pedido) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        $venta   = $this->pedido->venta;
        $cliente = $venta?->cliente?->nombre ?? 'Cliente';
        $total   = $venta?->total ?? 0;

        return [
            'tipo'      => 'nueva_venta',
            'pedido_id' => $this->pedido->id,
            'venta_id'  => $venta?->id,
            'total'     => (float) $total,
            'cliente'   => $cliente,
            'mensaje'   => "Nueva venta #{$this->pedido->id} de {$cliente} por $" . number_format($total, 0, ',', '.'),
            'url'       => route('pedidos.index'),
        ];
    }
}
