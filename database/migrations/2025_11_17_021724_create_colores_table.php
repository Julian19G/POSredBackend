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
        Schema::create('colores', function (Blueprint $table) {
            $table->id(); // BIGINT auto incremental
            $table->string('nombre', 100)->unique();

            // Código HEX estándar (#FFFFFF)
            $table->char('codigo_hex', 7)->nullable();

            $table->text('descripcion')->nullable();
            $table->boolean('activo')->default(true);

            $table->timestamps(); // created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('colores');
    }
};
