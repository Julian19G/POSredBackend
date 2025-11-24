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
        Schema::create('domicilios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('venta_id')
                  ->constrained('ventas')
                  ->onDelete('cascade'); // si se elimina la venta, se eliminan los domicilios
            $table->foreignId('cliente_id')
                  ->constrained('clientes')
                  ->onDelete('cascade'); // opcional, referencia al cliente
            $table->string('direccion');
            $table->string('ciudad')->nullable();
            $table->string('departamento')->nullable();
            $table->string('pais')->default('Colombia');
            $table->string('telefono')->nullable();
            $table->enum('estado', ['pendiente', 'enviado', 'entregado', 'cancelado'])
                  ->default('pendiente');
            $table->decimal('costo_envio', 10, 2)->default(0);
            $table->dateTime('fecha_envio')->nullable();
            $table->dateTime('fecha_entrega')->nullable();
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
        Schema::dropIfExists('domicilios');
    }
};
