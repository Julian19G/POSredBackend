<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sabor extends Model
{
    use HasFactory;

    // Nombre de la tabla (opcional si sigue la convención)
    protected $table = 'sabores';

    /**
     * Campos que se pueden asignar masivamente
     */
    protected $fillable = [
        'nombre',
        'descripcion',
        'intensidad',
        'activo',
        'imagen',
    ];

    /**
     * Casting de tipos
     */
    protected $casts = [
        'activo' => 'boolean',
        'intensidad' => 'integer',
    ];

    /**
     * Relación muchos a muchos con productos
     */
    public function productos()
    {
        return $this->belongsToMany(Producto::class, 'producto_sabor')
                    ->withTimestamps();
    }

    /**
     * Scope: solo sabores activos
     */
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }
}
