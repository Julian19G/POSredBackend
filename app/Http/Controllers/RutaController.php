<?php

namespace App\Http\Controllers;

use App\Models\Domicilio;
use App\Models\Domiciliario;
use App\Models\Pago;
use App\Models\Ruta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RutaController extends Controller
{
    private function miDomiciliario(): ?Domiciliario
    {
        $user = auth()->user();
        if ($user->isDomiciliario()) {
            return $user->domiciliario;
        }
        return null;
    }

    // Lista de domicilios disponibles para tomar
    public function disponibles()
    {
        abort_unless(
            auth()->user()->isDomiciliario() || auth()->user()->isAdmin(),
            403
        );

        $domicilios = Domicilio::with(['venta.cliente', 'zona'])
            ->disponibles()
            ->latest()
            ->get();

        $tarifa = \App\Models\TarifaDomicilio::vigente();

        return view('rutas.disponibles', compact('domicilios', 'tarifa'));
    }

    // Mis rutas (domiciliario) o todas las rutas (admin)
    public function index()
    {
        $user = auth()->user();
        abort_unless($user->isDomiciliario() || $user->isAdmin(), 403);

        if ($user->isAdmin()) {
            $rutas = Ruta::with(['domiciliario', 'domicilios.venta.cliente'])
                ->latest()->paginate(20);
        } else {
            $domiciliario = $this->miDomiciliario();
            abort_if(!$domiciliario, 403, 'No tienes perfil de domiciliario.');

            $rutas = Ruta::with(['domicilios.venta.cliente'])
                ->where('domiciliario_id', $domiciliario->id)
                ->latest()->paginate(20);
        }

        return view('rutas.index', compact('rutas'));
    }

    // Crear ruta con domicilios seleccionados
    public function store(Request $request)
    {
        $domiciliario = $this->miDomiciliario();
        abort_if(!$domiciliario, 403, 'Solo los domiciliarios pueden crear rutas.');

        $request->validate([
            'domicilio_ids'   => 'required|array|min:1|max:10',
            'domicilio_ids.*' => 'required|integer|exists:domicilios,id',
            'tipo'            => 'nullable|in:ruta,express',
        ]);

        $ids  = $request->domicilio_ids;
        $tipo = $request->tipo ?? (count($ids) === 1 ? 'express' : 'ruta');

        // Express solo toma 1
        if ($tipo === 'express' && count($ids) > 1) {
            $ids = [$ids[0]];
        }

        $ruta = DB::transaction(function () use ($domiciliario, $ids, $tipo) {
            $ruta = Ruta::create([
                'domiciliario_id' => $domiciliario->id,
                'tipo'            => $tipo,
                'estado'          => 'activa',
            ]);

            $updated = Domicilio::whereIn('id', $ids)
                ->where('estado', 'pendiente')
                ->whereNull('domiciliario_id')
                ->update([
                    'domiciliario_id'  => $domiciliario->id,
                    'ruta_id'          => $ruta->id,
                    'tipo'             => $tipo === 'express' ? 'express' : 'normal',
                    'estado'           => 'aceptado',
                    'fecha_aceptacion' => now(),
                ]);

            if ($updated === 0) {
                throw new \Exception('Uno o más domicilios ya no están disponibles. Recarga la página.');
            }

            return $ruta;
        });

        $msg = $tipo === 'express'
            ? '⚡ Domicilio Express aceptado. ¡A rodar!'
            : '🗺 Ruta creada con ' . count($ids) . ' domicilio(s). ¡Éxito!';

        return redirect()->route('rutas.show', $ruta)->with('success', $msg);
    }

    // Detalle de ruta
    public function show($id)
    {
        $ruta = Ruta::with([
            'domiciliario',
            'domicilios.venta.cliente',
            'domicilios.zona',
        ])->findOrFail($id);

        $domiciliario = $this->miDomiciliario();
        if ($domiciliario && $ruta->domiciliario_id !== $domiciliario->id) {
            abort(403);
        }

        return view('rutas.show', compact('ruta'));
    }

    // Marcar domicilio como recogido (aceptado → en_camino)
    public function recoger(Request $request, $domicilioId)
    {
        $domicilio    = Domicilio::findOrFail($domicilioId);
        $domiciliario = $this->miDomiciliario();

        if ($domiciliario && $domicilio->domiciliario_id !== $domiciliario->id) {
            abort(403);
        }

        if ($domicilio->estado !== 'aceptado') {
            return back()->withErrors(['error' => 'El domicilio no está en estado aceptado.']);
        }

        $domicilio->update([
            'estado'         => 'en_camino',
            'fecha_recogida' => now(),
        ]);

        return back()->with('success', '📦 Paquete recogido. En camino al cliente.');
    }

    // Marcar domicilio como entregado (en_camino → entregado)
    public function entregar(Request $request, $domicilioId)
    {
        $request->validate([
            'metodo_cobro'  => 'nullable|in:efectivo,transferencia,cripto,tarjeta,otro',
            'monto_cobrado' => 'nullable|numeric|min:0',
        ]);

        $domicilio    = Domicilio::with(['venta.pedido', 'ruta'])->findOrFail($domicilioId);
        $domiciliario = $this->miDomiciliario();

        if ($domiciliario && $domicilio->domiciliario_id !== $domiciliario->id) {
            abort(403);
        }

        if ($domicilio->estado !== 'en_camino') {
            return back()->withErrors(['error' => 'El domicilio no está en camino.']);
        }

        DB::transaction(function () use ($domicilio, $request) {
            $domicilio->update([
                'estado'             => 'entregado',
                'fecha_entrega_real' => now(),
            ]);

            // Registrar cobro si la venta no estaba pagada
            $venta = $domicilio->venta;
            if ($venta && $venta->estado !== 'pagada' && $request->filled('metodo_cobro')) {
                $monto = $request->filled('monto_cobrado')
                    ? (float) $request->monto_cobrado
                    : ($domicilio->monto_cobrar ?? $venta->total);

                Pago::create([
                    'venta_id'       => $venta->id,
                    'registrado_por' => auth()->id(),
                    'monto'          => $monto,
                    'metodo'         => $request->metodo_cobro,
                    'estado'         => 'confirmado',
                    'fecha_pago'     => now(),
                    'referencia'     => 'Cobro en entrega — Domicilio #' . $domicilio->id,
                ]);

                $venta->update(['estado' => 'pagada']);

                if ($venta->pedido) {
                    $venta->pedido->update([
                        'estado_pago' => 'pagado',
                        'metodo_pago' => $request->metodo_cobro,
                        'estado'      => 'entregado',
                        'fecha_entrega' => now(),
                    ]);
                }
            }

            // Cerrar ruta si todos los domicilios están listos
            $ruta = $domicilio->ruta;
            if ($ruta && $ruta->completada()) {
                $ruta->update([
                    'estado'           => 'completada',
                    'fecha_completada' => now(),
                ]);
            }
        });

        return back()->with('success', '✅ ¡Domicilio entregado exitosamente!');
    }
}
