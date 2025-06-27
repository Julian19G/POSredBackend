<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Producto;

class ProductoSeeder extends Seeder
{
    public function run(): void
    {
        $productos = [
            [
                'nombre' => 'Cholado x10g',
                'descripcion' => 'Sativa',
                'precio' => 35000,
                'stock' => 10,
            ],
            [
                'nombre' => 'Lemon x10g',
                'descripcion' => 'Híbrida',
                'precio' => 35000,
                'stock' => 10,
            ],
            [
                'nombre' => '11 rosas x10g',
                'descripcion' => 'Índica',
                'precio' => 35000,
                'stock' => 10,
            ],
        ];

        foreach ($productos as $producto) {
            Producto::create($producto);
        }
    }
}
