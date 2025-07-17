@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Detalle del cliente</h2>

    <ul class="list-group mb-3">
        <li class="list-group-item"><strong>Nombre:</strong> {{ $cliente-nombre}} </li>
        <li class="list-group-item"><strong>Email:</strong> {{ $cliente->email}} </li>
        <li class="list-group-item"><strong>Telefono:</strong> {{ $cliente->telefono}} </li>
        <li class="list-group-item"><strong>Direccion:</strong> {{ $cliente->direccion}} </li>
        <li class="list-group-item"><strong>Referido por:</strong> {{ $cliente->referidoPor->nombre ?? 'Ninguno'}} </li>
    </ul> 

    <a href="{{ route('clientes.edit', $cliente) }}" class="btn btn-warning">Edit</a>
    <a href="{{ route('clientes.index') }}" class="btn btn-secondary">Volver</a>
</div>
@endsection