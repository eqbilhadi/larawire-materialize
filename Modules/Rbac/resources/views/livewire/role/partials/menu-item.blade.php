@props(['menu', 'level'])

@php
    $menuState       = $this->getMenuState($menu);
    $menuActionState = $this->getMenuActionState($menu->id);
    $hasActions      = $menu->actions->isNotEmpty();
    $lang            = current_language();
    $menuLabel       = $menu->{'label_name_'.$lang} ?? $menu->label_name_en;
    $isChild         = $menu->parent_id != null;
@endphp

<li style="padding-left: @if($isChild) 20px @endif;" wire:key="menu-li-{{ $menu->id }}">

    <div class="border-bottom pb-2 mb-2" x-data>
        <div class="d-flex align-items-center gap-2">
            <div class="form-check mb-0" x-data>
                <input
                    type="checkbox"
                    class="form-check-input"
                    id="menu-{{ $menu->id }}"
                    x-ref="chk"
                    x-effect="
                        $refs.chk.checked       = {{ $menuState['all']  ? 'true' : 'false' }};
                        $refs.chk.indeterminate = {{ $menuState['some'] ? 'true' : 'false' }};
                    "
                    wire:click="toggleMenuAndChildren({{ $menu->id }})"
                    wire:loading.attr="disabled"
                    wire:target="toggleMenuAndChildren"
                >
                <label class="form-check-label fw-semibold" for="menu-{{ $menu->id }}">
                    {{ $menuLabel }}
                </label>
            </div>

            @if ($hasActions)
                <div class="ms-auto" x-data>
                    <input
                        type="checkbox"
                        class="form-check-input"
                        id="all-actions-{{ $menu->id }}"
                        x-ref="achk"
                        x-effect="
                            $refs.achk.checked       = {{ $menuActionState['all']  ? 'true' : 'false' }};
                            $refs.achk.indeterminate = {{ $menuActionState['some'] ? 'true' : 'false' }};
                        "
                        wire:click="toggleAllMenuActions({{ $menu->id }})"
                        title="Toggle all actions"
                        wire:loading.attr="disabled"
                        wire:target="toggleAllMenuActions, selectedMenuActions"
                    >
                    <label class="form-check-label text-muted small" for="all-actions-{{ $menu->id }}">
                        All actions
                    </label>
                </div>
            @endif
        </div>

        @if ($hasActions)
            <div class="d-flex flex-wrap gap-3 mt-2 ps-10">
                @foreach ($menu->actions->sortBy('order') as $menuAction)
                    <div class="d-flex align-items-start gap-2 mb-0 me-3 pe-4 @if (!$loop->last) border-end @endif" wire:key="action-{{ $menuAction->id }}">
                        <input
                            type="checkbox"
                            class="form-check-input form-check-input-access mt-1"
                            id="action-{{ $menuAction->id }}"
                            value="{{ $menuAction->id }}"
                            wire:model.live="selectedMenuActions"
                            wire:target="toggleAllMenuActions, selectedMenuActions"
                            wire:loading.attr="disabled"
                        >
                        <label class="form-check-label" for="action-{{ $menuAction->id }}">
                            {{ $menuAction->label }}
                            <small class="text-muted d-block" style="font-size:10px">{{ $menuAction->permission_name }}</small>
                        </label>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

</li>
