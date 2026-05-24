@extends('layouts.app')

@section('content')
<div class="container py-4" style="max-width:540px">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0 fs-3">Editar usuario</h1>
        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary btn-sm">← Volver</a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">

            {{-- Info fija --}}
            <div class="mb-4 p-3 bg-light rounded-3">
                <div class="fw-bold">{{ $user->name }}</div>
                <div class="text-muted small">{{ $user->email }}</div>
                <div class="text-muted small">Registrado: {{ $user->created_at->format('d/m/Y') }}</div>
            </div>

            <form action="{{ route('users.update', $user) }}" method="POST">
                @csrf @method('PUT')

                {{-- Rol --}}
                <div class="mb-4">
                    <label class="form-label fw-semibold">Rol en el sistema</label>
                    <div class="d-flex gap-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="role" id="role_admin"
                                   value="admin" {{ old('role', $user->role) === 'admin' ? 'checked' : '' }}>
                            <label class="form-check-label" for="role_admin">
                                <span class="badge bg-danger">Admin</span>
                                <small class="text-muted d-block">Acceso completo + gestión de usuarios</small>
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="role" id="role_vendedor"
                                   value="vendedor" {{ old('role', $user->role) === 'vendedor' ? 'checked' : '' }}>
                            <label class="form-check-label" for="role_vendedor">
                                <span class="badge bg-info text-dark">Vendedor</span>
                                <small class="text-muted d-block">Acceso al sistema POS</small>
                            </label>
                        </div>
                    </div>
                </div>

                {{-- Vinculación a vendedor --}}
                <div class="mb-4">
                    <label class="form-label fw-semibold">Vincular a perfil de vendedor</label>
                    <select name="vendedor_id" class="form-select">
                        <option value="">— Sin vincular —</option>
                        @foreach($vendedoresDisponibles as $v)
                            <option value="{{ $v->id }}"
                                {{ old('vendedor_id', $vendedorActual?->id) == $v->id ? 'selected' : '' }}>
                                {{ $v->nombre }}
                                @if($v->comision_porcentaje > 0) ({{ $v->comision_porcentaje }}%) @endif
                            </option>
                        @endforeach
                    </select>
                    <div class="form-text">Permite asociar las ventas de este usuario a un registro de vendedor para calcular comisiones.</div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Guardar cambios</button>
                    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
