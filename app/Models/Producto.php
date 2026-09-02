<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Categoria;
use App\Models\Venta;
use App\Models\Sabor;
use App\Models\Efecto;
use App\Models\Color;

class Producto extends Model
{
    use HasFactory;

    protected $fillable = [
    'nombre', 'descripcion', 'stock',
    'imagen', 'activo', 'categoria_id', 'tipo_flor_id',
    'motivo_inactivo', 'motivo_inactivo_detalle',
    ];

    protected $casts = [
        'stock'  => 'float',
        'activo' => 'boolean',
    ];

    /**
     * Recalcula la disponibilidad (en paquetes) de cada variante a partir
     * del stock total del producto (en unidades base). El stock por variante
     * es una caché derivada: floor(stock_producto / cantidad_por_variante).
     */
    public function sincronizarStockPaquetes(): void
    {
        $total = max((float) $this->stock, 0);

        foreach ($this->variantes as $variante) {
            $cant = (float) $variante->cantidad_por_variante;
            $disponibles = $cant > 0 ? (int) floor($total / $cant) : 0;

            $variante->stock  = $disponibles;
            $variante->activo = $disponibles > 0;
            $variante->save();
        }
    }

    /**
     * Motivos predefinidos para inhabilitar un producto.
     * "Otro" habilita el campo de texto libre motivo_inactivo_detalle.
     */
    public const MOTIVOS_INACTIVO = [
        'Agotado por venta personal',
        'Otro',
    ];

    // -----------------------------
    //   Relaciones
    // -----------------------------

    public function variantes()
    {
        return $this->hasMany(Variante::class);
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }

    public function tipoFlor()
    {
        return $this->belongsTo(\App\Models\TipoFlor::class, 'tipo_flor_id');
    }

    /**
     * Relación muchos a muchos con ventas usando la tabla pivot detalle_ventas.
     */
    public function ventas()
    {
        return $this->belongsToMany(Venta::class, 'detalle_ventas')
                    ->withPivot('cantidad', 'precio_unitario', 'subtotal')
                    ->withTimestamps();
    }

    /**
     * Relación muchos a muchos: Producto <-> Sabores
     */
    public function sabores()
    {
        return $this->belongsToMany(Sabor::class, 'producto_sabor');
    }

    /**
     * Relación muchos a muchos: Producto <-> Efectos
     */
    public function efectos()
    {
        return $this->belongsToMany(Efecto::class, 'producto_efecto');
    }

    /**
     * Relación muchos a muchos: Producto <-> Colores
     */
    public function colores()
    {
        return $this->belongsToMany(Color::class, 'producto_color');
    }

    /**
     * Relación muchos a muchos: Producto <-> Descuentos (directos)
     */
    public function descuentos()
    {
        return $this->belongsToMany(Descuento::class, 'descuentos_productos');
    }

    /**
     * Descuentos activos y vigentes que aplican a este producto,
     * ya sea de forma directa o a través de su categoría.
     *
     * Devuelve una Collection de Descuento (no un query builder),
     * consultando en una sola query.
     */
    public function descuentosActivos()
    {
        $ahora = now();
        $categoriaId = $this->categoria_id;

        return Descuento::where('activo', true)
            ->where('fecha_inicio', '<=', $ahora)
            ->where('fecha_fin', '>=', $ahora)
            ->where(function ($query) use ($categoriaId) {
                $query->whereHas('productos', function ($q) {
                    $q->where('productos.id', $this->id);
                });

                if ($categoriaId) {
                    $query->orWhereHas('categorias', function ($q) use ($categoriaId) {
                        $q->where('categorias.id', $categoriaId);
                    });
                }
            })
            ->get();
    }

        /**
         * Determina cuál es el descuento que más ahorro genera para este producto.
         * Como el precio vive en las variantes, usamos un precio de referencia
         * (por defecto la variante más barata) solo para decidir cuál descuento
         * "gana" cuando hay varios en juego (ej: 20% vs $2.000 fijo).
         */
        public function mejorDescuento($precioReferencia = null)
        {
            $precio = $precioReferencia ?? $this->variantes->min('precio') ?? 0;
            $descuentos = $this->descuentosActivos();

            if ($descuentos->isEmpty()) {
                return null;
            }

            $ahorroDe = function ($descuento) use ($precio) {
                return $descuento->tipo === 'porcentaje'
                    ? $precio * $descuento->valor / 100
                    : $descuento->valor;
            };

            return $descuentos->reduce(function ($mejor, $actual) use ($ahorroDe) {
                if ($mejor === null) {
                    return $actual;
                }
                return $ahorroDe($actual) > $ahorroDe($mejor) ? $actual : $mejor;
            });
        }

        /**
         * Calcula el precio con descuento para un precio puntual (el de una
         * variante). Siempre requiere el precio explícito — ya no existe
         * precio_venta a nivel de producto.
         */
        public function precioConDescuento($precio)
        {
            $descuento = $this->mejorDescuento($precio);

            if (!$descuento) {
                return (float) $precio;
            }

            return $descuento->tipo === 'porcentaje'
                ? round($precio * (1 - $descuento->valor / 100), 2)
                : round(max(0, $precio - $descuento->valor), 2);
        }


    // ─────────────────────────────────────────────────────────────
    //   Scope
    // ─────────────────────────────────────────────────────────────
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($producto) {
            if ($producto->stock <= 0) {
                $producto->stock  = 0;
                $producto->activo = false;
            }
            // Solo reactiva si el campo activo no fue modificado manualmente
            if ($producto->stock > 0 && !$producto->isDirty('activo')) {
                $producto->activo = true;
            }
        });
    }
}