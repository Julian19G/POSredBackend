<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Zona extends Model
{
    use HasFactory;

    protected $table = 'zonas_cali';

    protected $fillable = [
        'nombre',
        'tipo',
        'descripcion',
        'latitud_centro',
        'longitud_centro',
        'activo',
    ];

    protected $casts = [
        'activo'           => 'boolean',
        'latitud_centro'   => 'float',
        'longitud_centro'  => 'float',
    ];

    public function domicilios()
    {
        return $this->hasMany(Domicilio::class, 'zona_id');
    }

    public function scopeActivas($query)
    {
        return $query->where('activo', true);
    }
}
