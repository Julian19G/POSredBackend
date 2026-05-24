@extends('layouts.app')

@section('content')
<div class="container py-3">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">👥 Usuarios del sistema</h1>
        <a href="{{ route('register') }}" class="btn btn-primary btn-sm">➕ Nuevo usuario</a>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="ps-4">Nombre</th>
                            <th>Email</th>
                            <th>Rol</th>
                            <th>Vendedor vinculado</th>
                            <th>Registro</th>
                            <th class="text-end pe-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $u)
                    <tr>
                        <td class="ps-4 fw-semibold">
                            {{ $u->name }}
                            @if($u->id === auth()->id())
                                <span class="badge bg-secondary ms-1" style="font-size:.7rem">Tú</span>
                            @endif
                        </td>
                        <td class="text-muted small">{{ $u->email }}</td>
                        <td>
                            <span class="badge {{ $u->isAdmin() ? 'bg-danger' : 'bg-info text-dark' }}">
                                {{ ucfirst($u->role) }}
                            </span>
                        </td>
                        <td>
                            @if($u->vendedor)
                                <a href="{{ route('vendedores.show', $u->vendedor) }}" class="text-decoration-none small">
                                    {{ $u->vendedor->nombre }}
                                </a>
                            @else
                                <span class="text-muted small">—</span>
                            @endif
                        </td>
                        <td><small class="text-muted">{{ $u->created_at->format('d/m/Y') }}</small></td>
                        <td class="text-end pe-4">
                            <a href="{{ route('users.edit', $u) }}" class="btn btn-sm btn-warning">✏️ Editar</a>
                            @if($u->id !== auth()->id())
                            <form action="{{ route('users.destroy', $u) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('¿Eliminar al usuario {{ addslashes($u->name) }}?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">🗑</button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection
