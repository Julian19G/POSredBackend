<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Presentacion extends Model
{
    protected $table = 'presentaciones';

    protected $fillable = ['nombre', 'cantidad', 'activo'];

    protected $casts = [
        'activo'   => 'boolean',
        'cantidad' => 'float',
    ];

    public function scopeActivas($query)
    {
        return $query->where('activo', true);
    }
}
