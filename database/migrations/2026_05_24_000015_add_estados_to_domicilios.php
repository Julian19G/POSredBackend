<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE domicilios MODIFY COLUMN estado ENUM('pendiente','aceptado','en_camino','enviado','entregado','cancelado') NOT NULL DEFAULT 'pendiente'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE domicilios MODIFY COLUMN estado ENUM('pendiente','enviado','entregado','cancelado') NOT NULL DEFAULT 'pendiente'");
    }
};
