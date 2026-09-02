<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\Variante;
use App\Models\Producto;
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

        if ($user->isDomiciliario() && !$user->vendedor) {
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

        $domiciliosMes = Domicilio::where('created_at', '>=', $inicioMes);
        $domiciliosEntregadosMes = (clone $domiciliosMes)->where('estado', 'entregado');
        $generadoDomiciliariosMes = (float) $domiciliosEntregadosMes->sum('tarifa_monto');
        $totalCobradoDomiciliosMes = (float) (clone $domiciliosEntregadosMes)->sum('costo_envio');
        $domiciliosExternosMes = (clone $domiciliosMes)->where('origen', 'externa')->count();

        $ventasBrutasHoy = (float) Venta::whereDate('created_at', $hoy)->sum('total');
        $ventasBrutasMes = (float) Venta::where('created_at', '>=', $inicioMes)->sum('total');
        $pagosConfirmadosMes = (float) DB::table('pagos')
            ->where('estado', 'confirmado')->where('created_at', '>=', $inicioMes)->sum('monto');

        $chartLabels = [];
        $chartVentas = [];
        $chartDomicilios = [];
        for ($date = Carbon::today()->subDays(13); $date <= Carbon::today(); $date->addDay()) {
            $label = $date->format('d/m');
            $chartLabels[] = $label;
            $chartVentas[] = (float) Venta::whereDate('created_at', $date)->sum('total');
            $chartDomicilios[] = (float) Domicilio::whereDate('fecha_entrega_real', $date)->where('estado', 'entregado')->sum('tarifa_monto');
        }

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
            'pedidosPendientes'   => $pedidosPendientes,
            'comisionesTotales'   => $comisionesTotales,
            'ventasBrutasHoy'    => $ventasBrutasHoy,
            'ventasBrutasMes'    => $ventasBrutasMes,
            'pagosConfirmadosMes' => $pagosConfirmadosMes,
            'totalCobradoDomiciliosMes' => $totalCobradoDomiciliosMes,
            'generadoDomiciliariosMes' => $generadoDomiciliariosMes,
            'gananciaNetaDomiciliosMes' => $totalCobradoDomiciliosMes - $generadoDomiciliariosMes,
            'domiciliosExternosMes' => $domiciliosExternosMes,
            'domiciliosPendientes' => Domicilio::where('estado', 'pendiente')->count(),
            'domiciliosEnCamino' => Domicilio::where('estado', 'en_camino')->count(),
            'domiciliosEntregadosHoy' => Domicilio::where('estado', 'entregado')->whereDate('fecha_entrega_real', $hoy)->count(),
            'productosAgotados' => Producto::where('stock', '<=', 0)->count(),
            'chartLabels' => $chartLabels,
            'chartVentas' => $chartVentas,
            'chartDomicilios' => $chartDomicilios,
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
        $aceptadosCount = $domiciliario->domicilios()->where('estado', 'aceptado')->count();
        $enCaminoCount = $domiciliario->domicilios()->where('estado', 'en_camino')->count();
        $externosRealizados = $domiciliario->domicilios()->where('origen', 'externa')->where('estado', 'entregado')->count();
        $historialEntregas = $domiciliario->domicilios()->where('estado', 'entregado')->with('venta.cliente')->latest('fecha_entrega_real')->limit(8)->get();
        $tarifaVigente    = TarifaDomicilio::vigente();
        $chartLabels = [];
        $chartVentas = [];
        $chartDomicilios = [];
        for ($date = Carbon::today()->subDays(13); $date <= Carbon::today(); $date->addDay()) {
            $chartLabels[] = $date->format('d/m');
            $chartVentas[] = 0;
            $chartDomicilios[] = (float) $domiciliario->domicilios()
                ->where('estado', 'entregado')->whereDate('fecha_entrega_real', $date)->sum('tarifa_monto');
        }

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
            'aceptadosCount' => $aceptadosCount,
            'enCaminoCount' => $enCaminoCount,
            'externosRealizados' => $externosRealizados,
            'historialEntregas' => $historialEntregas,
            'tarifaVigente'    => $tarifaVigente,
            'chartLabels' => $chartLabels,
            'chartVentas' => $chartVentas,
            'chartDomicilios' => $chartDomicilios,
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

        $domiciliario = $user->domiciliario;
        $gananciaDomiciliosMes = $domiciliario?->gananciaMes() ?? 0;
        $gananciaDomiciliosTotal = $domiciliario?->gananciaTotal() ?? 0;
        $entregasDomiciliosMes = $domiciliario?->entregasMes() ?? 0;
        $entregasDomiciliosTotal = $domiciliario?->domicilios()->where('estado', 'entregado')->count() ?? 0;
        $chartLabels = [];
        $chartVentas = [];
        $chartDomicilios = [];
        for ($date = Carbon::today()->subDays(13); $date <= Carbon::today(); $date->addDay()) {
            $chartLabels[] = $date->format('d/m');
            $chartVentas[] = (float) Venta::where('vendedor_id', $vid)->whereDate('created_at', $date)->sum('total');
            $chartDomicilios[] = (float) ($domiciliario?->domicilios()->where('estado', 'entregado')->whereDate('fecha_entrega_real', $date)->sum('tarifa_monto') ?? 0);
        }

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
            'tieneDomiciliario'  => (bool) $domiciliario,
            'gananciaDomiciliosMes' => $gananciaDomiciliosMes,
            'gananciaDomiciliosTotal' => $gananciaDomiciliosTotal,
            'entregasDomiciliosMes' => $entregasDomiciliosMes,
            'entregasDomiciliosTotal' => $entregasDomiciliosTotal,
            'chartLabels' => $chartLabels,
            'chartVentas' => $chartVentas,
            'chartDomicilios' => $chartDomicilios,
        ]);
    }
}
