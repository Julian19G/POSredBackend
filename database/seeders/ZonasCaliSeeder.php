<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ZonasCaliSeeder extends Seeder
{
    public function run(): void
    {
        $zonas = [
            // 22 comunas oficiales de Cali con coordenadas aproximadas de sus centros
            ['nombre' => 'Comuna 1 – Sucre',              'tipo' => 'comuna', 'lat' => 3.4698,  'lng' => -76.5162],
            ['nombre' => 'Comuna 2 – Santa Rosa',          'tipo' => 'comuna', 'lat' => 3.4745,  'lng' => -76.5390],
            ['nombre' => 'Comuna 3 – San Nicolás',         'tipo' => 'comuna', 'lat' => 3.4560,  'lng' => -76.5310],
            ['nombre' => 'Comuna 4 – La Flora',            'tipo' => 'comuna', 'lat' => 3.4480,  'lng' => -76.5180],
            ['nombre' => 'Comuna 5 – Guabal',              'tipo' => 'comuna', 'lat' => 3.4340,  'lng' => -76.5120],
            ['nombre' => 'Comuna 6 – San Carlos',          'tipo' => 'comuna', 'lat' => 3.4210,  'lng' => -76.5230],
            ['nombre' => 'Comuna 7 – Unidad Deportiva',    'tipo' => 'comuna', 'lat' => 3.4420,  'lng' => -76.5430],
            ['nombre' => 'Comuna 8 – Villanueva',          'tipo' => 'comuna', 'lat' => 3.4290,  'lng' => -76.5350],
            ['nombre' => 'Comuna 9 – Los Libertadores',    'tipo' => 'comuna', 'lat' => 3.4120,  'lng' => -76.5260],
            ['nombre' => 'Comuna 10 – Calvario',           'tipo' => 'comuna', 'lat' => 3.4510,  'lng' => -76.5410],
            ['nombre' => 'Comuna 11 – San Bosco',          'tipo' => 'comuna', 'lat' => 3.4640,  'lng' => -76.5470],
            ['nombre' => 'Comuna 12 – San Antonio',        'tipo' => 'comuna', 'lat' => 3.4590,  'lng' => -76.5580],
            ['nombre' => 'Comuna 13 – El Rodeo',           'tipo' => 'comuna', 'lat' => 3.4520,  'lng' => -76.5700],
            ['nombre' => 'Comuna 14 – Amenábar',           'tipo' => 'comuna', 'lat' => 3.4400,  'lng' => -76.5620],
            ['nombre' => 'Comuna 15 – Junín',              'tipo' => 'comuna', 'lat' => 3.4310,  'lng' => -76.5530],
            ['nombre' => 'Comuna 16 – San Fernando',       'tipo' => 'comuna', 'lat' => 3.4350,  'lng' => -76.5480],
            ['nombre' => 'Comuna 17 – Sector Nuevo',       'tipo' => 'comuna', 'lat' => 3.3980,  'lng' => -76.5360],
            ['nombre' => 'Comuna 18 – El Jordán',          'tipo' => 'comuna', 'lat' => 3.3810,  'lng' => -76.5450],
            ['nombre' => 'Comuna 19 – Cristóbal Colón',    'tipo' => 'comuna', 'lat' => 3.4160,  'lng' => -76.5560],
            ['nombre' => 'Comuna 20 – Siloe',              'tipo' => 'comuna', 'lat' => 3.4380,  'lng' => -76.5780],
            ['nombre' => 'Comuna 21 – Pízamos',            'tipo' => 'comuna', 'lat' => 3.3740,  'lng' => -76.5280],
            ['nombre' => 'Comuna 22 – Pance',              'tipo' => 'comuna', 'lat' => 3.3560,  'lng' => -76.5680],

            // Sectores / barrios populares de referencia
            ['nombre' => 'Ciudad Jardín',          'tipo' => 'sector', 'lat' => 3.3940, 'lng' => -76.5520],
            ['nombre' => 'El Ingenio',             'tipo' => 'sector', 'lat' => 3.3870, 'lng' => -76.5380],
            ['nombre' => 'Chipichape',             'tipo' => 'sector', 'lat' => 3.4720, 'lng' => -76.5460],
            ['nombre' => 'Norte / Versalles',      'tipo' => 'sector', 'lat' => 3.4870, 'lng' => -76.5360],
            ['nombre' => 'Centro Cali',            'tipo' => 'sector', 'lat' => 3.4516, 'lng' => -76.5320],
            ['nombre' => 'Univalle / Meléndez',    'tipo' => 'sector', 'lat' => 3.3760, 'lng' => -76.5330],
            ['nombre' => 'Imbanaco',               'tipo' => 'sector', 'lat' => 3.4020, 'lng' => -76.5480],
            ['nombre' => 'Limonar / Santa Mónica', 'tipo' => 'sector', 'lat' => 3.3950, 'lng' => -76.5360],
            ['nombre' => 'Bocagrande / Holguines', 'tipo' => 'sector', 'lat' => 3.4230, 'lng' => -76.5610],
            ['nombre' => 'Aguablanca / Desepaz',   'tipo' => 'sector', 'lat' => 3.4050, 'lng' => -76.4920],
        ];

        $now = now();
        foreach ($zonas as $z) {
            DB::table('zonas_cali')->insertOrIgnore([
                'nombre'           => $z['nombre'],
                'tipo'             => $z['tipo'],
                'latitud_centro'   => $z['lat'],
                'longitud_centro'  => $z['lng'],
                'activo'           => true,
                'created_at'       => $now,
                'updated_at'       => $now,
            ]);
        }
    }
}
