<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('domicilios', function (Blueprint $table) {
            $table->foreignId('domiciliario_id')->nullable()->after('zona_id')
                  ->constrained('domiciliarios')->nullOnDelete();
            $table->foreignId('ruta_id')->nullable()->after('domiciliario_id')
                  ->constrained('rutas')->nullOnDelete();
            $table->foreignId('tarifa_id')->nullable()->after('ruta_id')
                  ->constrained('tarifas_domicilio')->nullOnDelete();
            $table->enum('tipo', ['normal', 'express'])->default('normal')->after('tarifa_id');
            $table->decimal('tarifa_monto', 10, 2)->nullable()->after('tipo');
            $table->boolean('cobrar_en_entrega')->default(true)->after('tarifa_monto');
            $table->decimal('monto_cobrar', 10, 2)->nullable()->after('cobrar_en_entrega');
            $table->text('instrucciones_recogida')->nullable()->after('monto_cobrar');
            $table->text('instrucciones_entrega')->nullable()->after('instrucciones_recogida');
            $table->timestamp('fecha_aceptacion')->nullable()->after('instrucciones_entrega');
            $table->timestamp('fecha_recogida')->nullable()->after('fecha_aceptacion');
            $table->timestamp('fecha_entrega_real')->nullable()->after('fecha_recogida');
        });
    }

    public function down(): void
    {
        Schema::table('domicilios', function (Blueprint $table) {
            $table->dropForeign(['domiciliario_id']);
            $table->dropForeign(['ruta_id']);
            $table->dropForeign(['tarifa_id']);
            $table->dropColumn([
                'domiciliario_id', 'ruta_id', 'tarifa_id', 'tipo', 'tarifa_monto',
                'cobrar_en_entrega', 'monto_cobrar', 'instrucciones_recogida',
                'instrucciones_entrega', 'fecha_aceptacion', 'fecha_recogida', 'fecha_entrega_real',
            ]);
        });
    }
};
