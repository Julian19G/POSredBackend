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
        Schema::create('inventarios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('producto_id')
                  ->constrained('productos'); // si se elimina un producto, se eliminan sus movimientos
            $table->enum('tipo', ['entrada', 'salida']);
            $table->integer('cantidad');
            $table->string('descripcion')->nullable();
            $table->timestamps();

            $table->index(['producto_id', 'tipo']); // índice para consultas frecuentes
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventarios');
    }
};
