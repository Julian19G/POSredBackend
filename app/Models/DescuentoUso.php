<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DescuentoUso extends Model
{
    public $timestamps = false;

    protected $table = 'descuento_usos';

    protected $fillable = ['descuento_id', 'cliente_id', 'venta_id'];

    protected $casts = ['created_at' => 'datetime'];

    public function descuento()
    {
        return $this->belongsTo(Descuento::class);
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function venta()
    {
        return $this->belongsTo(Venta::class);
    }
}
