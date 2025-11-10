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
        Schema::create('ventas', function (Blueprint $table) {
            $table->id();

            // Relación con cliente
            $table->foreignId('cliente_id')
                ->constrained('clientes')
                ->onDelete('cascade');

            // Totales y descuentos
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('descuento_manual', 10, 2)->default(0);
            $table->string('motivo_descuento')->nullable();

            // (Futuro) Descuento de tabla
            $table->foreignId('descuento_id')
                ->nullable()
                ->constrained('descuentos')
                ->nullOnDelete();

            // Envío
            $table->boolean('envio')->default(false);
            $table->decimal('costo_envio', 10, 2)->default(0);
            $table->string('direccion_envio')->nullable();

            // Estado de la venta
            $table->enum('estado', ['pendiente', 'pagada', 'cancelada'])->default('pendiente');

            // Total final (subtotal - descuento + envío)
            $table->decimal('total', 10, 2)->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ventas');
    }
};
