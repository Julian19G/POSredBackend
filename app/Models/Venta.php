<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\DetalleVenta;

class Venta extends Model
{
    use HasFactory;

    protected $fillable = ['total'];

    /**
     * Relación: una venta tiene muchos detalles.
     */
    public function detalles()
    {
        return $this->hasMany(DetalleVenta::class);
    }
}
