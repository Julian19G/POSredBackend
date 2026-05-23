<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comisiones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendedor_id')->constrained('vendedores')->cascadeOnDelete();
            $table->foreignId('venta_id')->constrained('ventas')->cascadeOnDelete();
            $table->decimal('monto_venta', 12, 2);
            $table->decimal('porcentaje', 5, 2);
            $table->decimal('monto_comision', 12, 2);
            $table->enum('estado', ['pendiente', 'pagada', 'anulada'])->default('pendiente');
            $table->foreignId('liquidacion_id')->nullable()->constrained('liquidaciones')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comisiones');
    }
};
