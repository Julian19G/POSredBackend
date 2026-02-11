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

    /**
     * Relación: un descuento puede estar en muchas ventas
     */
    public function ventas()
    {
        return $this->hasMany(Venta::class);
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

    /**
     * Verifica si un cliente puede usar este descuento
     */
    public function puedeUsar(Cliente $cliente): bool
    {
        if ($this->uso_maximo !== null) {
            $totalUso = $this->ventas()->count();
            if ($totalUso >= $this->uso_maximo) {
                return false;
            }
        }

        if ($this->uso_cliente_maximo !== null) {
            $usoCliente = $this->ventas()
                ->where('cliente_id', $cliente->id)
                ->count();

            if ($usoCliente >= $this->uso_cliente_maximo) {
                return false;
            }
        }

        return true;
    }
}
