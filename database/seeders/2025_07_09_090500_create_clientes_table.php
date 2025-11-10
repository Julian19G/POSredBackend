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
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->string('telefono', 20)->unique(); // No permitir duplicados
            $table->string('email', 100)->unique();   // Email obligatorio y único
            $table->text('direccion');               // Obligatorio

            // Referencia a otro cliente que lo refirió
            $table->unsignedBigInteger('referido_por')->nullable();
            $table->foreign('referido_por')->references('id')->on('clientes')->onDelete('set null');

            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
