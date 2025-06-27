<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\DetalleVentaController;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('ventas', VentaController::class);
Route::resource('productos', ProductoController::class);
Route::resource('detalle_ventas', DetalleVentaController::class);