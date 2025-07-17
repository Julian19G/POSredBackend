@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Editar Clientes</h2>

    <form action="{{ route('clientes.update',$cliente) }}" method="POST">
        @csrf @method('PUT')

        @include('clientes.form')

        <button type="submit" class="btn btn-primary">Actualizar</button>
        <a href="{{ route('clientes.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection