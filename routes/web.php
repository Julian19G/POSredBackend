<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\DetalleVentaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ColorController;
use App\Http\Controllers\EfectoController;
use App\Http\Controllers\SaborController;
use App\Http\Controllers\DescuentoController;
use App\Http\Controllers\DomicilioController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\VendedorController;
use App\Http\Controllers\LiquidacionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\DB;

// ── Auth (solo para invitados) ─────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login',    [LoginController::class, 'showForm'])->name('login');
    Route::post('/login',   [LoginController::class, 'login'])->name('login.post');

    Route::get('/register', [RegisterController::class, 'showForm'])->name('register');
    Route::post('/register',[RegisterController::class, 'register'])->name('register.post');

    Route::get('/forgot-password',  [ForgotPasswordController::class, 'showForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendLink'])->name('password.email');

    Route::get('/reset-password/{token}',  [ResetPasswordController::class, 'showForm'])->name('password.reset');
    Route::post('/reset-password',         [ResetPasswordController::class, 'resetPassword'])->name('password.update');
});

// ── Logout ─────────────────────────────────────────────────────
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// ── Rutas protegidas (solo usuarios autenticados) ──────────────
Route::middleware('auth')->group(function () {

    // Gestión de usuarios (admin)
    Route::resource('users', UserController::class)->except(['create', 'store']);

    // Perfil
    Route::get('/profile',           [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',         [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/password',[ProfileController::class, 'updatePassword'])->name('profile.password');

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Ventas
    Route::resource('ventas', VentaController::class);
    Route::get('/ventas/{id}/recibo', [VentaController::class, 'recibo'])->name('ventas.recibo');

    // Inventario (anidado bajo producto)
    Route::get('/productos/{producto}/inventario/create', [InventarioController::class, 'create'])->name('inventarios.create');
    Route::post('/productos/{producto}/inventario',       [InventarioController::class, 'store'])->name('inventarios.store');

    // Recursos estándar
    Route::resource('productos',      ProductoController::class);
    Route::resource('detalle_ventas', DetalleVentaController::class);
    Route::resource('clientes',       ClienteController::class);
    Route::resource('categorias',     CategoriaController::class);
    Route::resource('efectos',        EfectoController::class);
    Route::resource('descuentos',     DescuentoController::class);

    Route::resource('vendedores', VendedorController::class)->parameters(['vendedores' => 'vendedor']);
    Route::post('vendedores/{vendedor}/liquidaciones',                       [LiquidacionController::class, 'store'])->name('liquidaciones.store');
    Route::get('vendedores/{vendedor}/liquidaciones/{liquidacion}',          [LiquidacionController::class, 'show'])->name('liquidaciones.show');

    Route::resource('colores', ColorController::class)->parameters(['colores' => 'color']);
    Route::resource('sabores', SaborController::class)->parameters(['sabores' => 'sabor']);

    // Domicilios
    Route::get('domicilios/mapa', [DomicilioController::class, 'mapa'])->name('domicilios.mapa');
    Route::resource('domicilios', DomicilioController::class);

    // Pedidos
    Route::resource('pedidos', PedidoController::class)->only(['index', 'show']);
    Route::patch('pedidos/{pedido}/estado', [PedidoController::class, 'actualizarEstado'])->name('pedidos.estado');
    Route::patch('pedidos/{pedido}/pago',   [PedidoController::class, 'registrarPago'])->name('pedidos.pago');

    Route::post('pedidos/{pedido}/comprobante',
        [PedidoController::class, 'subirComprobante'])->name('pedidos.comprobante.subir');
    Route::patch('pedidos/{pedido}/comprobante/{comprobante}/verificar',
        [PedidoController::class, 'verificarComprobante'])->name('pedidos.comprobante.verificar');
    Route::patch('pedidos/{pedido}/comprobante/{comprobante}/rechazar',
        [PedidoController::class, 'rechazarComprobante'])->name('pedidos.comprobante.rechazar');

    // Debug pivotes
    Route::get('/pivotes', function () {
        $productos        = DB::table('productos')->get();
        $producto_colores = DB::table('producto_color')
            ->join('colores', 'producto_color.color_id', '=', 'colores.id')
            ->select('producto_color.producto_id', 'colores.nombre as color_nombre', 'colores.codigo_hex')
            ->get();
        $producto_efectos = DB::table('producto_efecto')
            ->join('efectos', 'producto_efecto.efecto_id', '=', 'efectos.id')
            ->select('producto_efecto.producto_id', 'efectos.nombre as efecto_nombre')
            ->get();
        $producto_sabores = DB::table('producto_sabor')
            ->join('sabores', 'producto_sabor.sabor_id', '=', 'sabores.id')
            ->select('producto_sabor.producto_id', 'sabores.nombre as sabor_nombre')
            ->get();
        return view('pivotes.index', compact('productos', 'producto_colores', 'producto_efectos', 'producto_sabores'));
    })->name('pivotes.index');

});
