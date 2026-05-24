<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // ventas.cliente_id: CASCADE → RESTRICT (no borrar cliente con ventas)
        Schema::table('ventas', function (Blueprint $table) {
            $table->dropForeign(['cliente_id']);
            $table->foreign('cliente_id')
                  ->references('id')->on('clientes')
                  ->onDelete('restrict');
        });

        // comisiones.vendedor_id: CASCADE → RESTRICT (no borrar vendedor con comisiones)
        Schema::table('comisiones', function (Blueprint $table) {
            $table->dropForeign(['vendedor_id']);
            $table->foreign('vendedor_id')
                  ->references('id')->on('vendedores')
                  ->onDelete('restrict');
        });

        // liquidaciones.vendedor_id: CASCADE → RESTRICT (no borrar vendedor con liquidaciones)
        Schema::table('liquidaciones', function (Blueprint $table) {
            $table->dropForeign(['vendedor_id']);
            $table->foreign('vendedor_id')
                  ->references('id')->on('vendedores')
                  ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::table('ventas', function (Blueprint $table) {
            $table->dropForeign(['cliente_id']);
            $table->foreign('cliente_id')->references('id')->on('clientes')->onDelete('cascade');
        });

        Schema::table('comisiones', function (Blueprint $table) {
            $table->dropForeign(['vendedor_id']);
            $table->foreign('vendedor_id')->references('id')->on('vendedores')->onDelete('cascade');
        });

        Schema::table('liquidaciones', function (Blueprint $table) {
            $table->dropForeign(['vendedor_id']);
            $table->foreign('vendedor_id')->references('id')->on('vendedores')->onDelete('cascade');
        });
    }
};
