@props([
    'label' => 'Label Name',
    'id' => 'select_id',
    'name' => '',
    'disabled' => false,
    'placeholder' => 'Example Placeholder',
    'rows' => 3,
])
<div class="mb-3">
    <label for="{{ $id }}" class="form-label">{!! $label !!}</label>
    <textarea name="{{ $name }}" {{ $attributes->merge(['class' => 'form-control' . ($errors->has($name) ? ' is-invalid' : '')]) }} id="{{ $id }}" placeholder="{{ $placeholder }}" rows="{{ $rows }}" {{ $disabled ? 'disabled' : '' }}>{{ $slot }}</textarea>
    <div id="error-div-{{ $id }}">
        @error($name)
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
</div>
