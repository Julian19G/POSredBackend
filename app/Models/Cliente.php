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
        'email',
        'direccion',
        'referido_por',
    ];

    //Relacionar que un cliente fue referido por otro
    public function referidoPor()
    {
        return $this->belongsTo(Cliente::Class, 'referido_por');
    }

    //Relacionar que un cliente refiere a otros

    public function referidos()
    {
        return $this->hasMany(Cliente::class, 'referido_por');
    }
}
