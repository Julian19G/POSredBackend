<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Models\Venta;
use Illuminate\Http\Request;

class PagoController extends Controller
{
    /**
     * Mostrar lista de pagos.
     */
    public function index()
    {
        $pagos = Pago::with('venta')->paginate(10);
        return view('pagos.index', compact('pagos'));
    }

    /**
     * Formulario para crear un nuevo pago.
     */
    public function create()
    {
        $ventas = Venta::all(); // para seleccionar venta asociada
        return view('pagos.create', compact('ventas'));
    }

    /**
     * Guardar un nuevo pago en la base de datos.
     */
    public function store(Request $request)
    {
        $request->validate([
            'venta_id' => 'required|exists:ventas,id',
            'monto' => 'required|numeric|min:0',
            'metodo' => 'required|string|max:50',
            'estado' => 'required|in:pendiente,aprobado,rechazado',
            'fecha_pago' => 'nullable|date',
            'referencia' => 'nullable|string|max:255',
            'comentarios' => 'nullable|string',
        ]);

        Pago::create($request->all());

        return redirect()->route('pagos.index')
            ->with('success', 'Pago creado correctamente.');
    }

    /**
     * MOSTRAR UN PAGO (SHOW)
     */
    public function show($id)
    {
        $pago = Pago::with('venta')->findOrFail($id);
        return view('pagos.show', compact('pago'));
    }

    /**
     * Formulario para editar un pago.
     */
    public function edit($id)
    {
        $pago = Pago::findOrFail($id);
        $ventas = Venta::all();

        return view('pagos.edit', compact('pago', 'ventas'));
    }

    /**
     * Actualizar un pago.
     */
    public function update(Request $request, $id)
    {
        $pago = Pago::findOrFail($id);

        $request->validate([
            'venta_id' => 'required|exists:ventas,id',
            'monto' => 'required|numeric|min:0',
            'metodo' => 'required|string|max:50',
            'estado' => 'required|in:pendiente,aprobado,rechazado',
            'fecha_pago' => 'nullable|date',
            'referencia' => 'nullable|string|max:255',
            'comentarios' => 'nullable|string',
        ]);

        $pago->update($request->all());

        return redirect()->route('pagos.index')
            ->with('success', 'Pago actualizado correctamente.');
    }

    /**
     * Eliminar un pago.
     */
    public function destroy($id)
    {
        $pago = Pago::findOrFail($id);
        $pago->delete();

        return redirect()->route('pagos.index')
            ->with('success', 'Pago eliminado correctamente.');
    }
}
