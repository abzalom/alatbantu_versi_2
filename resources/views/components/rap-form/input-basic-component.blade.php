@props([
    'inputType' => 'text',
    'label' => 'Label Name',
    'id' => 'select_id',
    'name' => '',
    'value' => '',
    'disabled' => false,
    'placeholder' => 'Example Placeholder',
])
<div class="mb-3">
    <label for="{{ $id }}" class="form-label">{!! $label !!}</label>
    <input name="{{ $name }}" type="{{ $inputType }}" value="{{ $value }}" {{ $attributes->merge(['class' => 'form-control' . ($errors->has($name) ? ' is-invalid' : '')]) }} id="{{ $id }}" placeholder="{{ $placeholder }}" {{ $disabled ? 'disabled' : '' }}>
    <div id="error-div-{{ $id }}">
        @error($name)
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
</div>
