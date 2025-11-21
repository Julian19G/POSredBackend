@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">

    <h1 class="text-2xl font-bold text-gray-800 mb-6">Editar Sabor</h1>

    <div class="max-w-xl bg-white shadow p-6 rounded-lg">

        <form action="{{ route('sabores.update', $sabor) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Nombre --}}
            <div class="mb-4">
                <label class="block font-semibold text-gray-700 mb-1">Nombre</label>
                <input type="text" name="nombre" value="{{ old('nombre', $sabor->nombre) }}"
                       class="w-full border-gray-300 rounded-lg shadow-sm"
                       required>
                @error('nombre')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Descripción --}}
            <div class="mb-4">
                <label class="block font-semibold text-gray-700 mb-1">Descripción</label>
                <textarea name="descripcion" rows="3"
                          class="w-full border-gray-300 rounded-lg shadow-sm">{{ old('descripcion', $sabor->descripcion) }}</textarea>
            </div>

            {{-- Intensidad --}}
            <div class="mb-4">
                <label class="block font-semibold text-gray-700 mb-1">Intensidad</label>
                <select name="intensidad" class="w-full border-gray-300 rounded-lg shadow-sm">
                    @for($i = 1; $i <= 10; $i++)
                        <option value="{{ $i }}" {{ $sabor->intensidad == $i ? 'selected' : '' }}>
                            {{ $i }}
                        </option>
                    @endfor
                </select>
            </div>


            {{-- Estado --}}
            <div class="mb-3">
                <label class="form-label">Estado</label>
                <select name="activo" class="form-select @error('activo') is-invalid @enderror">
                    <option value="1" {{ old('activo', 1) == 1 ? 'selected' : '' }}>Activo</option>
                    <option value="0" {{ old('activo') == 0 ? 'selected' : '' }}>Inactivo</option>
                </select>

                @error('activo')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Imagen actual --}}
            <div class="mb-4">
                <label class="block font-semibold text-gray-700 mb-1">Imagen actual</label>

                @if($sabor->imagen)
                    <img src="{{ asset('storage/' . $sabor->imagen) }}" alt="imagen sabor"
                         class="h-20 w-20 object-cover rounded border mb-2">
                @else
                    <p class="text-gray-500 italic">No hay imagen</p>
                @endif
            </div>

            {{-- Subir nueva imagen --}}
            <div class="mb-6">
                <label class="block font-semibold text-gray-700 mb-1">Cambiar imagen</label>
                <input type="file" name="imagen"
                       class="w-full border-gray-300 rounded-lg shadow-sm">
            </div>

            {{-- Botones --}}
            <div class="flex items-center justify-between">
                <a href="{{ route('sabores.index') }}"
                   class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded">
                    Cancelar
                </a>

                <button type="submit"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded shadow">
                    Guardar cambios
                </button>
            </div>

        </form>

    </div>
</div>
@endsection
