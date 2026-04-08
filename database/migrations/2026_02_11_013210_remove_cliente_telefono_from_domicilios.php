<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('domicilios', function (Blueprint $table) {
            // 1. Eliminar foreign key primero
            if (Schema::hasColumn('domicilios', 'cliente_id')) {
                $table->dropForeign(['cliente_id']);
                $table->dropColumn('cliente_id');
            }

            // 2. Eliminar telefono
            if (Schema::hasColumn('domicilios', 'telefono')) {
                $table->dropColumn('telefono');
            }
        });
    }

    public function down(): void
    {
        Schema::table('domicilios', function (Blueprint $table) {
            $table->unsignedBigInteger('cliente_id')->nullable();
            $table->string('telefono', 30)->nullable();

            // restaurar foreign key
            $table->foreign('cliente_id')
                  ->references('id')
                  ->on('clientes')
                  ->nullOnDelete();
        });
    }
};
