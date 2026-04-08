<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Domicilio extends Model
{
    use HasFactory;

    protected $fillable = [
        'venta_id',
        'direccion',
        'ciudad',
        'departamento',
        'pais',
        'telefono',
        'estado',
        'costo_envio',
        'fecha_envio',
        'fecha_entrega',
        'comentarios',
    ];

    protected $casts = [
        'costo_envio'   => 'float',
        'fecha_envio'   => 'datetime',
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
     * Cliente a través de la venta
     */
    public function cliente()
    {
        return $this->hasOneThrough(
            \App\Models\Cliente::class,
            \App\Models\Venta::class,
            'id',          // FK en ventas que apunta a domicilios (venta_id en domicilios)
            'id',          // PK de clientes
            'venta_id',    // columna local en domicilios
            'cliente_id'   // columna en ventas que apunta a clientes
        );
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
        $this->estado       = 'entregado';
        $this->fecha_entrega = now();
        $this->save();
    }
}