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
     * Campos que se pueden asignar masivamente.
     */
    protected $fillable = [
        'cliente_id',
        'subtotal',
        'descuento_manual',
        'motivo_descuento',
        'descuento_id',
        'envio',        // solo boolean
        'estado',
        'total',
    ];


    /**
     * Casting de tipos de datos.
     */
    protected $casts = [
        'envio' => 'boolean',
        'subtotal' => 'float',
        'descuento_manual' => 'float',
        'costo_envio' => 'float',
        'total' => 'float',
    ];

    /**
     * Relación: una venta pertenece a un cliente.
     */
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    /**
     * Relación: una venta puede tener un descuento asociado.
     */
    public function descuento()
    {
        return $this->belongsTo(Descuento::class);
    }

    /**
     * Relación: una venta tiene muchos detalles.
     */
    public function detalles()
    {
        return $this->hasMany(DetalleVenta::class);
    }

    /**
     * Calcula el total final de la venta (subtotal - descuento + envío).
     */
    public function calcularTotal(): float
    {
        $subtotal = $this->subtotal ?? 0;
        $descuento = $this->descuento_manual ?? 0;

        $costoEnvio = $this->domicilio?->costo_envio ?? 0;

        return max($subtotal - $descuento + $costoEnvio, 0);
    }


    /**
     * Evento de modelo: recalcula el total automáticamente al guardar.
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($venta) {
            $venta->total = $venta->calcularTotal();
        });
    }

    /**
     * Accesor para mostrar el estado con la primera letra mayúscula.
     */
    public function getEstadoLabelAttribute(): string
    {
        return ucfirst($this->estado);
    }

        public function domicilio()
    {
        return $this->hasOne(Domicilio::class);
    }

}

