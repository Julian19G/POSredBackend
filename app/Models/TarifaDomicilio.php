<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TarifaDomicilio extends Model
{
    protected $table = 'tarifas_domicilio';

    protected $fillable = [
        'nombre', 'monto', 'hora_inicio', 'hora_fin', 'comision_plataforma', 'activo',
    ];

    protected $casts = [
        'monto'               => 'float',
        'comision_plataforma' => 'float',
        'activo'              => 'boolean',
    ];

    public function domicilios()
    {
        return $this->hasMany(Domicilio::class, 'tarifa_id');
    }

    // Retorna la tarifa vigente según la hora actual
    public static function vigente(): ?self
    {
        $hora = now()->format('H:i:s');

        return self::where('activo', true)
            ->get()
            ->first(function ($tarifa) use ($hora) {
                $inicio = $tarifa->hora_inicio;
                $fin    = $tarifa->hora_fin;

                // Caso cruzado medianoche (22:00 → 06:00)
                if ($inicio > $fin) {
                    return $hora >= $inicio || $hora < $fin;
                }
                return $hora >= $inicio && $hora < $fin;
            });
    }
}
