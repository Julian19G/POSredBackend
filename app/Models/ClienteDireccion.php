<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClienteDireccion extends Model
{
    protected $table = 'cliente_direcciones';

    protected $fillable = [
        'cliente_id',
        'etiqueta',
        'direccion',
        'barrio',
        'es_predeterminada',
    ];

    protected $casts = [
        'es_predeterminada' => 'boolean',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
}
