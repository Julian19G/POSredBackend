<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Variante extends Model
{
      protected $fillable = [
        'producto_id', 'nombre', 'cantidad_por_variante',
        'precio', 'stock', 'activo',
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($variante) {
            if ($variante->stock <= 0) {
                $variante->stock  = 0;
                $variante->activo = false;
            }
            if ($variante->stock > 0 && !$variante->isDirty('activo')) {
                $variante->activo = true;
            }
        });
    }
}
