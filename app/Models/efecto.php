<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Efecto extends Model
{
    use HasFactory;

    protected $table = 'efectos';

    /**
     * Campos asignables masivamente
     */
    protected $fillable = [
        'nombre',
        'descripcion',
        'tipo',
        'imagen',
        'activo',
    ];

    /**
     * Casting de columnas
     */
    protected $casts = [
        'activo' => 'boolean',
    ];

    /**
     * Relación muchos a muchos con productos
     */
    public function productos()
    {
        return $this->belongsToMany(Producto::class, 'producto_efecto')
                    ->withTimestamps();
    }

    /**
     * Scope para efectos activos
     */
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    /**
     * Scope para filtrar por tipo (positivo, negativo, neutral)
     */
    public function scopeTipo($query, $tipo)
    {
        return $query->where('tipo', $tipo);
    }
}
