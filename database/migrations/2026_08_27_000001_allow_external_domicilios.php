<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('domicilios', function (Blueprint $table) {
            $table->dropForeign(['venta_id']);
            $table->foreignId('venta_id')->nullable()->change();
            $table->string('origen', 20)->default('venta')->after('venta_id');
            $table->string('cliente_nombre', 150)->nullable()->after('origen');
            $table->string('cliente_telefono', 30)->nullable()->after('cliente_nombre');
        });

        Schema::table('domicilios', function (Blueprint $table) {
            $table->foreign('venta_id')->references('id')->on('ventas')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('domicilios', function (Blueprint $table) {
            $table->dropForeign(['venta_id']);
            $table->dropColumn(['origen', 'cliente_nombre', 'cliente_telefono']);
            $table->foreignId('venta_id')->nullable(false)->change();
            $table->foreign('venta_id')->references('id')->on('ventas')->cascadeOnDelete();
        });
    }
};