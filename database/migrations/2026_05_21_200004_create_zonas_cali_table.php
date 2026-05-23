<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('zonas_cali', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 150);
            $table->enum('tipo', ['comuna', 'sector', 'barrio'])->default('comuna');
            $table->string('descripcion', 255)->nullable();
            $table->decimal('latitud_centro', 10, 7)->nullable();
            $table->decimal('longitud_centro', 10, 7)->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('zonas_cali');
    }
};
