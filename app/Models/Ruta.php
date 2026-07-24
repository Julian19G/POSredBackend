<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ruta extends Model
{
    protected $fillable = [
        'domiciliario_id', 'tipo', 'estado', 'nombre', 'fecha_completada',
    ];

    protected $casts = [
        'fecha_completada' => 'datetime',
    ];

    public function domiciliario()
    {
        return $this->belongsTo(Domiciliario::class);
    }

    public function domicilios()
    {
        return $this->hasMany(Domicilio::class);
    }

    // Verifica si todos los domicilios de la ruta están entregados o cancelados
    public function completada(): bool
    {
        return $this->domicilios()
            ->whereNotIn('estado', ['entregado', 'cancelado'])
            ->doesntExist();
    }

    public function tipoLabel(): string
    {
        return $this->tipo === 'express' ? '⚡ Express' : '🗺 Ruta';
    }

    public function estadoColor(): string
    {
        return match ($this->estado) {
            'activa'      => 'warning',
            'completada'  => 'success',
            'cancelada'   => 'secondary',
            default       => 'light',
        };
    }
}
