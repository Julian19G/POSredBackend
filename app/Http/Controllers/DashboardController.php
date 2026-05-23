<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\Variante;
use App\Models\DetalleVenta;
use App\Models\Pedido;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $hoy          = Carbon::today();
        $inicioMes    = Carbon::now()->startOfMonth();
        $inicioSemana = Carbon::now()->startOfWeek();

        $ventasHoy    = Venta::whereDate('created_at', $hoy)->count();
        $ventasSemana = Venta::where('created_at', '>=', $inicioSemana)->count();
        $ventasMes    = Venta::where('created_at', '>=', $inicioMes)->count();

        $ingresosHoy  = Venta::whereDate('created_at', $hoy)
                            ->where('estado', 'pagada')->sum('total');
        $ingresosMes  = Venta::where('created_at', '>=', $inicioMes)
                            ->where('estado', 'pagada')->sum('total');
        $pendienteCobro = Venta::where('estado', 'pendiente')->sum('total');

        $topProductos = DetalleVenta::select(
                'nombre_producto',
                DB::raw('SUM(cantidad) as total_vendido'),
                DB::raw('SUM(subtotal) as total_ingresos')
            )
            ->groupBy('nombre_producto')
            ->orderByDesc('total_vendido')
            ->limit(5)
            ->get();

        $stockBajo = Variante::with('producto')
            ->where('stock', '<=', 5)
            ->orderBy('stock')
            ->limit(10)
            ->get();

        $pedidosPendientes = Pedido::whereIn('estado', ['nuevo', 'en_preparacion'])->count();

        $ventasRecientes = Venta::with(['cliente', 'pedido'])
            ->latest()
            ->limit(8)
            ->get();

        return view('dashboard', compact(
            'ventasHoy', 'ventasSemana', 'ventasMes',
            'ingresosHoy', 'ingresosMes', 'pendienteCobro',
            'topProductos', 'stockBajo',
            'pedidosPendientes', 'ventasRecientes'
        ));
    }
}
