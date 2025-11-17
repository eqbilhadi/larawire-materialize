@props([
    'label' => null,
    'items' => [],
    'checked' => [],
    'groupKey' => null,  {{-- optional --}}
])

<div class="mb-3">
    <div class="flex justify-between items-center mb-1">
        <span class="font-semibold">{{ $label }}</span>

        {{-- GROUP CHECKBOX --}}
        <input
            type="checkbox"
            wire:click="toggleGroup('{{ $groupKey }}')"
            @if(collect($items)->every(fn($i) => in_array($i['id'], $checked))) checked @endif
            @if(
                collect($items)->contains(fn($i) => in_array($i['id'], $checked))
                && !collect($items)->every(fn($i) => in_array($i['id'], $checked))
            )
               x-indeterminate
            @endif
        >
    </div>

    @foreach ($items as $item)
        <label class="flex justify-between px-4 py-1 border-b cursor-pointer hover:bg-gray-100">
            <span>{{ $item['name'] }}</span>
            <input
                type="checkbox"
                wire:click="toggleSingle({{ $item['id'] }})"
                @checked(in_array($item['id'], $checked))
            >
        </label>
    @endforeach
</div>

@once
    {{-- Script untuk indeterminate --}}
    <script>
        document.querySelectorAll('[x-indeterminate]').forEach(el => {
            el.indeterminate = true;
        })
    </script>
@endonce
