<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Venta;
use App\Models\Producto;

class DetalleVenta extends Model
{
    use HasFactory;

    protected $table = 'detalle_ventas';

    protected $fillable = [
        'venta_id',
        'producto_id',
        'nombre_producto',
        'codigo_producto',
        'cantidad',
        'precio_unitario',
        'descuento_aplicado',
        'impuesto',
        'subtotal',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relaciones
    |--------------------------------------------------------------------------
    */

    /**
     * Relación inversa con la venta.
     */
    public function venta()
    {
        return $this->belongsTo(Venta::class, 'venta_id');
    }

    /**
     * Relación con producto (nullable si el producto fue eliminado).
     */
    public function producto()
    {
        // Se incluye withTrashed() solo si el modelo Producto usa SoftDeletes
        return $this->belongsTo(Producto::class, 'producto_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors y Mutators
    |--------------------------------------------------------------------------
    */

    /**
     * Calcula el subtotal dinámicamente si no está guardado en la BD.
     */
    public function getSubtotalAttribute($value)
    {
        if (!is_null($value)) {
            return $value;
        }

        $subtotal = ($this->cantidad * $this->precio_unitario)
            - $this->descuento_aplicado
            + $this->impuesto;

        return round($subtotal, 2);
    }

    /**
     * Devuelve el precio unitario con formato de moneda.
     */
    public function getPrecioUnitarioFormattedAttribute(): string
    {
        return '$' . number_format($this->precio_unitario, 2, ',', '.');
    }

    /**
     * Devuelve el subtotal con formato de moneda.
     */
    public function getSubtotalFormattedAttribute(): string
    {
        return '$' . number_format($this->subtotal, 2, ',', '.');
    }

    /*
    |--------------------------------------------------------------------------
    | Métodos personalizados
    |--------------------------------------------------------------------------
    */

    /**
     * Recalcula el subtotal según los campos actuales.
     */
    public function recalcularSubtotal(): void
    {
        $this->subtotal = ($this->cantidad * $this->precio_unitario)
            - $this->descuento_aplicado
            + $this->impuesto;

        $this->save();
    }

    /**
     * Genera una breve descripción del detalle de venta.
     */
    public function resumen(): string
    {
        return "{$this->nombre_producto} ({$this->cantidad} × {$this->getPrecioUnitarioFormattedAttribute()}) = {$this->getSubtotalFormattedAttribute()}";
    }
}

