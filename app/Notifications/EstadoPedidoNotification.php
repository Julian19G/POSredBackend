<?php

namespace App\Notifications;

use App\Models\Pedido;
use Illuminate\Notifications\Notification;

class EstadoPedidoNotification extends Notification
{
    public function __construct(public Pedido $pedido) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        $label = Pedido::estadosLabel()[$this->pedido->estado]['label'] ?? $this->pedido->estado;

        return [
            'tipo'              => 'estado_pedido',
            'pedido_id'         => $this->pedido->id,
            'seguimiento_token' => $this->pedido->public_token,
            'estado'            => $this->pedido->estado,
            'label'             => $label,
            'mensaje'           => "Tu pedido #{$this->pedido->id} ahora está: {$label}",
        ];
    }
}
