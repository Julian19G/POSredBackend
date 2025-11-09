<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\DetalleVenta;
use App\Models\Cliente;
use App\Models\Descuento;

class Venta extends Model
{
    use HasFactory;

    /**
     * Campos que se pueden asignar masivamente
     */
    protected $fillable = [
        'cliente_id',
        'subtotal',
        'descuento_manual',
        'motivo_descuento',
        'descuento_id',
        'costo_envio',
        'direccion_envio',
        'estado',
        'total',
    ];

    /**
     * Relación: una venta pertenece a un cliente
     */
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    /**
     * Relación: una venta puede tener un descuento asociado
     */
    public function descuento()
    {
        return $this->belongsTo(Descuento::class);
    }

    /**
     * Relación: una venta tiene muchos detalles
     */
    public function detalles()
    {
        return $this->hasMany(DetalleVenta::class);
    }

    /**
     * Calcula el total final de la venta (subtotal - descuento + envío)
     */
    public function calcularTotal()
    {
        $subtotal = $this->subtotal;
        $descuento = $this->descuento_manual ?? 0;
        $envio = $this->costo_envio ?? 0;

        return max($subtotal - $descuento + $envio, 0);
    }

    /**
     * Mutador automático al guardar: recalcula el total
     */
    public static function boot()
    {
        parent::boot();

        static::saving(function ($venta) {
            $venta->total = $venta->calcularTotal();
        });
    }

    /**
     * Formato más legible del estado (por si lo usas en vistas)
     */
    public function getEstadoLabelAttribute()
    {
        return ucfirst($this->estado);
    }
}
