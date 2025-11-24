<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Domicilio extends Model
{
    use HasFactory;

    protected $fillable = [
        'venta_id',
        'cliente_id',
        'direccion',
        'ciudad',
        'departamento',
        'pais',
        'telefono',
        'estado',       // pendiente, enviado, entregado, cancelado
        'costo_envio',
        'fecha_envio',
        'fecha_entrega',
        'comentarios',
    ];

    protected $casts = [
        'costo_envio' => 'float',
        'fecha_envio' => 'datetime',
        'fecha_entrega' => 'datetime',
    ];

    /**
     * Relación con venta
     */
    public function venta()
    {
        return $this->belongsTo(\App\Models\Venta::class);
    }

    /**
     * Relación con cliente
     */
    public function cliente()
    {
        return $this->belongsTo(\App\Models\Cliente::class);
    }

    /**
     * Scope por estado
     */
    public function scopeEstado($query, $estado)
    {
        return $query->where('estado', $estado);
    }

    /**
     * Marca el domicilio como entregado
     */
    public function marcarEntregado()
    {
        $this->estado = 'entregado';
        $this->fecha_entrega = now();
        $this->save();
    }
}
