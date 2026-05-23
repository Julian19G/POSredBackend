<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comision extends Model
{
    protected $table = 'comisiones';

    protected $fillable = [
        'vendedor_id',
        'venta_id',
        'monto_venta',
        'porcentaje',
        'monto_comision',
        'estado',
        'liquidacion_id',
    ];

    protected $casts = [
        'monto_venta'    => 'float',
        'porcentaje'     => 'float',
        'monto_comision' => 'float',
    ];

    public function vendedor()
    {
        return $this->belongsTo(Vendedor::class);
    }

    public function venta()
    {
        return $this->belongsTo(Venta::class);
    }

    public function liquidacion()
    {
        return $this->belongsTo(Liquidacion::class);
    }

    public function getBadgeEstadoAttribute(): string
    {
        return match ($this->estado) {
            'pagada'  => '<span class="badge bg-success">Pagada</span>',
            'anulada' => '<span class="badge bg-secondary">Anulada</span>',
            default   => '<span class="badge bg-warning text-dark">Pendiente</span>',
        };
    }

    public static function crearParaVenta(Venta $venta): ?self
    {
        if (!$venta->vendedor_id) return null;

        $vendedor = Vendedor::find($venta->vendedor_id);
        if (!$vendedor || $vendedor->comision_porcentaje <= 0) return null;

        return self::create([
            'vendedor_id'    => $vendedor->id,
            'venta_id'       => $venta->id,
            'monto_venta'    => $venta->total,
            'porcentaje'     => $vendedor->comision_porcentaje,
            'monto_comision' => round($venta->total * ($vendedor->comision_porcentaje / 100), 2),
            'estado'         => 'pendiente',
        ]);
    }
}
