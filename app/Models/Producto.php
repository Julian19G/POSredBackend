<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Categoria;
use App\Models\Venta;
use App\Models\Sabor;
use App\Models\Efecto;
use App\Models\Color;

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

    // -----------------------------
    //   Relaciones
    // -----------------------------

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
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

    /**
     * Relación muchos a muchos: Producto <-> Sabores
     */
    public function sabores()
    {
        return $this->belongsToMany(Sabor::class, 'producto_sabor');
    }

    /**
     * Relación muchos a muchos: Producto <-> Efectos
     */
    public function efectos()
    {
        return $this->belongsToMany(Efecto::class, 'producto_efecto');
    }

    /**
     * Relación muchos a muchos: Producto <-> Colores
     */
    public function colores()
    {
        return $this->belongsToMany(Color::class, 'producto_color');
    }

    // -----------------------------
    //   Scope
    // -----------------------------
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }
}
