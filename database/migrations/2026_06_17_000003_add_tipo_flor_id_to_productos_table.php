<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->foreignId('tipo_flor_id')
                ->nullable()
                ->after('categoria_id')
                ->constrained('tipos_flor')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->dropForeign(['tipo_flor_id']);
            $table->dropColumn('tipo_flor_id');
        });
    }
};
