@extends('layouts.app')
@section('content')
<div class="container py-3" style="max-width:640px">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">➕ Nuevo Vendedor</h1>
        <a href="{{ route('vendedores.index') }}" class="btn btn-outline-secondary btn-sm">← Volver</a>
    </div>

    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show">
        <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <form action="{{ route('vendedores.store') }}" method="POST">
                @csrf
                @include('vendedores._form')
                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-primary px-4">💾 Guardar</button>
                    <a href="{{ route('vendedores.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
