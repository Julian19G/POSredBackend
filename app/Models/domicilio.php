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
        'domiciliario_id',
        'ruta_id',
        'tarifa_id',
        'tipo',
        'tarifa_monto',
        'cobrar_en_entrega',
        'monto_cobrar',
        'instrucciones_recogida',
        'instrucciones_entrega',
        'direccion',
        'ciudad',
        'departamento',
        'pais',
        'estado',
        'costo_envio',
        'fecha_envio',
        'fecha_entrega',
        'fecha_aceptacion',
        'fecha_recogida',
        'fecha_entrega_real',
        'comentarios',
        'latitud',
        'longitud',
        'referencia_ubicacion',
    ];

    protected $casts = [
        'costo_envio'        => 'float',
        'tarifa_monto'       => 'float',
        'monto_cobrar'       => 'float',
        'cobrar_en_entrega'  => 'boolean',
        'fecha_envio'        => 'datetime',
        'fecha_entrega'      => 'datetime',
        'fecha_aceptacion'   => 'datetime',
        'fecha_recogida'     => 'datetime',
        'fecha_entrega_real' => 'datetime',
        'latitud'            => 'float',
        'longitud'           => 'float',
    ];

    public function venta()
    {
        return $this->belongsTo(Venta::class);
    }

    public function zona()
    {
        return $this->belongsTo(Zona::class, 'zona_id');
    }

    public function domiciliario()
    {
        return $this->belongsTo(Domiciliario::class);
    }

    public function ruta()
    {
        return $this->belongsTo(Ruta::class);
    }

    public function tarifa()
    {
        return $this->belongsTo(TarifaDomicilio::class, 'tarifa_id');
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
        return $query->whereIn('estado', ['pendiente', 'aceptado', 'en_camino']);
    }

    public function scopeDisponibles($query)
    {
        return $query->where('estado', 'pendiente')->whereNull('domiciliario_id');
    }

    public function tieneUbicacion(): bool
    {
        return $this->latitud !== null && $this->longitud !== null;
    }

    public function estadoColor(): string
    {
        return match ($this->estado) {
            'pendiente'  => 'secondary',
            'aceptado'   => 'info',
            'en_camino'  => 'primary',
            'entregado'  => 'success',
            'cancelado'  => 'danger',
            default      => 'light',
        };
    }

    public function estadoLabel(): string
    {
        return match ($this->estado) {
            'pendiente'  => 'Pendiente',
            'aceptado'   => 'Aceptado',
            'en_camino'  => 'En camino',
            'entregado'  => 'Entregado',
            'cancelado'  => 'Cancelado',
            default      => $this->estado,
        };
    }

    // Si la venta ya fue pagada, no hay que cobrar
    public function debeCobrarse(): bool
    {
        return $this->venta?->estado !== 'pagada';
    }

    public function marcarEntregado(): void
    {
        $this->estado            = 'entregado';
        $this->fecha_entrega_real = now();
        $this->save();
    }
}
