@props(['menu', 'level'])

@php
    $state = $this->getMenuState($menu);
@endphp

<li style="padding-left: {{ $level * 20 }}px;">
    <div class="form-check border-bottom pb-3" x-data>

        <input
            type="checkbox"
            class="form-check-input"
            id="menu-{{ $menu->id }}"
            x-ref="chk"

            x-effect="
                $refs.chk.checked = {{ $state['all'] ? 'true' : 'false' }};
                $refs.chk.indeterminate = {{ $state['some'] ? 'true' : 'false' }};
            "

            wire:click="toggleMenuAndChildren({{ $menu->id }})"
        >

        <label class="form-check-label" for="menu-{{ $menu->id }}">
            {{ $menu->label_name_en ?? $menu->label_name }}
        </label>
    </div>

    @if ($menu->children && $menu->children->isNotEmpty())
        <ul class="list-unstyled">
            @foreach ($menu->children as $child)
                @include('rbac::livewire.role.partials.menu-item', [
                    'menu' => $child,
                    'level' => $level + 1
                ])
            @endforeach
        </ul>
    @endif
</li>
