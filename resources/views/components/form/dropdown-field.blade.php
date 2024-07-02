
<div class="form-group">
    <label class="{{ $required ? 'has-star' : '' }}">{{ $label }}</label>
    <select class="form-control select2 @error($name) is-invalid @enderror"
            name="{{ $name }}">
        <option value="">-- Select {{ $label }} --</option>
        @foreach ($items as $value => $item)
            <option value="{{ $value }}"
                    {{ $value == $selected ? 'selected' : '' }}>
                {{ $item }}
            </option>
        @endforeach
    </select>
    @error($name)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
