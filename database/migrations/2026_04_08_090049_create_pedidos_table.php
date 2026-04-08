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

    Schema::create('pedidos', function (Blueprint $table) {
        $table->id();

        $table->foreignId('venta_id')
            ->constrained('ventas')
            ->onDelete('cascade');

        // Estado del pedido (logística)
        $table->enum('estado', [
            'nuevo',
            'en_preparacion',
            'despachado',
            'entregado',
            'cancelado'
        ])->default('nuevo');

        // Pago
        $table->enum('metodo_pago', [
            'efectivo',
            'transferencia',
            'cripto',
            'tarjeta',
            'otro'
        ])->nullable();

        $table->enum('estado_pago', [
            'pendiente',
            'pagado',
            'reembolsado'
        ])->default('pendiente');

        // Timestamps de seguimiento
        $table->timestamp('fecha_preparacion')->nullable();
        $table->timestamp('fecha_despacho')->nullable();
        $table->timestamp('fecha_entrega')->nullable();
        $table->timestamp('fecha_cancelacion')->nullable();

        // Notas
        $table->text('notas')->nullable();

        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pedidos');
    }
};
