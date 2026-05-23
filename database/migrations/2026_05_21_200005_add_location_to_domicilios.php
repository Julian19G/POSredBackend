<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('domicilios', function (Blueprint $table) {
            $table->foreignId('zona_id')
                  ->nullable()
                  ->after('comentarios')
                  ->constrained('zonas_cali')
                  ->nullOnDelete();
            $table->decimal('latitud', 10, 7)->nullable()->after('zona_id');
            $table->decimal('longitud', 10, 7)->nullable()->after('latitud');
            $table->string('referencia_ubicacion', 255)->nullable()->after('longitud');
        });
    }

    public function down(): void
    {
        Schema::table('domicilios', function (Blueprint $table) {
            $table->dropForeign(['zona_id']);
            $table->dropColumn(['zona_id', 'latitud', 'longitud', 'referencia_ubicacion']);
        });
    }
};
