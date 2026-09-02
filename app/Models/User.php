<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isVendedor(): bool
    {
        return $this->role === 'vendedor';
    }

    public function isDomiciliario(): bool
    {
        return $this->role === 'domiciliario';
    }

    public function tienePerfilDomiciliario(): bool
    {
        return $this->isDomiciliario() || $this->domiciliario()->exists();
    }

    public function puedeGestionarOperaciones(): bool
    {
        return !$this->isDomiciliario();
    }

    public function puedeGestionarAdministracion(): bool
    {
        return $this->isAdmin();
    }

    public function vendedor()
    {
        return $this->hasOne(Vendedor::class);
    }

    public function domiciliario()
    {
        return $this->hasOne(Domiciliario::class);
    }
}
