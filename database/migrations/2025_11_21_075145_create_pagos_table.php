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
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('venta_id')
                  ->constrained('ventas')
                  ->onDelete('cascade'); // si se elimina la venta, se eliminan los pagos
            $table->decimal('monto', 12, 2);
            $table->enum('metodo', ['efectivo', 'tarjeta', 'transferencia', 'paypal']);
            $table->enum('estado', ['pendiente', 'aprobado', 'rechazado'])->default('pendiente');
            $table->dateTime('fecha_pago')->nullable();
            $table->string('referencia')->nullable();
            $table->string('comentarios')->nullable();
            $table->timestamps();

            $table->index(['venta_id', 'estado']); // índice para consultas frecuentes
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};
