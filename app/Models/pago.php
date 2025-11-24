<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pago extends Model
{
    use HasFactory;

    protected $fillable = [
        'venta_id',
        'monto',
        'metodo',       // efectivo, tarjeta, transferencia, PayPal
        'estado',       // pendiente, aprobado, rechazado
        'fecha_pago',
        'referencia',   // número de transacción o comprobante
        'comentarios',
    ];

    protected $casts = [
        'monto' => 'float',
        'fecha_pago' => 'datetime',
    ];

    /**
     * Relación con venta
     */
    public function venta()
    {
        return $this->belongsTo(\App\Models\Venta::class);
    }

    /**
     * Scope para pagos aprobados
     */
    public function scopeAprobados($query)
    {
        return $query->where('estado', 'aprobado');
    }

    /**
     * Scope para pagos pendientes
     */
    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }

    /**
     * Verifica si el pago está aprobado
     */
    public function esAprobado()
    {
        return $this->estado === 'aprobado';
    }
}
