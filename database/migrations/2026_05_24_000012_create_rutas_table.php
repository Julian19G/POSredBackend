<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rutas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('domiciliario_id')->constrained('domiciliarios')->restrictOnDelete();
            $table->enum('tipo', ['ruta', 'express'])->default('ruta');
            $table->enum('estado', ['activa', 'completada', 'cancelada'])->default('activa');
            $table->string('nombre', 100)->nullable();
            $table->timestamp('fecha_completada')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rutas');
    }
};
