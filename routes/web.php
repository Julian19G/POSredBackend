<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\DetalleVentaController;
use App\Http\Controllers\ClienteController;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('ventas', VentaController::class);
Route::resource('productos', ProductoController::class);
Route::resource('detalle_ventas', DetalleVentaController::class);
Route::resource('clientes', ClienteController::class);
