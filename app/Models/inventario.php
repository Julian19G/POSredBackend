<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Inventario extends Model
{
    use HasFactory;

    protected $fillable = [
        'producto_id',
        'tipo', // entrada o salida
        'cantidad',
        'descripcion',
    ];

    protected $casts = [
        'cantidad' => 'integer',
    ];

    /**
     * Relación con producto
     */
    public function producto()
    {
        return $this->belongsTo(\App\Models\Producto::class);
    }

    /**
     * Scope para entradas
     */
    public function scopeEntradas($query)
    {
        return $query->where('tipo', 'entrada');
    }

    /**
     * Scope para salidas
     */
    public function scopeSalidas($query)
    {
        return $query->where('tipo', 'salida');
    }

    /**
     * Calcula el stock actual de un producto
     */
    public static function stockActual($producto_id)
    {
        $entradas = self::where('producto_id', $producto_id)->where('tipo', 'entrada')->sum('cantidad');
        $salidas = self::where('producto_id', $producto_id)->where('tipo', 'salida')->sum('cantidad');

        return $entradas - $salidas;
    }
}
