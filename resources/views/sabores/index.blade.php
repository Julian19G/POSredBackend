@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Listado de Sabores</h1>

        <a href="{{ route('sabores.create') }}"
            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow">
            + Crear Sabor
        </a>
    </div>

    {{-- Mensajes de éxito --}}
    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

    {{-- Tabla --}}
    <div class="overflow-x-auto bg-white shadow rounded-lg">
        <table class="min-w-full text-left text-gray-700">
            <thead>
                <tr class="bg-gray-100 border-b">
                    <th class="p-3">ID</th>
                    <th class="p-3">Imagen</th>
                    <th class="p-3">Nombre</th>
                    <th class="p-3">Intensidad</th>
                    <th class="p-3">Estado</th>
                    <th class="p-3 text-right">Acciones</th>
                </tr>
            </thead>

            <tbody>
                @forelse($sabores as $sabor)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="p-3">{{ $sabor->id }}</td>

                        <td class="p-3">
                            @if($sabor->imagen)
                                <img src="{{ asset('storage/' . $sabor->imagen) }}"
                                     class="h-12 w-12 object-cover rounded"
                                     alt="imagen sabor">
                            @else
                                <span class="text-gray-400 italic">Sin imagen</span>
                            @endif
                        </td>

                        <td class="p-3 font-semibold">{{ $sabor->nombre }}</td>

                        <td class="p-3">
                            <span class="bg-purple-100 text-purple-700 px-3 py-1 rounded">
                                {{ $sabor->intensidad }}
                            </span>
                        </td>

                        <td class="p-3">
                            @if($sabor->activo)
                                <span class="bg-green-100 text-green-700 px-2 py-1 rounded">Activo</span>
                            @else
                                <span class="bg-red-100 text-red-700 px-2 py-1 rounded">Inactivo</span>
                            @endif
                        </td>

                        <td class="p-3 text-right">
                            <a href="{{ route('sabores.show', $sabor) }}"
                               class="text-blue-600 hover:text-blue-800 mr-3">
                               Ver
                            </a>

                            <a href="{{ route('sabores.edit', $sabor) }}"
                               class="text-yellow-600 hover:text-yellow-800 mr-3">
                               Editar
                            </a>

                            <form action="{{ route('sabores.destroy', $sabor) }}"
                                  method="POST"
                                  class="inline-block"
                                  onsubmit="return confirm('¿Seguro que deseas eliminar este sabor?')">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-600 hover:text-red-800">
                                    Eliminar
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-4 text-center text-gray-500">
                            No hay sabores registrados.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Paginación --}}
    <div class="mt-4">
        {{ $sabores->links() }}
    </div>

</div>
@endsection
