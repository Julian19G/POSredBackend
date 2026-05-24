<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // detalle_ventas.venta_id → ventas (CASCADE: al borrar venta, borrar sus detalles)
        // venta_id FK already exists with CASCADE — skip it
        // detalle_ventas.producto_id → productos (RESTRICT: no borrar producto con ventas históricas)
        Schema::table('detalle_ventas', function (Blueprint $table) {
            $table->foreign('producto_id')
                  ->references('id')->on('productos')
                  ->onDelete('restrict');
        });

        // ventas.descuento_id → descuentos (SET NULL: al borrar descuento, conservar venta)
        Schema::table('ventas', function (Blueprint $table) {
            $table->foreign('descuento_id')
                  ->references('id')->on('descuentos')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('detalle_ventas', function (Blueprint $table) {
            $table->dropForeignIfExists('detalle_ventas_venta_id_foreign');
            $table->dropForeignIfExists('detalle_ventas_producto_id_foreign');
        });

        Schema::table('ventas', function (Blueprint $table) {
            $table->dropForeign(['descuento_id']);
        });
    }
};
