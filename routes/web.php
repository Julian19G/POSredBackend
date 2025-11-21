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


Route::get('/', function () {
    return view('welcome');
});

Route::resource('ventas', VentaController::class);
Route::resource('productos', ProductoController::class);
Route::resource('detalle_ventas', DetalleVentaController::class);
Route::resource('clientes', ClienteController::class);
Route::resource('categorias', CategoriaController::class);
Route::resource('efectos', EfectoController::class);
Route::resource('colores', ColorController::class)->parameters([
    'colores' => 'color'
]);

Route::resource('sabores', SaborController::class)->parameters([
    'sabores' => 'sabor'
]);
