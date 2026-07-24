@extends('layouts.app')

@section('content')
<div class="container py-4" style="max-width:760px">

    <h1 class="mb-1">📦 Presentaciones</h1>
    <p class="text-muted">Define una vez los paquetes (x10, x20…) y reutilízalos al crear productos. La <strong>cantidad</strong> son las unidades base que consume cada paquete.</p>

    {{-- Crear --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body">
            <form action="{{ route('presentaciones.store') }}" method="POST" class="row g-2 align-items-end">
                @csrf
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">Nombre</label>
                    <input type="text" name="nombre" class="form-control" placeholder="Ej: Paquete x10" value="{{ old('nombre') }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-semibold">Cantidad (unidades)</label>
                    <input type="number" name="cantidad" class="form-control" step="any" min="0.01" placeholder="10 o 0.5" value="{{ old('cantidad') }}" required>
                </div>
                <div class="col-md-2 d-grid">
                    <button class="btn btn-primary">Agregar</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Lista --}}
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <table class="table mb-0 align-middle">
                <thead class="table-light">
                    <tr><th class="ps-3">Nombre</th><th>Cantidad</th><th class="text-end pe-3">Acción</th></tr>
                </thead>
                <tbody>
                    @forelse($presentaciones as $p)
                    <tr>
                        <td class="ps-3 fw-semibold">{{ $p->nombre }}</td>
                        <td>{{ $p->cantidad }} uds</td>
                        <td class="text-end pe-3">
                            <form action="{{ route('presentaciones.destroy', $p) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button class="btn btn-outline-danger btn-sm"
                                        data-confirm="¿Eliminar la presentación «{{ $p->nombre }}»?">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="text-center text-muted py-3">Sin presentaciones aún</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
