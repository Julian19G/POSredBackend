<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Producto extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
        'precio',
        'stock',
        'imagen',
        'activo',
        'categoria_id',
    ];

    public function categoria()
    {
        return $this->belogsTo(categoria::class);
    }

    /**
     * Relación muchos a muchos con ventas usando la tabla pivot detalle_ventas.
     */
    public function ventas()
    {
        return $this->belongsToMany(Venta::class, 'detalle_ventas')
                    ->withPivot('cantidad', 'precio_unitario', 'subtotal')
                    ->withTimestamps();
    }

    public function scopeActivos($query)
    {
        return $query->where('activo',true);
    }
}
