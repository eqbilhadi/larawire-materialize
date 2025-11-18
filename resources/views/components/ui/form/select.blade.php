@props([
    'label' => 'Select Label',
    'model' => null,
    'placeholder' => 'Select option',
    'options' => [],
    'wrapperClass' => '',
])

@php
    $hasError = $errors->has($model);

    $baseClass = 'form-select';
    $errorClass = $hasError ? ' is-invalid' : '';
    $extraClass = $attributes->get('class');
    $finalClass = trim("$baseClass$errorClass $extraClass");
@endphp

<div class="form-floating form-floating-outline {{ $wrapperClass }}">
    <select
        @if($model)
            wire:model.live="{{ $model }}"
        @endif

        class="{{ $finalClass }}"
        {{ $attributes->except('class') }}
    >
        <option value="">{{ $placeholder }}</option>

        @foreach ($options as $key => $value)
            @if (is_array($value))
                <option value="{{ $value['id'] }}">
                    {{ $value['label'] }}
                </option>
            @else
                <option value="{{ $key }}">
                    {{ $value }}
                </option>
            @endif
        @endforeach
    </select>

    <label>{{ $label }}</label>

    @error($model)
        <div class="text-danger" style="font-size: 11.5px">
            {{ $message }}
        </div>
    @enderror
</div>
