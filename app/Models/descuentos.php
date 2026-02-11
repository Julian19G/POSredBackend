<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Descuento extends Model
{
    use HasFactory;
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


    protected $casts = [
        'activo' => 'boolean',
        'aplicable_manual' => 'boolean', 
        'valor' => 'float',
        'fecha_inicio' => 'datetime',
        'fecha_fin' => 'datetime',
        'uso_maximo' => 'integer',
        'uso_cliente_maximo' => 'integer',
    ];


    /**
     * Relación con ventas
     */
    public function ventas()
    {
        return $this->hasMany(\App\Models\Venta::class);
    }

    /**
     * Scope: descuentos activos
     */
    public function scopeActivos($query)
    {
        return $query->where('activo', true)
                     ->where('fecha_inicio', '<=', now())
                     ->where('fecha_fin', '>=', now());
    }

    /**
     * Verifica si un cliente puede usar este descuento
     */
    public function puedeUsar(\App\Models\Cliente $cliente)
    {
        $totalUso = $this->ventas()->count();
        $usoCliente = $this->ventas()->where('cliente_id', $cliente->id)->count();

        if ($this->uso_maximo && $totalUso >= $this->uso_maximo) return false;
        if ($this->uso_cliente_maximo && $usoCliente >= $this->uso_cliente_maximo) return false;

        return true;
    }
}
