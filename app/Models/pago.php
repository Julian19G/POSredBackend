<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pago extends Model
{
    use HasFactory;

    protected $fillable = [
        'venta_id',
        'comprobante_id',
        'registrado_por',
        'monto',
        'metodo',
        'estado',
        'fecha_pago',
        'referencia',
        'comentarios',
    ];

    protected $casts = [
        'monto'      => 'float',
        'fecha_pago' => 'datetime',
    ];

    public function venta()
    {
        return $this->belongsTo(Venta::class);
    }

    public function comprobante()
    {
        return $this->belongsTo(Comprobante::class);
    }

    public function registradoPor()
    {
        return $this->belongsTo(User::class, 'registrado_por');
    }

    public function scopeConfirmados($query)
    {
        return $query->where('estado', 'confirmado');
    }

    public static function metodosLabel(): array
    {
        return [
            'efectivo'      => '💵 Efectivo',
            'transferencia' => '🏦 Transferencia',
            'cripto'        => '🪙 Cripto',
            'tarjeta'       => '💳 Tarjeta',
            'otro'          => '🔄 Otro',
        ];
    }
}
