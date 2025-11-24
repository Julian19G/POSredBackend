<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Color extends Model
{
    use HasFactory;

    protected $table = 'colores';

    /**
     * Campos permitidos para asignación masiva
     */
    protected $fillable = [
        'nombre',
        'codigo_hex',
        'descripcion',
        'activo',
    ];

    /**
     * Casting automático
     */
    protected $casts = [
        'activo' => 'boolean',
    ];

    /**
     * Relación muchos a muchos con productos
     */
    public function productos()
    {
        return $this->belongsToMany(Producto::class, 'producto_color')
                    ->withTimestamps();
    }

    

    /**
     * Scope: solo colores activos
     */
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }
}
