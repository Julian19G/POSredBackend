<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tarifas_domicilio', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->decimal('monto', 10, 2);
            $table->time('hora_inicio');
            $table->time('hora_fin');
            $table->decimal('comision_plataforma', 10, 2)->default(0);
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        // Tarifas por defecto
        DB::table('tarifas_domicilio')->insert([
            [
                'nombre'              => 'Diurna',
                'monto'               => 15000,
                'hora_inicio'         => '06:00:00',
                'hora_fin'            => '21:00:00',
                'comision_plataforma' => 2000,
                'activo'              => true,
                'created_at'          => now(),
                'updated_at'          => now(),
            ],
            [
                'nombre'              => 'Nocturna',
                'monto'               => 20000,
                'hora_inicio'         => '21:00:00',
                'hora_fin'            => '06:00:00',
                'comision_plataforma' => 3000,
                'activo'              => true,
                'created_at'          => now(),
                'updated_at'          => now(),
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('tarifas_domicilio');
    }
};
