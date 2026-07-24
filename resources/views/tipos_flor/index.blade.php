@extends('layouts.app')

@section('content')
<div class="container py-4" style="max-width:760px">

    <h1 class="mb-1">🌸 Tipos de flor</h1>
    <p class="text-muted">Etiquetas para la categoría <strong>Flores</strong> (Sativa, Índica, Indoor…). El emoji se muestra en la tienda.</p>

    {{-- Crear --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body">
            <form action="{{ route('tipos-flor.store') }}" method="POST" class="row g-2 align-items-end">
                @csrf
                <div class="col-md-5">
                    <label class="form-label small fw-semibold">Nombre</label>
                    <input type="text" name="nombre" class="form-control" placeholder="Ej: Sativa" value="{{ old('nombre') }}" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-semibold">Emoji</label>
                    <input type="text" name="icono" class="form-control text-center" placeholder="🌿" maxlength="16" value="{{ old('icono') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-semibold">Descripción</label>
                    <input type="text" name="descripcion" class="form-control" value="{{ old('descripcion') }}">
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
                    <tr><th class="ps-3">Tipo</th><th>Descripción</th><th class="text-end pe-3">Acción</th></tr>
                </thead>
                <tbody>
                    @forelse($tipos as $t)
                    <tr>
                        <td class="ps-3 fw-semibold">{{ $t->icono }} {{ $t->nombre }}</td>
                        <td class="text-muted small">{{ $t->descripcion ?? '—' }}</td>
                        <td class="text-end pe-3">
                            <form action="{{ route('tipos-flor.destroy', $t) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button class="btn btn-outline-danger btn-sm"
                                        data-confirm="¿Eliminar el tipo «{{ $t->nombre }}»?">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="text-center text-muted py-3">Sin tipos aún</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
