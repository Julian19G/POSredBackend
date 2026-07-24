<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sabor;
use App\Models\Efecto;
use App\Models\Color;

class AtributosProductoSeeder extends Seeder
{
    public function run(): void
    {
        $this->sembrarSabores();
        $this->sembrarEfectos();
        $this->sembrarColores();
    }

    /**
     * Inserta solo los nombres que NO existan ya (comparación sin
     * mayúsculas/espacios) para no duplicar lo que el usuario ya creó.
     */
    private function insertarUnicos(string $modelo, array $registros): int
    {
        $existentes = $modelo::pluck('nombre')
            ->map(fn ($n) => mb_strtolower(trim($n)))
            ->all();

        $creados = 0;
        foreach ($registros as $reg) {
            $clave = mb_strtolower(trim($reg['nombre']));
            if (in_array($clave, $existentes, true)) {
                continue;
            }
            $modelo::create($reg + ['activo' => true]);
            $existentes[] = $clave;
            $creados++;
        }
        return $creados;
    }

    private function sembrarSabores(): void
    {
        // intensidad: 1 suave · 2 medio · 3 intenso
        $sabores = [
            ['nombre' => 'Mango',              'intensidad' => 2, 'descripcion' => 'Dulce tropical'],
            ['nombre' => 'Uva',                'intensidad' => 2, 'descripcion' => 'Dulce a uva morada'],
            ['nombre' => 'Arándano',           'intensidad' => 2, 'descripcion' => 'Frutos azules'],
            ['nombre' => 'Mora',               'intensidad' => 2, 'descripcion' => 'Frutos del bosque'],
            ['nombre' => 'Frambuesa',          'intensidad' => 2, 'descripcion' => 'Baya dulce-ácida'],
            ['nombre' => 'Cereza',             'intensidad' => 2, 'descripcion' => 'Cereza madura'],
            ['nombre' => 'Manzana',            'intensidad' => 1, 'descripcion' => 'Manzana verde'],
            ['nombre' => 'Piña',               'intensidad' => 2, 'descripcion' => 'Tropical ácida'],
            ['nombre' => 'Sandía',             'intensidad' => 1, 'descripcion' => 'Fresca y dulce'],
            ['nombre' => 'Durazno',            'intensidad' => 2, 'descripcion' => 'Melocotón jugoso'],
            ['nombre' => 'Melón',              'intensidad' => 1, 'descripcion' => 'Suave y dulce'],
            ['nombre' => 'Coco',               'intensidad' => 2, 'descripcion' => 'Cremoso tropical'],
            ['nombre' => 'Banano',             'intensidad' => 2, 'descripcion' => 'Plátano dulce'],
            ['nombre' => 'Naranja',            'intensidad' => 2, 'descripcion' => 'Cítrico dulce'],
            ['nombre' => 'Mandarina',          'intensidad' => 2, 'descripcion' => 'Cítrico suave'],
            ['nombre' => 'Lima',               'intensidad' => 2, 'descripcion' => 'Cítrico ácido'],
            ['nombre' => 'Pomelo',             'intensidad' => 2, 'descripcion' => 'Toronja amarga-dulce'],
            ['nombre' => 'Guayaba',            'intensidad' => 2, 'descripcion' => 'Tropical aromática'],
            ['nombre' => 'Maracuyá',           'intensidad' => 2, 'descripcion' => 'Ácido tropical'],
            ['nombre' => 'Lichi',              'intensidad' => 2, 'descripcion' => 'Floral dulce'],
            ['nombre' => 'Tropical',           'intensidad' => 2, 'descripcion' => 'Mezcla de frutas tropicales'],
            ['nombre' => 'Frutos rojos',       'intensidad' => 2, 'descripcion' => 'Mix de bayas rojas'],
            ['nombre' => 'Frutos del bosque',  'intensidad' => 2, 'descripcion' => 'Bayas silvestres'],
            ['nombre' => 'Diésel',             'intensidad' => 3, 'descripcion' => 'Combustible penetrante'],
            ['nombre' => 'Gasolina',           'intensidad' => 3, 'descripcion' => 'Gassy intenso'],
            ['nombre' => 'Menta',              'intensidad' => 2, 'descripcion' => 'Refrescante'],
            ['nombre' => 'Mentol',             'intensidad' => 2, 'descripcion' => 'Frescor intenso'],
            ['nombre' => 'Eucalipto',          'intensidad' => 2, 'descripcion' => 'Herbal mentolado'],
            ['nombre' => 'Hierbabuena',        'intensidad' => 2, 'descripcion' => 'Menta suave'],
            ['nombre' => 'Lavanda',            'intensidad' => 2, 'descripcion' => 'Floral relajante'],
            ['nombre' => 'Floral',             'intensidad' => 1, 'descripcion' => 'Notas de flores'],
            ['nombre' => 'Rosa',               'intensidad' => 1, 'descripcion' => 'Pétalos dulces'],
            ['nombre' => 'Chocolate',          'intensidad' => 2, 'descripcion' => 'Cacao dulce'],
            ['nombre' => 'Café',               'intensidad' => 2, 'descripcion' => 'Tostado amargo'],
            ['nombre' => 'Vainilla',           'intensidad' => 2, 'descripcion' => 'Dulce cremoso'],
            ['nombre' => 'Caramelo',           'intensidad' => 2, 'descripcion' => 'Azúcar tostada'],
            ['nombre' => 'Miel',               'intensidad' => 2, 'descripcion' => 'Dulce floral'],
            ['nombre' => 'Galleta',            'intensidad' => 2, 'descripcion' => 'Tipo cookie'],
            ['nombre' => 'Crema',              'intensidad' => 2, 'descripcion' => 'Lácteo suave'],
            ['nombre' => 'Algodón de azúcar',  'intensidad' => 3, 'descripcion' => 'Muy dulce'],
            ['nombre' => 'Chicle',             'intensidad' => 2, 'descripcion' => 'Bubblegum'],
            ['nombre' => 'Tarta de queso',     'intensidad' => 2, 'descripcion' => 'Cheesecake dulce'],
            ['nombre' => 'Limonada',           'intensidad' => 2, 'descripcion' => 'Cítrica dulce'],
            ['nombre' => 'Especiado',          'intensidad' => 2, 'descripcion' => 'Notas a especias'],
            ['nombre' => 'Pimienta',           'intensidad' => 2, 'descripcion' => 'Picante seco'],
            ['nombre' => 'Canela',             'intensidad' => 2, 'descripcion' => 'Especia dulce'],
            ['nombre' => 'Anís',               'intensidad' => 1, 'descripcion' => 'Dulce herbal'],
            ['nombre' => 'Nuez',               'intensidad' => 1, 'descripcion' => 'Fruto seco'],
            ['nombre' => 'Almendra',           'intensidad' => 1, 'descripcion' => 'Suave a nuez'],
            ['nombre' => 'Madera',             'intensidad' => 1, 'descripcion' => 'Amaderado'],
            ['nombre' => 'Sándalo',            'intensidad' => 2, 'descripcion' => 'Madera aromática'],
            ['nombre' => 'Té verde',           'intensidad' => 1, 'descripcion' => 'Herbal fresco'],
            ['nombre' => 'Herbal',             'intensidad' => 2, 'descripcion' => 'Verde y vegetal'],
            ['nombre' => 'Haze',               'intensidad' => 2, 'descripcion' => 'Cítrico-especiado clásico'],
            ['nombre' => 'Kush',               'intensidad' => 2, 'descripcion' => 'Terroso clásico'],
            ['nombre' => 'OG',                 'intensidad' => 2, 'descripcion' => 'Pino-combustible'],
        ];

        $n = $this->insertarUnicos(Sabor::class, $sabores);
        $this->command?->info("Sabores nuevos: {$n}");
    }

    private function sembrarEfectos(): void
    {
        $efectos = [
            // Positivos
            ['nombre' => 'Relajante',           'tipo' => 'positivo', 'descripcion' => 'Calma cuerpo y mente'],
            ['nombre' => 'Eufórico',            'tipo' => 'positivo', 'descripcion' => 'Sensación de bienestar'],
            ['nombre' => 'Energético',          'tipo' => 'positivo', 'descripcion' => 'Da energía'],
            ['nombre' => 'Creativo',            'tipo' => 'positivo', 'descripcion' => 'Estimula la creatividad'],
            ['nombre' => 'Feliz',               'tipo' => 'positivo', 'descripcion' => 'Mejora el ánimo'],
            ['nombre' => 'Enfocado',            'tipo' => 'positivo', 'descripcion' => 'Aumenta la concentración'],
            ['nombre' => 'Edificante',          'tipo' => 'positivo', 'descripcion' => 'Levanta el estado de ánimo'],
            ['nombre' => 'Social',              'tipo' => 'positivo', 'descripcion' => 'Más sociable'],
            ['nombre' => 'Hablador',            'tipo' => 'positivo', 'descripcion' => 'Ganas de conversar'],
            ['nombre' => 'Risueño',             'tipo' => 'positivo', 'descripcion' => 'Provoca risa'],
            ['nombre' => 'Inspirado',           'tipo' => 'positivo', 'descripcion' => 'Ideas fluidas'],
            ['nombre' => 'Motivado',            'tipo' => 'positivo', 'descripcion' => 'Impulsa a la acción'],
            ['nombre' => 'Productivo',          'tipo' => 'positivo', 'descripcion' => 'Rendimiento en tareas'],
            ['nombre' => 'Estimulante',         'tipo' => 'positivo', 'descripcion' => 'Activa los sentidos'],
            ['nombre' => 'Tranquilo',           'tipo' => 'positivo', 'descripcion' => 'Serenidad'],
            ['nombre' => 'Calmante',            'tipo' => 'positivo', 'descripcion' => 'Reduce la tensión'],
            ['nombre' => 'Sedante',             'tipo' => 'positivo', 'descripcion' => 'Muy relajante'],
            ['nombre' => 'Somnoliento',         'tipo' => 'positivo', 'descripcion' => 'Induce al sueño'],
            ['nombre' => 'Aumenta el apetito',  'tipo' => 'positivo', 'descripcion' => 'Da hambre (munchies)'],
            ['nombre' => 'Efecto cerebral',     'tipo' => 'positivo', 'descripcion' => 'Colocón mental (head high)'],
            ['nombre' => 'Efecto corporal',     'tipo' => 'positivo', 'descripcion' => 'Relajación física (body high)'],
            ['nombre' => 'Hormigueo',           'tipo' => 'positivo', 'descripcion' => 'Cosquilleo corporal'],
            ['nombre' => 'Meditativo',          'tipo' => 'positivo', 'descripcion' => 'Estado introspectivo'],
            ['nombre' => 'Alerta',              'tipo' => 'positivo', 'descripcion' => 'Despierto y atento'],
            ['nombre' => 'Alivio del dolor',    'tipo' => 'positivo', 'descripcion' => 'Analgésico'],
            ['nombre' => 'Antiestrés',          'tipo' => 'positivo', 'descripcion' => 'Reduce el estrés'],
            ['nombre' => 'Reduce la ansiedad',  'tipo' => 'positivo', 'descripcion' => 'Ansiolítico'],
            ['nombre' => 'Antiinflamatorio',    'tipo' => 'positivo', 'descripcion' => 'Reduce inflamación'],
            ['nombre' => 'Anti-náusea',         'tipo' => 'positivo', 'descripcion' => 'Calma el malestar'],
            // Negativos
            ['nombre' => 'Boca seca',           'tipo' => 'negativo', 'descripcion' => 'Sequedad bucal (cottonmouth)'],
            ['nombre' => 'Ojos secos',          'tipo' => 'negativo', 'descripcion' => 'Ojos rojos/secos'],
            ['nombre' => 'Mareo',               'tipo' => 'negativo', 'descripcion' => 'Sensación de mareo'],
            ['nombre' => 'Paranoia',            'tipo' => 'negativo', 'descripcion' => 'En dosis altas'],
            ['nombre' => 'Ansiedad',            'tipo' => 'negativo', 'descripcion' => 'Posible con dosis altas'],
            ['nombre' => 'Dolor de cabeza',     'tipo' => 'negativo', 'descripcion' => 'Cefalea ocasional'],
            ['nombre' => 'Letargo',             'tipo' => 'negativo', 'descripcion' => 'Pesadez / pereza'],
            ['nombre' => 'Sed',                 'tipo' => 'negativo', 'descripcion' => 'Aumento de sed'],
        ];

        $n = $this->insertarUnicos(Efecto::class, $efectos);
        $this->command?->info("Efectos nuevos: {$n}");
    }

    private function sembrarColores(): void
    {
        $colores = [
            ['nombre' => 'Verde',         'codigo_hex' => '#22c55e'],
            ['nombre' => 'Verde claro',   'codigo_hex' => '#86efac'],
            ['nombre' => 'Verde oscuro',  'codigo_hex' => '#15803d'],
            ['nombre' => 'Lima',          'codigo_hex' => '#84cc16'],
            ['nombre' => 'Esmeralda',     'codigo_hex' => '#10b981'],
            ['nombre' => 'Morado',        'codigo_hex' => '#8b5cf6'],
            ['nombre' => 'Púrpura',       'codigo_hex' => '#7c3aed'],
            ['nombre' => 'Lila',          'codigo_hex' => '#c4b5fd'],
            ['nombre' => 'Magenta',       'codigo_hex' => '#d946ef'],
            ['nombre' => 'Rosa',          'codigo_hex' => '#ec4899'],
            ['nombre' => 'Rojo',          'codigo_hex' => '#ef4444'],
            ['nombre' => 'Vino',          'codigo_hex' => '#7f1d1d'],
            ['nombre' => 'Naranja',       'codigo_hex' => '#f97316'],
            ['nombre' => 'Naranja oscuro','codigo_hex' => '#c2410c'],
            ['nombre' => 'Amarillo',      'codigo_hex' => '#eab308'],
            ['nombre' => 'Dorado',        'codigo_hex' => '#d4af37'],
            ['nombre' => 'Azul',          'codigo_hex' => '#3b82f6'],
            ['nombre' => 'Azul claro',    'codigo_hex' => '#93c5fd'],
            ['nombre' => 'Azul oscuro',   'codigo_hex' => '#1e3a8a'],
            ['nombre' => 'Cian',          'codigo_hex' => '#06b6d4'],
            ['nombre' => 'Turquesa',      'codigo_hex' => '#14b8a6'],
            ['nombre' => 'Marrón',        'codigo_hex' => '#92400e'],
            ['nombre' => 'Café',          'codigo_hex' => '#6f4e37'],
            ['nombre' => 'Beige',         'codigo_hex' => '#d9c8a9'],
            ['nombre' => 'Blanco',        'codigo_hex' => '#f8fafc'],
            ['nombre' => 'Gris',          'codigo_hex' => '#6b7280'],
            ['nombre' => 'Negro',         'codigo_hex' => '#111827'],
            ['nombre' => 'Plateado',      'codigo_hex' => '#c0c0c0'],
        ];

        $n = $this->insertarUnicos(Color::class, $colores);
        $this->command?->info("Colores nuevos: {$n}");
    }
}
