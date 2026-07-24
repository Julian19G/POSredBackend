<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoFlor extends Model
{
    protected $table = 'tipos_flor';

    protected $fillable = ['nombre', 'icono', 'descripcion', 'activo'];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    public function productos()
    {
        return $this->hasMany(Producto::class, 'tipo_flor_id');
    }
}
