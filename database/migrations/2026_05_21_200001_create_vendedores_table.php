<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vendedores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('nombre', 150);
            $table->string('telefono', 20)->nullable();
            $table->string('whatsapp', 20)->nullable();
            $table->string('email', 150)->nullable();
            $table->string('instagram', 100)->nullable();
            $table->decimal('comision_porcentaje', 5, 2)->default(0);
            $table->boolean('activo')->default(true);
            $table->text('notas')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vendedores');
    }
};
