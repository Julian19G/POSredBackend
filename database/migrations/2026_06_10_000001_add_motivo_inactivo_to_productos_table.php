<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->string('motivo_inactivo')->nullable()->after('activo');
            $table->text('motivo_inactivo_detalle')->nullable()->after('motivo_inactivo');
        });
    }

    public function down(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->dropColumn(['motivo_inactivo', 'motivo_inactivo_detalle']);
        });
    }
};
