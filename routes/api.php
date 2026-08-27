<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\Categoria;
use App\Models\Producto;
use App\Models\Resena;
use App\Models\Venta;
use App\Models\DetalleVenta;
use App\Models\Pedido;
use App\Models\Cliente;
use App\Models\ClienteDireccion;
use App\Models\TarifaDomicilio;
use App\Models\Domicilio;
use App\Models\Vendedor;
use App\Models\Comision;
use App\Models\Variante;
use App\Models\User;
use App\Notifications\NuevaVentaNotification;
use Illuminate\Support\Facades\Notification;

// ─── Categorías ────────────────────────────────────────────
Route::get('/categorias', function () {
    try {
        $categorias = Categoria::where('activa', true)
            ->orderBy('nombre')
            ->get()
            ->map(fn($cat) => [
                'id'          => $cat->id,
                'nombre'      => $cat->nombre,
                'descripcion' => $cat->descripcion,
                'slug'        => $cat->slug,
                'imagen'      => $cat->imagen ? asset('storage/' . $cat->imagen) : null,
            ]);

        return response()->json($categorias);
    } catch (\Exception $e) {
        return response()->json(['error' => 'No se pudieron cargar las categorías', 'detalle' => $e->getMessage()], 503);
    }
});

Route::get('/categorias/{id}/productos', function ($id) {
    try {
        $categoria = Categoria::findOrFail($id);

        $productos = Producto::with(['variantes', 'sabores', 'efectos', 'colores', 'tipoFlor'])
            ->where('categoria_id', $id)
            ->where('activo', true)
            ->orderBy('nombre')
            ->get()
            ->map(fn($prod) => [
                'id'          => $prod->id,
                'nombre'      => $prod->nombre,
                'descripcion' => $prod->descripcion,
                'stock'       => $prod->stock,
                'imagen'      => $prod->imagen ? asset('storage/' . $prod->imagen) : null,
                'tipo_flor'   => $prod->tipoFlor ? ['nombre' => $prod->tipoFlor->nombre, 'icono' => $prod->tipoFlor->icono] : null,
                'variantes'   => $prod->variantes
                    ->sortBy('precio')
                    ->values()
                    ->map(fn($v) => [
                        'id'                    => $v->id,
                        'nombre'                => $v->nombre,
                        'cantidad_por_variante' => $v->cantidad_por_variante,
                        'precio'                => (float) $v->precio,
                        'stock'                 => $v->stock,
                        'agotado'               => $v->stock <= 0,
                    ]),
                'sabores'     => $prod->sabores->pluck('nombre'),
                'efectos'     => $prod->efectos->pluck('nombre'),
                'colores'     => $prod->colores->map(fn($c) => [
                    'nombre'     => $c->nombre,
                    'codigo_hex' => $c->codigo_hex ?? null,
                ]),
            ]);

        return response()->json([
            'categoria' => [
                'id'          => $categoria->id,
                'nombre'      => $categoria->nombre,
                'descripcion' => $categoria->descripcion,
            ],
            'productos' => $productos,
        ]);
    } catch (\Exception $e) {
        return response()->json(['error' => 'No se pudieron cargar los productos', 'detalle' => $e->getMessage()], 503);
    }
});

// ─── Producto individual ───────────────────────────────────
Route::get('/productos/{id}', function ($id) {
    $prod = Producto::with(['variantes', 'sabores', 'efectos', 'colores', 'categoria', 'tipoFlor'])
        ->findOrFail($id);

    // Reseñas — defensivo: la tabla puede no existir aún
    $resenasData = ['promedio' => null, 'total' => 0, 'lista' => []];

    try {
        $lista = Resena::where('producto_id', $id)
            ->latest()
            ->limit(50)
            ->get(['id', 'nombre_cliente', 'calificacion', 'comentario', 'created_at']);

        $resenasData = [
            'promedio' => $lista->count() ? round($lista->avg('calificacion'), 1) : null,
            'total'    => $lista->count(),
            'lista'    => $lista,
        ];
    } catch (\Exception $e) {
        // tabla resenas aún no existe
    }

    return response()->json([
        'id'          => $prod->id,
        'nombre'      => $prod->nombre,
        'descripcion' => $prod->descripcion,
        'stock'       => $prod->stock,
        'imagen'      => $prod->imagen ? asset('storage/' . $prod->imagen) : null,
        'tipo_flor'   => $prod->tipoFlor ? ['nombre' => $prod->tipoFlor->nombre, 'icono' => $prod->tipoFlor->icono] : null,
        'variantes'   => $prod->variantes
            ->sortBy('precio')
            ->values()
            ->map(fn($v) => [
                'id'                    => $v->id,
                'nombre'                => $v->nombre,
                'cantidad_por_variante' => $v->cantidad_por_variante,
                'precio'                => (float) $v->precio,
                'stock'                 => $v->stock,
                'agotado'               => $v->stock <= 0,
            ]),
        'sabores'     => $prod->sabores->pluck('nombre'),
        'efectos'     => $prod->efectos->pluck('nombre'),
        'colores'     => $prod->colores->map(fn($c) => [
            'nombre'     => $c->nombre,
            'codigo_hex' => $c->codigo_hex ?? null,
        ]),
        'categoria'   => $prod->categoria ? [
            'id'     => $prod->categoria->id,
            'nombre' => $prod->categoria->nombre,
        ] : null,
        'resenas'     => $resenasData,
    ]);
});

// ─── Seguimiento de pedido (por token inadivinable, no por id secuencial) ───
Route::get('/pedidos/{token}', function ($token) {
    $pedido = Pedido::with(['venta.cliente', 'venta.detalles'])
        ->where('public_token', $token)
        ->firstOrFail();

    $metodos = [
        'efectivo'      => '💵 Efectivo',
        'transferencia' => '🏦 Transferencia',
        'cripto'        => '🪙 Cripto',
        'tarjeta'       => '💳 Tarjeta',
        'otro'          => '🔄 Otro',
    ];

    return response()->json([
        'id'                => $pedido->id,
        'estado'            => $pedido->estado,
        'estado_pago'       => $pedido->estado_pago,
        'metodo_pago'       => $pedido->metodo_pago,
        'metodo_pago_label' => $metodos[$pedido->metodo_pago] ?? $pedido->metodo_pago,
        'notas'             => $pedido->notas,
        'created_at'        => $pedido->created_at,
        'fecha_preparacion' => $pedido->fecha_preparacion,
        'fecha_despacho'    => $pedido->fecha_despacho,
        'fecha_entrega'     => $pedido->fecha_entrega,
        'fecha_cancelacion' => $pedido->fecha_cancelacion,
        'cliente'           => $pedido->venta?->cliente?->nombre,
        'total'             => $pedido->venta?->total ?? 0,
        'envio'             => $pedido->venta?->envio ?? false,
        'direccion_envio'   => $pedido->venta?->direccion_envio,
        'items'             => $pedido->venta?->detalles->map(fn($d) => [
            'nombre_producto' => $d->nombre_producto,
            'nombre_variante' => $d->nombre_variante,
            'cantidad'        => $d->cantidad,
            'precio_unitario' => (float) $d->precio_unitario,
            'subtotal'        => (float) $d->subtotal,
        ]) ?? [],
    ]);
});

// ─── Reseñas ───────────────────────────────────────────────
Route::post('/productos/{id}/resenas', function (Request $request, $id) {
    Producto::findOrFail($id);

    $data = $request->validate([
        'nombre_cliente' => 'required|string|max:100',
        'calificacion'   => 'required|integer|min:1|max:5',
        'comentario'     => 'nullable|string|max:1000',
    ]);

    $resena = Resena::create([...$data, 'producto_id' => $id]);

    return response()->json($resena, 201);
});

// ─── Checkout ──────────────────────────────────────────────
// ─── Referido de vendedor ──────────────────────────────────
// Valida el código del link /{codigo}/login y devuelve el vendedor.
Route::get('/ref/{codigo}', function ($codigo) {
    $vendedor = Vendedor::activos()->porCodigo($codigo)->first();

    if (!$vendedor) {
        return response()->json(['error' => 'Código de vendedor no válido'], 404);
    }

    return response()->json([
        'vendedor' => [
            'id'                 => $vendedor->id,
            'nombre'             => $vendedor->nombre,
            'codigo'             => $vendedor->codigo,
            'recargo_porcentaje' => (float) $vendedor->comision_porcentaje,
        ],
    ]);
});

// Calcula el precio real que se cobrará (recargo de vendedor + tarifa de
// domicilio vigente en BD) SIN crear el pedido, tocar stock ni el cliente.
// El frontend debe llamar esto antes de dejar confirmar el pago, para no
// mostrar nunca un total que luego no coincida con el que arma /checkout.
Route::post('/checkout/cotizar', function (Request $request) {
    $request->validate([
        'items'                => 'required|array|min:1',
        'items.*.variante_id'  => 'required|integer|exists:variantes,id',
        'items.*.cantidad'     => 'required|integer|min:1',
        'envio'                => 'boolean',
        'direccion'            => 'nullable|string|max:255',
        'barrio'               => 'nullable|string|max:100',
        'telefono'             => 'nullable|string|max:30',
        'codigo_vendedor'      => 'nullable|string|max:16',
    ]);

    // Misma prioridad de atribución que /checkout, pero de solo lectura:
    // vendedor ya asignado al cliente (si ya existe) tiene prioridad sobre
    // el vendedor del código actual en el link.
    $vendedorCodigo = $request->filled('codigo_vendedor')
        ? Vendedor::activos()->porCodigo($request->codigo_vendedor)->first()
        : null;

    $vendedor = $vendedorCodigo;
    if ($request->filled('telefono')) {
        $clienteExistente = Cliente::where('telefono', $request->telefono)->first();
        if ($clienteExistente && $clienteExistente->vendedor_id) {
            $vendedor = Vendedor::find($clienteExistente->vendedor_id) ?? $vendedorCodigo;
        }
    }

    $markup = ($vendedor && $vendedor->comision_porcentaje > 0)
        ? $vendedor->comision_porcentaje / 100
        : 0;

    // Precios SIEMPRE desde la variante real en BD, igual que en /checkout.
    $subtotalFinal = 0;
    $itemsCotizados = [];
    foreach ($request->items as $item) {
        $variante = Variante::find($item['variante_id']);
        if (!$variante) {
            abort(422, 'Uno de los productos de tu carrito ya no está disponible.');
        }
        $precioBase  = (float) $variante->precio;
        $cant        = (int) $item['cantidad'];
        $precioFinal = round($precioBase * (1 + $markup));
        $subtotalFinal += $precioFinal * $cant;

        // Precio real por producto: el frontend lo necesita para que la
        // lista de items no muestre un precio distinto al que se ve en el
        // subtotal (p.ej. cuando el recargo viene de un vendedor asignado
        // por teléfono en BD, que el frontend no puede calcular por su cuenta).
        $itemsCotizados[] = [
            'variante_id'     => $variante->id,
            'precio_unitario' => $precioFinal,
            'subtotal'        => $precioFinal * $cant,
        ];
    }

    $esEnvio    = $request->boolean('envio');
    $tarifa     = $esEnvio ? TarifaDomicilio::vigente() : null;
    $envioBase  = $tarifa ? (float) $tarifa->monto : 0;
    $envioFinal = round($envioBase * (1 + $markup));

    return response()->json([
        'items'           => $itemsCotizados,
        'subtotal'        => round($subtotalFinal, 0),
        'costo_domicilio' => $esEnvio ? $envioFinal : 0,
        // Ajusta 'nombre' al campo real de tu modelo TarifaDomicilio si se
        // llama distinto (p.ej. 'tipo' o 'etiqueta'). Si no existe, queda null
        // y el frontend simplemente no muestra el badge.
        'tarifa_nombre'   => $tarifa->nombre ?? $tarifa->tipo ?? null,
        'recargos'        => [],
        'total'           => round($subtotalFinal + ($esEnvio ? $envioFinal : 0), 0),
    ]);
});

Route::post('/checkout', function (Request $request) {
    $request->validate([
        'cliente.nombre'          => 'required|string|max:150',
        'cliente.telefono'        => 'required|string|max:30',
        'cliente.email'           => 'nullable|email|max:150',
        'cliente.direccion'       => 'nullable|string|max:255',
        'cliente.barrio'          => 'nullable|string|max:100',
        'items'                   => 'required|array|min:1',
        'items.*.producto_id'     => 'required|integer|exists:productos,id',
        'items.*.variante_id'     => 'required|integer|exists:variantes,id',
        'items.*.nombre_producto' => 'required|string|max:255',
        'items.*.nombre_variante' => 'nullable|string|max:100',
        'items.*.cantidad'        => 'required|integer|min:1',
        'items.*.precio_unitario' => 'required|numeric|min:0',
        'envio'                   => 'boolean',
        'metodo_pago'             => 'required|in:efectivo,transferencia,cripto,tarjeta,otro',
        'notas'                   => 'nullable|string|max:500',
        'codigo_vendedor'         => 'nullable|string|max:16',
        // Total que el cliente vio y aceptó explícitamente en /checkout/cotizar.
        // Si el total real cambia entre la cotización y este submit (p.ej. cruzó
        // el horario nocturno), rechazamos en vez de cobrar un precio distinto
        // al que el cliente confirmó.
        'total_aceptado'          => 'nullable|numeric',
    ]);

    return DB::transaction(function () use ($request) {
        $clienteData = $request->input('cliente');

        // Vendedor según el código del link (si viene en esta compra)
        $vendedorCodigo = $request->filled('codigo_vendedor')
            ? Vendedor::activos()->porCodigo($request->codigo_vendedor)->first()
            : null;

        $cliente = Cliente::firstOrCreate(
            ['telefono' => $clienteData['telefono']],
            [
                'nombre'      => $clienteData['nombre'],
                'email'       => $clienteData['email'] ?? null,
                'direccion'   => $clienteData['direccion'] ?? null,
                'barrio'      => $clienteData['barrio'] ?? null,
                'vendedor_id' => $vendedorCodigo?->id,
            ]
        );

        // Si el cliente ya existía sin referente y ahora llega con código, lo guardamos
        if ($vendedorCodigo && !$cliente->vendedor_id) {
            $cliente->update(['vendedor_id' => $vendedorCodigo->id]);
        }

        // Atribución: prioridad al vendedor referente del cliente; si no, el del código
        $vendedor = $cliente->vendedor_id
            ? Vendedor::find($cliente->vendedor_id)
            : $vendedorCodigo;

        // Recargo del referido: % del vendedor que se suma al precio (lo paga el cliente)
        $markup = ($vendedor && $vendedor->comision_porcentaje > 0)
            ? $vendedor->comision_porcentaje / 100
            : 0;

        // Recalcular precios SERVER-SIDE desde el precio real de la variante (no confiar en el front)
        $baseSubtotal  = 0;   // suma real sin recargo (base para la comisión)
        $subtotalFinal = 0;   // lo que paga el cliente (con recargo)
        $lineas = [];
        $necesidad = [];      // unidades base requeridas por producto
        foreach ($request->items as $item) {
            // Precio SIEMPRE desde la variante real (variante_id es obligatorio): no se confía en el front
            $variante    = Variante::find($item['variante_id']);
            $precioBase  = (float) ($variante->precio ?? 0);
            $cant        = (int) $item['cantidad'];
            $precioFinal = round($precioBase * (1 + $markup));

            $baseSubtotal  += $precioBase  * $cant;
            $subtotalFinal += $precioFinal * $cant;

            if ($variante) {
                $unidades = (float) $variante->cantidad_por_variante * $cant;
                $necesidad[$variante->producto_id] = ($necesidad[$variante->producto_id] ?? 0) + $unidades;
            }

            $lineas[] = $item + ['precio_final' => $precioFinal, 'cant' => $cant];
        }

        // Validar stock suficiente (en unidades base) antes de reservar
        $productos = Producto::whereIn('id', array_keys($necesidad))->get()->keyBy('id');
        foreach ($necesidad as $pid => $need) {
            $p = $productos[$pid] ?? null;
            if (!$p || (float) $p->stock < $need) {
                abort(422, 'No hay stock suficiente para "' . ($p->nombre ?? 'el producto') . '".');
            }
        }

        $esEnvio    = $request->boolean('envio');
        $tarifa     = $esEnvio ? TarifaDomicilio::vigente() : null;
        $envioBase  = $tarifa ? (float) $tarifa->monto : 0;
        $envioFinal = round($envioBase * (1 + $markup));   // el envío también lleva recargo

        // Si el cliente aceptó un total en /checkout/cotizar, verificamos que
        // el precio real (calculado ahora mismo) siga siendo el mismo. Si
        // cambió, no creamos el pedido: se lo devolvemos para que confirme
        // el nuevo total en vez de cobrarle algo distinto a lo que vio.
        $totalCalculado = round($subtotalFinal + $envioFinal, 0);
        if ($request->filled('total_aceptado') && abs($totalCalculado - (float) $request->total_aceptado) > 1) {
            abort(409, 'El total cambió desde que lo confirmaste. Revisa el nuevo total antes de continuar.');
        }

        $venta = Venta::create([
            'cliente_id'       => $cliente->id,
            'vendedor_id'      => $vendedor?->id,
            'subtotal'         => $subtotalFinal,
            'descuento_manual' => 0,
            'costo_envio'      => $envioFinal,
            'estado'           => 'pendiente',
            'envio'            => $esEnvio,
            'direccion_envio'  => $clienteData['direccion'] ?? null,
        ]);

        foreach ($lineas as $l) {
            DetalleVenta::create([
                'venta_id'           => $venta->id,
                'producto_id'        => $l['producto_id'],
                'variante_id'        => $l['variante_id'] ?? null,
                'nombre_producto'    => $l['nombre_producto'],
                'nombre_variante'    => $l['nombre_variante'] ?? null,
                'cantidad'           => $l['cant'],
                'precio_unitario'    => $l['precio_final'],
                'descuento_aplicado' => 0,
                'impuesto'           => 0,
                'subtotal'           => $l['precio_final'] * $l['cant'],
            ]);
        }

        // Reservar stock: descontar unidades base y recalcular disponibilidad de paquetes
        foreach ($necesidad as $pid => $need) {
            $p = $productos[$pid];
            $p->decrement('stock', $need);
            $p->load('variantes');
            $p->sincronizarStockPaquetes();
        }

        if ($esEnvio) {
            Domicilio::create([
                'venta_id'    => $venta->id,
                'direccion'   => $clienteData['direccion'] ?? '',
                'ciudad'      => $clienteData['barrio'] ?? '',
                'departamento'=> null,
                'pais'        => 'Colombia',
                'estado'      => 'pendiente',
                'costo_envio' => $envioFinal,
                'tarifa_id'   => $tarifa?->id,
                'tarifa_monto'=> $tarifa?->monto,
                'cobrar_en_entrega' => true,
            ]);
        }

        $pedido = Pedido::create([
            'venta_id'    => $venta->id,
            'estado'      => 'nuevo',
            'metodo_pago' => $request->metodo_pago,
            'estado_pago' => 'pendiente',
            'notas'       => $request->notas,
        ]);

        // Comisión del vendedor = SOLO el recargo de los productos.
        // El recargo del envío se lo queda la tienda (cubre logística/domicilios).
        if ($vendedor && $markup > 0) {
            $baseProductos    = round($baseSubtotal);
            $recargoProductos = round($subtotalFinal - $baseSubtotal); // markup solo de productos
            Comision::create([
                'vendedor_id'    => $vendedor->id,
                'venta_id'       => $venta->id,
                'monto_venta'    => $baseProductos,
                'porcentaje'     => $vendedor->comision_porcentaje,
                'monto_comision' => max($recargoProductos, 0),
                'estado'         => 'pendiente',
            ]);
        }

        // Notificar a los administradores: nueva venta del frontend
        $admins = User::where('role', 'admin')->get();
        if ($admins->isNotEmpty()) {
            Notification::send($admins, new NuevaVentaNotification($pedido));
        }

        return response()->json([
            'pedido_id'         => $pedido->id,            // número amigable para mostrar (#id)
            'seguimiento_token' => $pedido->public_token,  // token para la URL de seguimiento
            'total'             => $venta->total,
            'costo_envio'       => $envioFinal,
            'cliente'           => $cliente->nombre,
        ], 201);
    });
});

// ─── Auth de clientes ──────────────────────────────────────

Route::post('/clientes/register', function (Request $request) {
    $request->validate([
        'nombre'            => 'required|string|max:150',
        'telefono'          => 'required|string|max:30',
        'email'             => 'nullable|email|max:150|unique:clientes,email',
        'password'          => 'required|string|min:6|confirmed',
        'codigo_vendedor'   => 'nullable|string|max:16',
    ]);

    // Vendedor que refirió al cliente (si se registró por un link de referido)
    $vendedor = $request->filled('codigo_vendedor')
        ? Vendedor::activos()->porCodigo($request->codigo_vendedor)->first()
        : null;

    $cliente = Cliente::where('telefono', $request->telefono)->first();

    if ($cliente) {
        if ($cliente->password) {
            return response()->json(['message' => 'Ya existe una cuenta con este teléfono.'], 422);
        }
        $cliente->update([
            'nombre'      => $request->nombre,
            'email'       => $request->email ?? $cliente->email,
            'password'    => Hash::make($request->password),
            // No sobrescribimos un vendedor referente ya asignado
            'vendedor_id' => $cliente->vendedor_id ?? $vendedor?->id,
        ]);
    } else {
        $cliente = Cliente::create([
            'nombre'      => $request->nombre,
            'telefono'    => $request->telefono,
            'email'       => $request->email ?? null,
            'password'    => Hash::make($request->password),
            'vendedor_id' => $vendedor?->id,
        ]);
    }

    $token = $cliente->createToken('cliente-token', ['cliente'])->plainTextToken;

    return response()->json([
        'token'   => $token,
        'cliente' => ['id' => $cliente->id, 'nombre' => $cliente->nombre, 'email' => $cliente->email, 'telefono' => $cliente->telefono],
    ], 201);
});

Route::post('/clientes/login', function (Request $request) {
    $request->validate([
        'identificador' => 'required|string',
        'password'      => 'required|string',
    ]);

    $cliente = Cliente::where('email', $request->identificador)
        ->orWhere('telefono', $request->identificador)
        ->first();

    if (!$cliente || !$cliente->password || !Hash::check($request->password, $cliente->password)) {
        throw ValidationException::withMessages(['identificador' => 'Credenciales incorrectas.']);
    }

    $token = $cliente->createToken('cliente-token', ['cliente'])->plainTextToken;

    return response()->json([
        'token'   => $token,
        'cliente' => ['id' => $cliente->id, 'nombre' => $cliente->nombre, 'email' => $cliente->email, 'telefono' => $cliente->telefono],
    ]);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/clientes/logout', function (Request $request) {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['ok' => true]);
    });

    // ─── Notificaciones del cliente (poller del SPA) ───────────
    Route::get('/clientes/notificaciones', function (Request $request) {
        $cliente = $request->user();

        $items = $cliente->notifications()
            ->latest()
            ->limit(15)
            ->get()
            ->map(fn ($n) => [
                'id'    => $n->id,
                'leida' => $n->read_at !== null,
                'fecha' => $n->created_at->diffForHumans(),
                'data'  => $n->data,
            ]);

        return response()->json([
            'no_leidas' => $cliente->unreadNotifications()->count(),
            'ultima_id' => $items->first()['id'] ?? null,
            'items'     => $items,
        ]);
    });

    Route::post('/clientes/notificaciones/leer', function (Request $request) {
        $cliente = $request->user();
        if ($request->filled('id')) {
            $cliente->notifications()->where('id', $request->id)->update(['read_at' => now()]);
        } else {
            $cliente->unreadNotifications->markAsRead();
        }
        return response()->json(['ok' => true, 'no_leidas' => $cliente->unreadNotifications()->count()]);
    });

    Route::get('/clientes/me', function (Request $request) {
        $c = $request->user();

        // Vendedor ya asignado a este cliente en BD (puede venir de una
        // sesión/dispositivo distinto al actual, o de antes de que existiera
        // el sistema de referidos). El frontend usa esto para sincronizar el
        // recargo en localStorage apenas carga la sesión, sin depender de
        // que el cliente haya entrado por el link del vendedor en este
        // navegador.
        $vendedor = $c->vendedor_id ? Vendedor::find($c->vendedor_id) : null;

        return response()->json([
            'id'       => $c->id,
            'nombre'   => $c->nombre,
            'email'    => $c->email,
            'telefono' => $c->telefono,
            'direccion'=> $c->direccion,
            'barrio'   => $c->barrio,
            'vendedor' => ($vendedor && $vendedor->comision_porcentaje > 0) ? [
                'codigo'             => $vendedor->codigo,
                'nombre'             => $vendedor->nombre,
                'recargo_porcentaje' => (float) $vendedor->comision_porcentaje,
            ] : null,
        ]);
    });

    Route::get('/clientes/mis-pedidos', function (Request $request) {
        try {
            $cliente = $request->user();

            $ventaIds = Venta::where('cliente_id', $cliente->id)->pluck('id');
            if ($ventaIds->isEmpty()) return response()->json([]);

            $pedidos = Pedido::whereIn('venta_id', $ventaIds)
                ->orderByDesc('created_at')
                ->get();

            $ventas = Venta::whereIn('id', $ventaIds)->get()->keyBy('id');

            $detalles = \App\Models\DetalleVenta::whereIn('venta_id', $ventaIds)
                ->get()
                ->groupBy('venta_id');

            $resultado = $pedidos->map(fn($p) => [
                'id'                => $p->id,
                'seguimiento_token' => $p->public_token,
                'estado'      => $p->estado,
                'estado_pago' => $p->estado_pago,
                'metodo_pago' => $p->metodo_pago,
                'created_at'  => $p->created_at,
                'total'       => $ventas[$p->venta_id]?->total ?? 0,
                'envio'       => $ventas[$p->venta_id]?->envio ?? false,
                'items'       => ($detalles[$p->venta_id] ?? collect())->map(fn($d) => [
                    'nombre_producto' => $d->nombre_producto,
                    'nombre_variante' => $d->nombre_variante,
                    'cantidad'        => $d->cantidad,
                    'subtotal'        => (float) $d->subtotal,
                ])->values()->all(),
            ]);

            return response()->json($resultado);
        } catch (\Exception $e) {
            return response()->json([]);
        }
    });

    Route::get('/clientes/mis-direcciones', function (Request $request) {
        return response()->json($request->user()->direcciones()->orderByDesc('es_predeterminada')->get());
    });

    Route::post('/clientes/mis-direcciones', function (Request $request) {
        $request->validate([
            'etiqueta'  => 'nullable|string|max:50',
            'direccion' => 'required|string|max:255',
            'barrio'    => 'nullable|string|max:100',
            'es_predeterminada' => 'boolean',
        ]);

        $cliente = $request->user();

        if ($request->boolean('es_predeterminada')) {
            $cliente->direcciones()->update(['es_predeterminada' => false]);
        }

        $dir = $cliente->direcciones()->create([
            'etiqueta'          => $request->input('etiqueta', 'Casa'),
            'direccion'         => $request->direccion,
            'barrio'            => $request->barrio,
            'es_predeterminada' => $request->boolean('es_predeterminada'),
        ]);

        return response()->json($dir, 201);
    });

    Route::delete('/clientes/mis-direcciones/{id}', function (Request $request, $id) {
        $dir = $request->user()->direcciones()->findOrFail($id);
        $dir->delete();
        return response()->json(['ok' => true]);
    });

});
