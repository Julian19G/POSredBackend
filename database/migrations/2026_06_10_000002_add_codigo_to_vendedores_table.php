<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Vendedor;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vendedores', function (Blueprint $table) {
            $table->string('codigo', 16)->nullable()->unique()->after('id');
        });

        // Backfill: asignar un código único a los vendedores existentes
        Vendedor::whereNull('codigo')->get()->each(function ($vendedor) {
            $vendedor->codigo = Vendedor::generarCodigoUnico();
            $vendedor->save();
        });
    }

    public function down(): void
    {
        Schema::table('vendedores', function (Blueprint $table) {
            $table->dropUnique(['codigo']);
            $table->dropColumn('codigo');
        });
    }
};
