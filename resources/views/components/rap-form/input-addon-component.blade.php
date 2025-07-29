@props([
    'inputType' => 'text',
    'label' => 'Label Name',
    'id' => 'select_id',
    'name' => '',
    'value' => '',
    'isEdit' => false,
    'disabled' => false,
    'addon' => 'left',
    'addonName' => 'addon1',
    'addonId' => 'basic-addon1',
    'placeholder' => 'Example Placeholder',
])
<div class="mb-3">
    <label for="{{ $id }}" class="form-label">{!! $label !!}</label>
    <div class="input-group">
        @if ($addon == 'left')
            <span class="input-group-text" id="{{ $addonId }}">{{ $addonName }}</span>
        @endif
        <input name="{{ $name }}" type="{{ $inputType }}" value="{{ $value }}" {{ $attributes->merge(['class' => 'form-control' . ($errors->has($name) ? ' is-invalid' : '')]) }} id="{{ $id }}" placeholder="{{ $placeholder }}" aria-describedby="{{ $addonId }}" {{ $disabled ? 'disabled' : '' }}>
        @if ($addon == 'right')
            <span class="input-group-text" id="{{ $addonId }}">{{ $addonName }}</span>
        @endif
    </div>
    <div id="error-div-{{ $id }}">
        @error($name)
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
</div>
