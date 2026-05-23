{{-- Datos de contacto --}}
<div class="row g-3">

    <div class="col-md-6">
        <label class="form-label fw-semibold">Nombre completo <span class="text-danger">*</span></label>
        <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror"
               value="{{ old('nombre', $cliente->nombre ?? '') }}" required>
        @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label fw-semibold">Fecha de nacimiento</label>
        <input type="date" name="fecha_nacimiento"
               class="form-control @error('fecha_nacimiento') is-invalid @enderror"
               value="{{ old('fecha_nacimiento', isset($cliente->fecha_nacimiento) ? $cliente->fecha_nacimiento->format('Y-m-d') : '') }}">
        @error('fecha_nacimiento') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- Contacto --}}
    <div class="col-md-4">
        <label class="form-label fw-semibold">Teléfono <span class="text-danger">*</span></label>
        <div class="input-group">
            <span class="input-group-text">📞</span>
            <input type="text" name="telefono" class="form-control @error('telefono') is-invalid @enderror"
                   placeholder="300 123 4567" value="{{ old('telefono', $cliente->telefono ?? '') }}" required>
        </div>
        @error('telefono') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-4">
        <label class="form-label fw-semibold">WhatsApp</label>
        <div class="input-group">
            <span class="input-group-text">💬</span>
            <input type="text" name="whatsapp" class="form-control @error('whatsapp') is-invalid @enderror"
                   placeholder="300 123 4567" value="{{ old('whatsapp', $cliente->whatsapp ?? '') }}">
        </div>
        @error('whatsapp') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-4">
        <label class="form-label fw-semibold">Instagram</label>
        <div class="input-group">
            <span class="input-group-text">@</span>
            <input type="text" name="instagram" class="form-control @error('instagram') is-invalid @enderror"
                   placeholder="usuario (sin @)" value="{{ old('instagram', $cliente->instagram ?? '') }}">
        </div>
        @error('instagram') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label fw-semibold">Email</label>
        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
               value="{{ old('email', $cliente->email ?? '') }}">
        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- Ubicación --}}
    <div class="col-md-6">
        <label class="form-label fw-semibold">Ciudad</label>
        <input type="text" name="ciudad" class="form-control @error('ciudad') is-invalid @enderror"
               value="{{ old('ciudad', $cliente->ciudad ?? 'Cali') }}">
        @error('ciudad') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-4">
        <label class="form-label fw-semibold">Barrio</label>
        <input type="text" name="barrio" class="form-control @error('barrio') is-invalid @enderror"
               value="{{ old('barrio', $cliente->barrio ?? '') }}" placeholder="Ej: El Ingenio, Chipichape…">
        @error('barrio') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-8">
        <label class="form-label fw-semibold">Dirección habitual</label>
        <input type="text" name="direccion" class="form-control @error('direccion') is-invalid @enderror"
               value="{{ old('direccion', $cliente->direccion ?? '') }}" placeholder="Calle, número, apto…">
        @error('direccion') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- Referido --}}
    <div class="col-md-6">
        <label class="form-label fw-semibold">¿Quién lo refirió?</label>
        <select name="referido_por" class="form-select @error('referido_por') is-invalid @enderror">
            <option value="">— Sin referido —</option>
            @foreach ($clientes as $c)
                <option value="{{ $c->id }}"
                    {{ old('referido_por', $cliente->referido_por ?? '') == $c->id ? 'selected' : '' }}>
                    {{ $c->nombre }}
                </option>
            @endforeach
        </select>
        @error('referido_por') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- Notas --}}
    <div class="col-12">
        <label class="form-label fw-semibold">Notas internas</label>
        <textarea name="notas" class="form-control @error('notas') is-invalid @enderror"
                  rows="2" placeholder="Preferencias, notas de entrega, historial…">{{ old('notas', $cliente->notas ?? '') }}</textarea>
        @error('notas') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

</div>
