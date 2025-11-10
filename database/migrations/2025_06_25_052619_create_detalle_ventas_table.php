<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('detalle_ventas', function (Blueprint $table) {
            $table->id();

            // Relaciones
            $table->foreignId('venta_id')
                ->constrained('ventas')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreignId('producto_id')
                ->nullable()
                ->constrained('productos')
                ->nullOnDelete()
                ->cascadeOnUpdate();

            // Copia del nombre/código del producto para mantener historial
            $table->string('nombre_producto');
            $table->string('codigo_producto')->nullable();

            // Datos de la venta
            $table->unsignedInteger('cantidad');
            $table->decimal('precio_unitario', 10, 2);
            $table->decimal('descuento_aplicado', 10, 2)->default(0);
            $table->decimal('impuesto', 10, 2)->default(0);
            $table->decimal('subtotal', 10, 2);

            $table->timestamps();

            // Índices para optimizar búsquedas
            $table->index('venta_id');
            $table->index('producto_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_ventas');
    }
};
