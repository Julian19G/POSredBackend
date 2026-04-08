<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $fillable = [
        'venta_id',
        'estado',
        'metodo_pago',
        'estado_pago',
        'fecha_preparacion',
        'fecha_despacho',
        'fecha_entrega',
        'fecha_cancelacion',
        'notas',
    ];

    protected $casts = [
        'fecha_preparacion' => 'datetime',
        'fecha_despacho'    => 'datetime',
        'fecha_entrega'     => 'datetime',
        'fecha_cancelacion' => 'datetime',
    ];

    public function venta()
    {
        return $this->belongsTo(Venta::class);
    }

    // Etiquetas para la UI
    public static function estadosLabel(): array
    {
        return [
            'nuevo'          => ['label' => 'Nuevo',          'badge' => 'secondary'],
            'en_preparacion' => ['label' => 'En preparación', 'badge' => 'warning'],
            'despachado'     => ['label' => 'Despachado',     'badge' => 'info'],
            'entregado'      => ['label' => 'Entregado',      'badge' => 'success'],
            'cancelado'      => ['label' => 'Cancelado',      'badge' => 'danger'],
        ];
    }

    public static function metodosLabel(): array
    {
        return [
            'efectivo'       => '💵 Efectivo',
            'transferencia'  => '🏦 Transferencia',
            'cripto'         => '🪙 Cripto',
            'tarjeta'        => '💳 Tarjeta',
            'otro'           => '🔄 Otro',
        ];
    }

    public function getBadgeEstadoAttribute(): string
    {
        return self::estadosLabel()[$this->estado]['badge'] ?? 'secondary';
    }

    public function getLabelEstadoAttribute(): string
    {
        return self::estadosLabel()[$this->estado]['label'] ?? $this->estado;
    }
}