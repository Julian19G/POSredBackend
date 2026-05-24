<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Arreglar enum metodo: paypal → cripto
        DB::statement("ALTER TABLE pagos MODIFY COLUMN metodo ENUM('efectivo','tarjeta','transferencia','cripto','otro') NOT NULL");

        // 2. Arreglar enum estado: aprobado/rechazado → confirmado/anulado
        DB::statement("UPDATE pagos SET estado = 'confirmado' WHERE estado = 'aprobado'");
        DB::statement("UPDATE pagos SET estado = 'anulado'    WHERE estado = 'rechazado'");
        DB::statement("ALTER TABLE pagos MODIFY COLUMN estado ENUM('pendiente','confirmado','anulado') NOT NULL DEFAULT 'pendiente'");

        // 3. Agregar comprobante_id y registrado_por
        Schema::table('pagos', function (Blueprint $table) {
            $table->foreignId('comprobante_id')
                  ->nullable()
                  ->after('venta_id')
                  ->constrained('comprobantes')
                  ->nullOnDelete();

            $table->foreignId('registrado_por')
                  ->nullable()
                  ->after('comprobante_id')
                  ->constrained('users')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('pagos', function (Blueprint $table) {
            $table->dropForeign(['comprobante_id']);
            $table->dropForeign(['registrado_por']);
            $table->dropColumn(['comprobante_id', 'registrado_por']);
        });

        DB::statement("ALTER TABLE pagos MODIFY COLUMN estado ENUM('pendiente','aprobado','rechazado') NOT NULL DEFAULT 'pendiente'");
        DB::statement("ALTER TABLE pagos MODIFY COLUMN metodo ENUM('efectivo','tarjeta','transferencia','paypal') NOT NULL");
    }
};
