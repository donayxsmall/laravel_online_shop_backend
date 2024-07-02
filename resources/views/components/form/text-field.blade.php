<div class="form-group">
    <label class="{{ $required ? 'has-star' : '' }}">{{ $label }}</label>
    <input type="text"
           class="form-control @error($name) is-invalid @enderror"
           name="{{ $name }}"
           {{-- value="{{ old($name, $value) }}" --}}
           {{-- value="{{ $formatNumber ? number_format((float)old($name, $value)) : old($name, $value) }}" --}}
           value="{{ $formatNumber ? number_format((float)str_replace(',', '', old($name, $value))) : old($name, $value) }}"
           {{ $required ? 'required' : '' }}
           @if($readonly) readonly @endif
           >

    @error($name)
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror
</div>
