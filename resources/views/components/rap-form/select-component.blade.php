{{-- @props(['component_id', 'label_name', 'name', 'options' => [], 'selected' => null, 'placeholder' => 'Pilih...'])

<div class="mb-3">
    <label for="{{ $component_id }}" class="form-label">{{ $label_name }}</label>
    <select id="{{ $component_id }}" name="{{ $name }}" data-placeholder="{{ $placeholder }}" {{ $attributes->merge(['class' => 'form-select select2']) }}>
        <option value=""></option>
        @foreach ($options as $value => $label)
            <option value="{{ $value }}" {{ old($name, $selected) == $value ? 'selected' : '' }}>
                {{ $label }}
            </option>
        @endforeach
    </select>
    <span id="name_error" class="text-danger">error</span>
</div> --}}

@props([
    'label' => 'Label Name',
    'id' => 'select_id',
    'name' => '',
    'isEdit' => false,
    'disabled' => false,
    'multiple' => false,
])

@php
    $errorKey = \Illuminate\Support\Str::before($name, '[]');
@endphp

<div class="mb-3">
    <label for="{{ $id }}" class="form-label">{!! $label !!}</label>
    <select name="{{ $name }}" {{ $attributes->merge(['class' => 'form-control' . ($errors->has($errorKey) ? ' is-invalid' : '')]) }} id="{{ $id }}" data-placeholder="Pilih..." {{ $multiple ? 'multiple' : '' }} {{ $disabled ? 'disabled' : '' }}>
        {{ $slot }}
    </select>
    <div id="error-div-{{ $id }}">
        @error($errorKey)
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
</div>
