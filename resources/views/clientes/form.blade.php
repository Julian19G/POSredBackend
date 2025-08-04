<div class="mb-3">
    <label for="nombre" class="form-label">Nombre</label>
    <input id="nombre" type="text" name="nombre" class="form-control" value="{{ old('nombre', $cliente->nombre ?? '') }}" required>
</div>

<div class="mb-3">
    <label for="email" class="form-label">Email</label>
    <input id="email" type="email" name="email" class="form-control" value="{{ old('email', $cliente->email ?? '') }}" required>
</div>

<div class="mb-3">
    <label for="telefono" class="form-label">Teléfono</label>
    <input id="telefono" type="text" name="telefono" class="form-control" value="{{ old('telefono', $cliente->telefono ?? '') }}" required>
</div>

<div class="mb-3">
    <label for="direccion" class="form-label">Dirección</label>
    <input id="direccion" type="text" name="direccion" class="form-control" value="{{ old('direccion', $cliente->direccion ?? '') }}" required>
</div>

<div class="mb-3">
    <label for="referido_por" class="form-label">¿Quién lo refirió?</label>
    <select id="referido_por" name="referido_por" class="form-control">
        <option value="">-- Ninguno --</option>
        @foreach ($clientes as $c)
            <option value="{{ $c->id }}"
                {{ old('referido_por', $cliente->referido_por ?? '') == $c->id ? 'selected' : '' }}>
                {{ $c->nombre }}
            </option>
        @endforeach
    </select>
</div>

@if ($errors->any())
    <div class="alert alert-danger mt-2">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
