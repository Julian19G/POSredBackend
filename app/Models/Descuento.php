<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Descuento extends Model
{
    use HasFactory;

    /**
     * Tabla asociada
     * (opcional, pero la dejamos explícita para evitar líos)
     */
    protected $table = 'descuentos';

    /**
     * Campos asignables
     */
    protected $fillable = [
        'nombre',
        'codigo',
        'tipo',
        'valor',
        'fecha_inicio',
        'fecha_fin',
        'activo',
        'aplicable_manual',
        'uso_maximo',
        'uso_cliente_maximo',
    ];

    /**
     * Casts automáticos
     */
    protected $casts = [
        'activo' => 'boolean',
        'aplicable_manual' => 'boolean',
        'valor' => 'float',
        'fecha_inicio' => 'datetime',
        'fecha_fin' => 'datetime',
        'uso_maximo' => 'integer',
        'uso_cliente_maximo' => 'integer',
    ];

    public function ventas()
    {
        return $this->hasMany(Venta::class);
    }

    public function usos()
    {
        return $this->hasMany(DescuentoUso::class);
    }

    /**
     * Scope: descuentos activos y vigentes
     */
    public function scopeActivos($query)
    {
        return $query
            ->where('activo', true)
            ->where('fecha_inicio', '<=', now())
            ->where('fecha_fin', '>=', now());
    }

    public function puedeUsar(Cliente $cliente): bool
    {
        if ($this->uso_maximo !== null) {
            if ($this->usos()->count() >= $this->uso_maximo) {
                return false;
            }
        }

        if ($this->uso_cliente_maximo !== null) {
            if ($this->usos()->where('cliente_id', $cliente->id)->count() >= $this->uso_cliente_maximo) {
                return false;
            }
        }

        return true;
    }
}
