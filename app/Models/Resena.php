<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resena extends Model
{
    protected $table = 'resenas';

    protected $fillable = [
        'producto_id',
        'nombre_cliente',
        'calificacion',
        'comentario',
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}
