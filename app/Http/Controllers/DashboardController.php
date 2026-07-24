<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\Variante;
use App\Models\DetalleVenta;
use App\Models\Pedido;
use App\Models\Comision;
use App\Models\Domicilio;
use App\Models\TarifaDomicilio;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user         = auth()->user();
        $hoy          = Carbon::today();
        $inicioMes    = Carbon::now()->startOfMonth();
        $inicioSemana = Carbon::now()->startOfWeek();

        if ($user->isAdmin()) {
            return $this->admin($hoy, $inicioMes, $inicioSemana);
        }

        if ($user->isDomiciliario()) {
            return $this->domiciliario($user, $hoy, $inicioMes);
        }

        return $this->vendedor($user, $hoy, $inicioMes, $inicioSemana);
    }

    // ─────────────────────────────────────────────────────────────
    //  ADMIN: vista global de todo el negocio
    // ─────────────────────────────────────────────────────────────
    private function admin($hoy, $inicioMes, $inicioSemana)
    {
        $ventasHoy    = Venta::whereDate('created_at', $hoy)->count();
        $ventasSemana = Venta::where('created_at', '>=', $inicioSemana)->count();
        $ventasMes    = Venta::where('created_at', '>=', $inicioMes)->count();

        $ingresosMes    = Venta::where('created_at', '>=', $inicioMes)->where('estado', 'pagada')->sum('total');
        $pendienteCobro = Venta::where('estado', 'pendiente')->sum('total');

        $pedidosPendientes = Pedido::whereIn('estado', ['nuevo', 'en_preparacion', 'despachado'])->count();

        $comisionesTotales = Comision::where('estado', 'pendiente')->sum('monto_comision');

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

        $ventasRecientes = Venta::with(['cliente', 'vendedor', 'pedido'])
            ->latest()->limit(8)->get();

        return view('dashboard', [
            'esAdmin'           => true,
            'ventasHoy'         => $ventasHoy,
            'ventasSemana'      => $ventasSemana,
            'ventasMes'         => $ventasMes,
            'ingresosMes'       => $ingresosMes,
            'pendienteCobro'    => $pendienteCobro,
            'pedidosPendientes' => $pedidosPendientes,
            'comisionesTotales' => $comisionesTotales,
            'topProductos'      => $topProductos,
            'stockBajo'         => $stockBajo,
            'ventasRecientes'   => $ventasRecientes,
        ]);
    }

    // ─────────────────────────────────────────────────────────────
    //  DOMICILIARIO: sus rutas y entregas
    // ─────────────────────────────────────────────────────────────
    private function domiciliario($user, $hoy, $inicioMes)
    {
        $domiciliario = $user->domiciliario;

        if (!$domiciliario) {
            return view('dashboard', ['esDomiciliario' => true, 'sinDomiciliario' => true]);
        }

        $rutaActiva       = $domiciliario->rutaActiva();
        $entregasHoy      = $domiciliario->entregasHoy();
        $entregasMes      = $domiciliario->entregasMes();
        $gananciaHoy      = $domiciliario->gananciaHoy();
        $gananciaMes      = $domiciliario->gananciaMes();
        $gananciaTotal    = $domiciliario->gananciaTotal();
        $misActivos       = $domiciliario->domiciliosActivos();
        $disponiblesCount = Domicilio::disponibles()->count();
        $tarifaVigente    = TarifaDomicilio::vigente();

        return view('dashboard', [
            'esDomiciliario'   => true,
            'sinDomiciliario'  => false,
            'domiciliario'     => $domiciliario,
            'rutaActiva'       => $rutaActiva,
            'entregasHoy'      => $entregasHoy,
            'entregasMes'      => $entregasMes,
            'gananciaHoy'      => $gananciaHoy,
            'gananciaMes'      => $gananciaMes,
            'gananciaTotal'    => $gananciaTotal,
            'misActivos'       => $misActivos,
            'disponiblesCount' => $disponiblesCount,
            'tarifaVigente'    => $tarifaVigente,
        ]);
    }

    // ─────────────────────────────────────────────────────────────
    //  VENDEDOR: solo sus propios datos
    // ─────────────────────────────────────────────────────────────
    private function vendedor($user, $hoy, $inicioMes, $inicioSemana)
    {
        $vendedor = $user->vendedor;

        if (!$vendedor) {
            return view('dashboard', ['esAdmin' => false, 'sinVendedor' => true]);
        }

        $vid = $vendedor->id;

        $ventasHoy    = Venta::whereDate('created_at', $hoy)->where('vendedor_id', $vid)->count();
        $ventasSemana = Venta::where('created_at', '>=', $inicioSemana)->where('vendedor_id', $vid)->count();
        $ventasMes    = Venta::where('created_at', '>=', $inicioMes)->where('vendedor_id', $vid)->count();

        $ingresosMes    = Venta::where('created_at', '>=', $inicioMes)
                               ->where('estado', 'pagada')
                               ->where('vendedor_id', $vid)->sum('total');

        $pendienteCobro = Venta::where('estado', 'pendiente')
                               ->where('vendedor_id', $vid)->sum('total');

        $pedidosActivos = Pedido::whereIn('estado', ['nuevo', 'en_preparacion', 'despachado'])
                                ->whereHas('venta', fn($q) => $q->where('vendedor_id', $vid))
                                ->count();

        $comisionPendiente = $vendedor->saldoPendiente();
        $comisionCobrada   = $vendedor->totalPagado();
        $comisionTotal     = $vendedor->totalComisionado();

        $ventasRecientes = Venta::with(['cliente', 'pedido'])
                                ->where('vendedor_id', $vid)
                                ->latest()->limit(8)->get();

        $topProductos = DetalleVenta::select(
                'nombre_producto',
                DB::raw('SUM(cantidad) as total_vendido'),
                DB::raw('SUM(subtotal) as total_ingresos')
            )
            ->whereIn('venta_id', Venta::where('vendedor_id', $vid)->pluck('id'))
            ->groupBy('nombre_producto')
            ->orderByDesc('total_vendido')
            ->limit(5)
            ->get();

        $ultimasComisiones = Comision::where('vendedor_id', $vid)
                                     ->latest()->limit(5)->get();

        return view('dashboard', [
            'esAdmin'            => false,
            'sinVendedor'        => false,
            'vendedor'           => $vendedor,
            'ventasHoy'          => $ventasHoy,
            'ventasSemana'       => $ventasSemana,
            'ventasMes'          => $ventasMes,
            'ingresosMes'        => $ingresosMes,
            'pendienteCobro'     => $pendienteCobro,
            'pedidosActivos'     => $pedidosActivos,
            'comisionPendiente'  => $comisionPendiente,
            'comisionCobrada'    => $comisionCobrada,
            'comisionTotal'      => $comisionTotal,
            'ventasRecientes'    => $ventasRecientes,
            'topProductos'       => $topProductos,
            'ultimasComisiones'  => $ultimasComisiones,
        ]);
    }
}
