<div class="row g-3">

    <div class="col-12">
        <label class="form-label fw-semibold">Nombre <span class="text-danger">*</span></label>
        <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror"
               value="{{ old('nombre', $vendedor->nombre ?? '') }}" required>
        @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label fw-semibold">Teléfono</label>
        <div class="input-group">
            <span class="input-group-text">📞</span>
            <input type="text" name="telefono" class="form-control @error('telefono') is-invalid @enderror"
                   value="{{ old('telefono', $vendedor->telefono ?? '') }}" placeholder="300 123 4567">
        </div>
        @error('telefono') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label fw-semibold">WhatsApp</label>
        <div class="input-group">
            <span class="input-group-text">💬</span>
            <input type="text" name="whatsapp" class="form-control @error('whatsapp') is-invalid @enderror"
                   value="{{ old('whatsapp', $vendedor->whatsapp ?? '') }}" placeholder="300 123 4567">
        </div>
        @error('whatsapp') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label fw-semibold">Email</label>
        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
               value="{{ old('email', $vendedor->email ?? '') }}">
        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label fw-semibold">Instagram</label>
        <div class="input-group">
            <span class="input-group-text">@</span>
            <input type="text" name="instagram" class="form-control @error('instagram') is-invalid @enderror"
                   value="{{ old('instagram', $vendedor->instagram ?? '') }}" placeholder="usuario (sin @)">
        </div>
        @error('instagram') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label fw-semibold">Comisión (%)</label>
        <div class="input-group">
            <input type="number" name="comision_porcentaje" class="form-control @error('comision_porcentaje') is-invalid @enderror"
                   min="0" max="100" step="0.5"
                   value="{{ old('comision_porcentaje', $vendedor->comision_porcentaje ?? 0) }}">
            <span class="input-group-text">%</span>
        </div>
        @error('comision_porcentaje') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6 d-flex align-items-end">
        <div class="form-check mb-2">
            <input class="form-check-input" type="checkbox" name="activo" id="activo" value="1"
                   {{ old('activo', $vendedor->activo ?? true) ? 'checked' : '' }}>
            <label class="form-check-label fw-semibold" for="activo">Vendedor activo</label>
        </div>
    </div>

    <div class="col-12">
        <label class="form-label fw-semibold">Notas</label>
        <textarea name="notas" class="form-control @error('notas') is-invalid @enderror"
                  rows="2" placeholder="Territorio, horario, observaciones…">{{ old('notas', $vendedor->notas ?? '') }}</textarea>
        @error('notas') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

</div>
