<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Domiciliario extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'nombre', 'telefono', 'vehiculo', 'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function domicilios()
    {
        return $this->hasMany(Domicilio::class);
    }

    public function rutas()
    {
        return $this->hasMany(Ruta::class);
    }

    public function rutaActiva(): ?Ruta
    {
        return $this->rutas()
            ->where('estado', 'activa')
            ->with('domicilios.venta.cliente')
            ->first();
    }

    // ── Entregas ──────────────────────────────────────────

    public function entregasHoy(): int
    {
        return $this->domicilios()
            ->where('estado', 'entregado')
            ->whereDate('fecha_entrega_real', today())
            ->count();
    }

    public function entregasMes(): int
    {
        return $this->domicilios()
            ->where('estado', 'entregado')
            ->whereMonth('fecha_entrega_real', now()->month)
            ->whereYear('fecha_entrega_real', now()->year)
            ->count();
    }

    // ── Ganancias (tarifa_monto de domicilios entregados) ─

    public function gananciaHoy(): float
    {
        return (float) $this->domicilios()
            ->where('estado', 'entregado')
            ->whereDate('fecha_entrega_real', today())
            ->sum('tarifa_monto');
    }

    public function gananciaMes(): float
    {
        return (float) $this->domicilios()
            ->where('estado', 'entregado')
            ->whereMonth('fecha_entrega_real', now()->month)
            ->whereYear('fecha_entrega_real', now()->year)
            ->sum('tarifa_monto');
    }

    public function gananciaTotal(): float
    {
        return (float) $this->domicilios()
            ->where('estado', 'entregado')
            ->sum('tarifa_monto');
    }

    // ── Domicilios activos (en progreso) ──────────────────

    public function domiciliosActivos()
    {
        return $this->domicilios()
            ->with(['venta.cliente', 'ruta'])
            ->whereIn('estado', ['aceptado', 'en_camino'])
            ->latest()
            ->get();
    }

    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    public function vehiculoLabel(): string
    {
        return match ($this->vehiculo) {
            'moto'      => '🏍 Moto',
            'bicicleta' => '🚲 Bicicleta',
            'pie'       => '🚶 A pie',
            'carro'     => '🚗 Carro',
            default     => $this->vehiculo,
        };
    }
}
