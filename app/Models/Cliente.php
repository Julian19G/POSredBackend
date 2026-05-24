<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cliente extends Model
{
    use HasFactory;

    protected $table = 'clientes';

    protected $fillable = [
        'nombre',
        'telefono',
        'whatsapp',
        'instagram',
        'email',
        'direccion',
        'barrio',
        'ciudad',
        'fecha_nacimiento',
        'notas',
        'referido_por',
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
    ];

    public function referidoPor()
    {
        return $this->belongsTo(Cliente::class, 'referido_por');
    }

    public function referidos()
    {
        return $this->hasMany(Cliente::class, 'referido_por');
    }

    public function ventas()
    {
        return $this->hasMany(Venta::class);
    }

    public function descuentoUsos()
    {
        return $this->hasMany(DescuentoUso::class);
    }
}
