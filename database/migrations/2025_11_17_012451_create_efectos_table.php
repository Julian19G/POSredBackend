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
        Schema::create('efectos', function (Blueprint $table) {
            $table->id(); // BIGINT auto incremental
            $table->string('nombre', 100)->unique();
            $table->text('descripcion')->nullable();

            // ENUM con los 3 valores permitidos
            $table->enum('tipo', ['positivo', 'negativo', 'neutral'])
                  ->default('positivo');

            $table->boolean('activo')->default(true);

            $table->timestamps(); // created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('efectos');
    }
};
