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
        Schema::create('categorias', function (Blueprint $table) {
            $table->id();
            $table->string('nombre',100)->unique();
            $table->text('descripcion')->nullable();
            $table->string('imagen')->nullable();
            $table->boolean('activa')->default(true);
            $table->string('slug')->unique();
            $table->timestamps();
        });

        Schema::table('productos', function (Blueprint $table) {
            $table->foreign('categoria_id')
                ->references('id')->on('categorias')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categorias');
    }
};
