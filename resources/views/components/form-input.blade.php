<div class="mb-3">
    <label for="{{ $id ?? 'input' }}" class="form-label">{{ $label }}</label>
    <input 
        type="{{ $type ?? 'text' }}" 
        class="form-control {{ $errors->has($name) ? 'is-invalid' : '' }} {{ $class ?? '' }}" 
        id="{{ $id ?? 'input' }}" 
        name="{{ $name ?? '' }}" 
        value="{{ old($name) ?? $value ?? '' }}" 
        {{ $attributes }}
    >
    @if ($errors->has($name))
        <div class="invalid-feedback">
            {{ $errors->first($name) }}
        </div>
    @endif
</div>