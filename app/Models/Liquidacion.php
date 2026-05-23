<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Liquidacion extends Model
{
    protected $table = 'liquidaciones';

    protected $fillable = [
        'vendedor_id',
        'monto_total',
        'metodo_pago',
        'referencia',
        'notas',
        'fecha_pago',
    ];

    protected $casts = [
        'monto_total' => 'float',
        'fecha_pago'  => 'date',
    ];

    public function vendedor()
    {
        return $this->belongsTo(Vendedor::class);
    }

    public function comisiones()
    {
        return $this->hasMany(Comision::class);
    }

    public static function metodosLabel(): array
    {
        return [
            'efectivo'       => '💵 Efectivo',
            'transferencia'  => '🏦 Transferencia',
            'cripto'         => '₿ Cripto',
            'tarjeta'        => '💳 Tarjeta',
            'otro'           => '📦 Otro',
        ];
    }
}
