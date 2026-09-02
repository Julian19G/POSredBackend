<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vendedor extends Model
{
    use HasFactory;

    protected $table = 'vendedores';

    protected $fillable = [
        'user_id',
        'codigo',
        'nombre',
        'telefono',
        'whatsapp',
        'email',
        'instagram',
        'comision_porcentaje',
        'activo',
        'notas',
    ];

    protected $casts = [
        'activo'               => 'boolean',
        'comision_porcentaje'  => 'float',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($vendedor) {
            if (empty($vendedor->codigo)) {
                $vendedor->codigo = static::generarCodigoUnico();
            }
        });
    }

    /**
     * Genera un código de referido aleatorio, opaco y único.
     * Usa un alfabeto sin caracteres ambiguos (sin 0/O/1/I/l).
     */
    public static function generarCodigoUnico(int $longitud = 10): string
    {
        $alfabeto = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz23456789';

        do {
            $codigo = '';
            for ($i = 0; $i < $longitud; $i++) {
                $codigo .= $alfabeto[random_int(0, strlen($alfabeto) - 1)];
            }
        } while (static::where('codigo', $codigo)->exists());

        return $codigo;
    }

    /**
     * Link completo de referido que comparte el vendedor.
     */
    public function getEnlaceReferidoAttribute(): string
    {
        return rtrim(config('app.frontend_url'), '/') . '/' . $this->codigo . '/login';
    }

    public function scopePorCodigo($query, ?string $codigo)
    {
        return $query->where('codigo', $codigo);
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function domiciliario()
    {
        return $this->hasOne(Domiciliario::class, 'user_id', 'user_id');
    }

    public function ventas()
    {
        return $this->hasMany(Venta::class);
    }

    public function comisiones()
    {
        return $this->hasMany(Comision::class);
    }

    public function liquidaciones()
    {
        return $this->hasMany(Liquidacion::class);
    }

    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    public function totalVentas(): float
    {
        return $this->ventas()->sum('total');
    }

    public function totalComisionado(): float
    {
        return (float) $this->comisiones()->whereIn('estado', ['pendiente', 'pagada'])->sum('monto_comision');
    }

    public function totalPagado(): float
    {
        return (float) $this->comisiones()->where('estado', 'pagada')->sum('monto_comision');
    }

    public function saldoPendiente(): float
    {
        return (float) $this->comisiones()->where('estado', 'pendiente')->sum('monto_comision');
    }
}
