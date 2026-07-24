<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->string('password')->nullable()->after('email');
            $table->rememberToken()->after('password');
        });

        Schema::create('cliente_direcciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained('clientes')->cascadeOnDelete();
            $table->string('etiqueta', 50)->default('Casa');
            $table->string('direccion', 255);
            $table->string('barrio', 100)->nullable();
            $table->boolean('es_predeterminada')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cliente_direcciones');
        Schema::table('clientes', function (Blueprint $table) {
            $table->dropColumn(['password', 'remember_token']);
        });
    }
};
