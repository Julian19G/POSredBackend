<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Presentacion;
use App\Models\TipoFlor;

class PresentacionTipoFlorSeeder extends Seeder
{
    public function run(): void
    {
        $presentaciones = [
            ['nombre' => 'Paquete x10', 'cantidad' => 10],
            ['nombre' => 'Paquete x20', 'cantidad' => 20],
            ['nombre' => 'Paquete x30', 'cantidad' => 30],
        ];
        foreach ($presentaciones as $p) {
            Presentacion::firstOrCreate(['nombre' => $p['nombre']], $p + ['activo' => true]);
        }

        $tipos = [
            ['nombre' => 'Sativa',    'icono' => '🌿', 'descripcion' => 'Efecto energético/cerebral'],
            ['nombre' => 'Índica',    'icono' => '💤', 'descripcion' => 'Efecto relajante/corporal'],
            ['nombre' => 'Sativa dominante',    'icono' => '🌿', 'descripcion' => 'Efecto energético/cerebral'],
            ['nombre' => 'Índica dominante',    'icono' => '💤', 'descripcion' => 'Efecto relajante/corporal'],
            ['nombre' => 'Híbrida',   'icono' => '⚖️', 'descripcion' => 'Mezcla de sativa e índica'],
            ['nombre' => 'Indoor',    'icono' => '🏠', 'descripcion' => 'Cultivo interior'],
            ['nombre' => 'Outdoor',   'icono' => '☀️', 'descripcion' => 'Cultivo exterior'],
            ['nombre' => 'Invernadero','icono' => '🌱', 'descripcion' => 'Cultivo en invernadero'],
            ['nombre' => 'CBD',       'icono' => '🧪', 'descripcion' => 'Alto en CBD'],
            ['nombre' => 'THC alto',  'icono' => '🔥', 'descripcion' => 'Alta potencia'],
            ['nombre' => 'Premium',   'icono' => '⭐', 'descripcion' => 'Calidad premium / top shelf'],
            ['nombre' => 'Fresca',   'icono' => '🌱', 'descripcion' => 'Recien cultivada'],
            ['nombre' => 'Curada',   'icono' => '', 'descripcion' => 'Calidad premium / top shelf'],
        ];
        foreach ($tipos as $t) {
            TipoFlor::firstOrCreate(['nombre' => $t['nombre']], $t + ['activo' => true]);
        }
    }
}
