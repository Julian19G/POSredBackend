<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('liquidaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendedor_id')->constrained('vendedores')->cascadeOnDelete();
            $table->decimal('monto_total', 12, 2);
            $table->enum('metodo_pago', ['efectivo', 'transferencia', 'cripto', 'tarjeta', 'otro'])->default('efectivo');
            $table->string('referencia')->nullable();
            $table->text('notas')->nullable();
            $table->date('fecha_pago');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('liquidaciones');
    }
};
