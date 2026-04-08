<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Categoria extends Model
{
    use HasFactory;

    protected $table = 'categorias';

    protected $fillable = [
        'nombre',
        'descripcion',
        'imagen',
        'activa',
        'slug',
    ];


        public function productos()
    {
        return $this->hasMany(\App\Models\Producto::class);
    }
    public static function boot()
    {
        parent::boot();

        static::creating(function ($categoria) {
            if (empty($categoria->slug)) {
                $categoria->slug = Str::slug($categoria->nombre);            }
        });

        static::updating(function ($categoria) {
            if ($categoria->isDirty('nombre')) {
                $categoria->slug = Str::slug($categoria->nombre);
            }
        });
    }
}
