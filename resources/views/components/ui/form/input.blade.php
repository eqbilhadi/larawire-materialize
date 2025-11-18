@props([
    'label' => 'Input Label',
    'placeholder' => '',
    'model' => null,
    'modifier' => 'debounce',   // default: wire:model
    'debounce' => '250ms',      // isi misal: "250ms"
    'wrapperClass' => '',
    'type' => 'text'
])

@php
    // Build wire:model attribute
    $wireAttribute = null;

    if ($model) {
        if ($modifier === 'model') {
            // wire:model (tanpa modifier)
            $wireAttribute = "wire:model=\"{$model}\"";
        } elseif ($modifier === 'debounce' && $debounce) {
            // wire:model.debounce.250ms
            $wireAttribute = "wire:model.live.debounce.{$debounce}=\"{$model}\"";
        } else {
            // modifier lain: lazy, live, blur
            $wireAttribute = "wire:model.{$modifier}=\"{$model}\"";
        }
    }

    $baseClass = 'form-control';
    $errorClass = $errors->has($model) ? ' is-invalid' : '';
    $extraClass = $attributes->get('class');
    $finalClass = trim("$baseClass$errorClass $extraClass");
@endphp

<div class="form-floating form-floating-outline {{ $wrapperClass }}">
    <input
        type="{{ $type }}"
        class="{{ $finalClass }}"
        placeholder="{{ $placeholder ?: $label }}"

        @if($wireAttribute)
            {!! $wireAttribute !!}
        @endif

        {{ $attributes->except('class') }}
    />

    <label>{{ $label }}</label>

    @error($model)
        <div class="text-danger" style="font-size: 11.5px">
            {{ $message }}
        </div>
    @enderror
</div>
