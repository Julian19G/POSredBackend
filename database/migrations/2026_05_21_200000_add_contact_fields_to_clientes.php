<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->string('whatsapp', 20)->nullable()->after('telefono');
            $table->string('instagram', 100)->nullable()->after('whatsapp');
            $table->date('fecha_nacimiento')->nullable()->after('instagram');
            $table->string('barrio', 100)->nullable()->after('direccion');
            $table->string('ciudad', 100)->nullable()->default('Cali')->after('barrio');
            $table->text('notas')->nullable()->after('ciudad');
        });
    }

    public function down(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->dropColumn(['whatsapp', 'instagram', 'fecha_nacimiento', 'barrio', 'ciudad', 'notas']);
        });
    }
};
