<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comprobante extends Model
{
    use HasFactory;

    protected $table = 'comprobantes';

    protected $fillable = [
        'pedido_id',
        'tipo',
        'monto',
        'referencia',
        'imagen_path',
        'estado',
        'notas',
        'verificado_en',
    ];

    protected $casts = [
        'monto'          => 'float',
        'verificado_en'  => 'datetime',
    ];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }

    public static function tiposLabel(): array
    {
        return [
            'efectivo'      => '💵 Efectivo',
            'transferencia' => '🏦 Transferencia',
            'cripto'        => '🪙 Cripto',
            'tarjeta'       => '💳 Tarjeta',
            'otro'          => '🔄 Otro',
        ];
    }

    public function getBadgeEstadoAttribute(): string
    {
        return match($this->estado) {
            'verificado' => 'success',
            'rechazado'  => 'danger',
            default      => 'warning',
        };
    }
}
