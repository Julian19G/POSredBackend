<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\DetalleVentaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ColorController;
use App\Http\Controllers\EfectoController;
use App\Http\Controllers\SaborController;
use App\Http\Controllers\DescuentoController;
use Illuminate\Support\Facades\DB;


Route::get('/', function () {
    return view('welcome');
});

Route::resource('ventas', VentaController::class);
Route::resource('productos', ProductoController::class);
Route::resource('detalle_ventas', DetalleVentaController::class);
Route::resource('clientes', ClienteController::class);
Route::resource('categorias', CategoriaController::class);
Route::resource('efectos', EfectoController::class);
Route::resource('descuentos', DescuentoController::class); 
Route::resource('colores', ColorController::class)->parameters([
    'colores' => 'color'
]);

Route::resource('sabores', SaborController::class)->parameters([
    'sabores' => 'sabor'
]);
Route::get('/pivotes', function() {

    // Traemos todos los productos
    $productos = DB::table('productos')->get();

    // Traemos los colores con el nombre y el HEX
    $producto_colores = DB::table('producto_color')
        ->join('colores', 'producto_color.color_id', '=', 'colores.id')
        ->select('producto_color.producto_id', 'colores.nombre as color_nombre', 'colores.codigo_hex')
        ->get();

    // Traemos los efectos con el nombre
    $producto_efectos = DB::table('producto_efecto')
        ->join('efectos', 'producto_efecto.efecto_id', '=', 'efectos.id')
        ->select('producto_efecto.producto_id', 'efectos.nombre as efecto_nombre')
        ->get();

    // Traemos los sabores con el nombre
    $producto_sabores = DB::table('producto_sabor')
        ->join('sabores', 'producto_sabor.sabor_id', '=', 'sabores.id')
        ->select('producto_sabor.producto_id', 'sabores.nombre as sabor_nombre')
        ->get();

    return view('pivotes.index', compact('productos', 'producto_colores', 'producto_efectos', 'producto_sabores'));

})->name('pivotes.index');
