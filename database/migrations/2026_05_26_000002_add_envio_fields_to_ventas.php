<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ventas', function (Blueprint $table) {
            if (!Schema::hasColumn('ventas', 'envio')) {
                $table->boolean('envio')->default(false)->after('total');
            }
            if (!Schema::hasColumn('ventas', 'direccion_envio')) {
                $table->string('direccion_envio', 255)->nullable()->after('envio');
            }
        });
    }

    public function down(): void
    {
        Schema::table('ventas', function (Blueprint $table) {
            $table->dropColumn(array_filter(
                ['envio', 'direccion_envio'],
                fn($col) => Schema::hasColumn('ventas', $col)
            ));
        });
    }
};
