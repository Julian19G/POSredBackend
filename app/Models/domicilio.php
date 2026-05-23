<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Domicilio extends Model
{
    use HasFactory;

    protected $fillable = [
        'venta_id',
        'zona_id',
        'direccion',
        'ciudad',
        'departamento',
        'pais',
        'estado',
        'costo_envio',
        'fecha_envio',
        'fecha_entrega',
        'comentarios',
        'latitud',
        'longitud',
        'referencia_ubicacion',
    ];

    protected $casts = [
        'costo_envio'   => 'float',
        'fecha_envio'   => 'datetime',
        'fecha_entrega' => 'datetime',
        'latitud'       => 'float',
        'longitud'      => 'float',
    ];

    public function venta()
    {
        return $this->belongsTo(Venta::class);
    }

    public function zona()
    {
        return $this->belongsTo(Zona::class, 'zona_id');
    }

    public function cliente()
    {
        return $this->hasOneThrough(
            Cliente::class,
            Venta::class,
            'id',
            'id',
            'venta_id',
            'cliente_id'
        );
    }

    public function scopeEstado($query, $estado)
    {
        return $query->where('estado', $estado);
    }

    public function scopePendientes($query)
    {
        return $query->whereIn('estado', ['pendiente', 'enviado']);
    }

    public function tieneUbicacion(): bool
    {
        return $this->latitud !== null && $this->longitud !== null;
    }

    public function marcarEntregado(): void
    {
        $this->estado        = 'entregado';
        $this->fecha_entrega = now();
        $this->save();
    }
}
