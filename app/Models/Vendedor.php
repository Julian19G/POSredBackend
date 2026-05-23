<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vendedor extends Model
{
    use HasFactory;

    protected $table = 'vendedores';

    protected $fillable = [
        'user_id',
        'nombre',
        'telefono',
        'whatsapp',
        'email',
        'instagram',
        'comision_porcentaje',
        'activo',
        'notas',
    ];

    protected $casts = [
        'activo'               => 'boolean',
        'comision_porcentaje'  => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function ventas()
    {
        return $this->hasMany(Venta::class);
    }

    public function comisiones()
    {
        return $this->hasMany(Comision::class);
    }

    public function liquidaciones()
    {
        return $this->hasMany(Liquidacion::class);
    }

    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    public function totalVentas(): float
    {
        return $this->ventas()->sum('total');
    }

    public function totalComisionado(): float
    {
        return (float) $this->comisiones()->whereIn('estado', ['pendiente', 'pagada'])->sum('monto_comision');
    }

    public function totalPagado(): float
    {
        return (float) $this->comisiones()->where('estado', 'pagada')->sum('monto_comision');
    }

    public function saldoPendiente(): float
    {
        return (float) $this->comisiones()->where('estado', 'pendiente')->sum('monto_comision');
    }
}
