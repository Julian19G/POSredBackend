<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comprobantes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedido_id')->constrained('pedidos')->cascadeOnDelete();
            $table->enum('tipo', ['efectivo', 'transferencia', 'cripto', 'tarjeta', 'otro']);
            $table->decimal('monto', 12, 2);
            $table->string('referencia', 255)->nullable();  // número de transacción, hash cripto, etc.
            $table->string('imagen_path', 255)->nullable(); // captura/foto del comprobante
            $table->enum('estado', ['pendiente', 'verificado', 'rechazado'])->default('pendiente');
            $table->text('notas')->nullable();
            $table->timestamp('verificado_en')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comprobantes');
    }
};
