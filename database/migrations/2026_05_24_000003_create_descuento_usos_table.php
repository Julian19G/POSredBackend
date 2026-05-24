<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('descuento_usos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('descuento_id')->constrained('descuentos')->onDelete('cascade');
            $table->foreignId('cliente_id')->constrained('clientes')->onDelete('cascade');
            $table->foreignId('venta_id')->constrained('ventas')->onDelete('cascade');
            $table->timestamp('created_at')->useCurrent();

            $table->index(['descuento_id', 'cliente_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('descuento_usos');
    }
};
