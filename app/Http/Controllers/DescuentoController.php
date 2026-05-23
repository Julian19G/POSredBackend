<?php

    namespace App\Http\Controllers;

    use App\Models\Descuento;
    use Illuminate\Http\Request;

    class DescuentoController extends Controller
    {
        /**
         * Mostrar todos los descuentos.
         */
        public function index()
        {
            $descuentos = Descuento::orderBy('created_at', 'desc')->paginate(15);

            return view('descuentos.index', compact('descuentos'));
        }

        /**
         * Vista para crear un nuevo descuento.
         */
        public function create()
        {
            return view('descuentos.create');
        }

        /**
         * Guardar un nuevo descuento.
         */
        public function store(Request $request)
        {
            $validated = $request->validate([
                'nombre'             => 'required|string|max:255',
                'codigo'             => 'required|string|max:50|unique:descuentos,codigo',
                'tipo'               => 'required|in:porcentaje,fijo',
                'valor'              => 'required|numeric|min:0',
                'fecha_inicio'       => 'required|date',
                'fecha_fin'          => 'required|date|after_or_equal:fecha_inicio',
                'activo'             => 'nullable|boolean',
                'uso_maximo'         => 'nullable|integer|min:1',
                'uso_cliente_maximo' => 'nullable|integer|min:1',
            ]);

            $validated['activo']           = $request->has('activo');
            $validated['aplicable_manual'] = $request->has('aplicable_manual');

            Descuento::create($validated);

            return redirect()
                ->route('descuentos.index')
                ->with('success', 'Descuento creado correctamente.');
        }

        /**
         * Mostrar un descuento específico.
         */
        public function show($id)
        {
            $descuento = Descuento::findOrFail($id);

            return view('descuentos.show', compact('descuento'));
        }

        /**
         * Vista para editar.
         */
        public function edit($id)
        {
            $descuento = Descuento::findOrFail($id);

            return view('descuentos.edit', compact('descuento'));
        }

        /**
         * Actualizar un descuento.
         */
        public function update(Request $request, $id)
        {
            $descuento = Descuento::findOrFail($id);

            $validated = $request->validate([
                'nombre'             => 'required|string|max:255',
                'codigo'             => 'required|string|max:50|unique:descuentos,codigo,' . $id,
                'tipo'               => 'required|in:porcentaje,fijo',
                'valor'              => 'required|numeric|min:0',
                'fecha_inicio'       => 'required|date',
                'fecha_fin'          => 'required|date|after_or_equal:fecha_inicio',
                'activo'             => 'nullable|boolean',
                'uso_maximo'         => 'nullable|integer|min:1',
                'uso_cliente_maximo' => 'nullable|integer|min:1',
            ]);

            $validated['activo']           = $request->has('activo');
            $validated['aplicable_manual'] = $request->has('aplicable_manual');

            $descuento->update($validated);

            return redirect()
                ->route('descuentos.index')
                ->with('success', 'Descuento actualizado correctamente.');
        }

        /**
         * Eliminar descuento.
         */
        public function destroy($id)
        {
            $descuento = Descuento::findOrFail($id);
            $descuento->delete();

            return redirect()
                ->route('descuentos.index')
                ->with('success', 'Descuento eliminado correctamente.');
        }
    }
