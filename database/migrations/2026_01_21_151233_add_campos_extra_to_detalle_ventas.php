<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('detalle_ventas', function (Blueprint $table) {
            $table->string('nombre_producto')->after('producto_id');
            $table->string('codigo_producto')->nullable()->after('nombre_producto');
            $table->decimal('descuento_aplicado', 10, 2)->default(0)->after('precio_unitario');
            $table->decimal('impuesto', 10, 2)->default(0)->after('descuento_aplicado');
        });
    }

    public function down(): void
    {
        Schema::table('detalle_ventas', function (Blueprint $table) {
            $table->dropColumn([
                'nombre_producto',
                'codigo_producto',
                'descuento_aplicado',
                'impuesto'
            ]);
        });
    }
};
