<div class="mb-3">
    <label>Nombre</label>
    <input type="text" name="nombre" class="form-control" value="{{ old('nombre', $cliente->nombre ?? '') }}" required>
</div>

<div class="mb-3">
    <label>Email</label>
    <input type="text" name="email" class="form-control" value="{{ old('email', $cliente->email ?? '') }}" required>
</div>

<div class="mb-3">
    <label>Telefono</label>
    <input type="text" name="telefono" class="form-control" value="{{ old('telefono', $cliente->telefono ?? '') }}" required>
</div>

<div class="mb-3">
    <label>Direccion</label>
    <input type="text" name="direccion" class="form-control" value="{{ old('direccion', $cliente->direccion ?? '') }}" required>
</div>

<div class="mb-3">
    <label>¿Quien lo referio?</label>
    <select name="referido_por" class="form-control">
     <option value="">-- Ninguno --</option>
     @foreach ($clientes as $c)
        <option value ="{{ $c->id }}"
            {{ old('referido_por'}}, $cliente->referido_por ?? '') == $c->id ? 'selected' : ''}}>
            {{ $c->nombre }}
        </option>
    </select>
</div>

@if ($errors->any())
    <div class="alert alert-danger mt-2">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li> {{ $error }} </li>
            @endforeach
        </ul>
    </div>
@endif