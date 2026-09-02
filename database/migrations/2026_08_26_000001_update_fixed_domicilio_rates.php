<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('tarifas_domicilio')
            ->where('nombre', 'Diurna')
            ->update([
                'monto'      => 15000,
                'hora_inicio' => '06:00:00',
                'hora_fin'    => '21:00:00',
                'activo'      => true,
                'updated_at'  => now(),
            ]);

        DB::table('tarifas_domicilio')
            ->where('nombre', 'Nocturna')
            ->update([
                'monto'      => 20000,
                'hora_inicio' => '21:00:00',
                'hora_fin'    => '06:00:00',
                'activo'      => true,
                'updated_at'  => now(),
            ]);
    }

    public function down(): void
    {
        DB::table('tarifas_domicilio')
            ->where('nombre', 'Diurna')
            ->update(['hora_fin' => '22:00:00', 'updated_at' => now()]);

        DB::table('tarifas_domicilio')
            ->where('nombre', 'Nocturna')
            ->update(['hora_inicio' => '22:00:00', 'updated_at' => now()]);
    }
};