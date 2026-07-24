<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Cliente extends Authenticatable
{
    use HasFactory, HasApiTokens, Notifiable;

    protected $table = 'clientes';

    protected $fillable = [
        'nombre',
        'telefono',
        'whatsapp',
        'instagram',
        'email',
        'password',
        'direccion',
        'barrio',
        'ciudad',
        'fecha_nacimiento',
        'notas',
        'referido_por',
        'vendedor_id',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'fecha_nacimiento' => 'date',
        'password'         => 'hashed',
    ];

    public function referidoPor()
    {
        return $this->belongsTo(Cliente::class, 'referido_por');
    }

    public function referidos()
    {
        return $this->hasMany(Cliente::class, 'referido_por');
    }

    /**
     * Vendedor que refirió a este cliente (vía su link de referido).
     */
    public function vendedor()
    {
        return $this->belongsTo(Vendedor::class);
    }

    public function ventas()
    {
        return $this->hasMany(Venta::class);
    }

    public function descuentoUsos()
    {
        return $this->hasMany(DescuentoUso::class);
    }

    public function direcciones()
    {
        return $this->hasMany(ClienteDireccion::class);
    }
}
