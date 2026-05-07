{{--
    Blade helper partial: rbac::components.menu-actions

    Usage on any page (e.g. resources/views/guru/index.blade.php):

    @php
        $menu = \App\Models\SysMenu::where('permission_prefix', 'guru')
                    ->with('actions')
                    ->first();
    @endphp

    @include('rbac::components.menu-actions', ['menu' => $menu, 'model' => null])

    For row-level buttons (inside a @foreach):
    @include('rbac::components.menu-actions', ['menu' => $menu, 'model' => $guru])
--}}

@if ($menu && $menu->actions->isNotEmpty())
    @foreach ($menu->actions->sortBy('order') as $menuAction)
        @can($menuAction->permission_name)

            @if ($menuAction->action === 'create' && !isset($model))
                <a href="{{ route($menu->route_name) }}" class="btn btn-primary btn-sm">
                    <i class="ri ri-add-line me-1"></i> {{ $menuAction->label }}
                </a>

            @elseif ($menuAction->action === 'edit' && isset($model))
                <a href="{{ route($menu->route_name . '.edit', $model) }}" class="btn btn-warning btn-sm">
                    <i class="ri ri-pencil-line me-1"></i> {{ $menuAction->label }}
                </a>

            @elseif ($menuAction->action === 'delete' && isset($model))
                <button
                    type="button"
                    class="btn btn-danger btn-sm"
                    onclick="confirmDelete('{{ route($menu->route_name . '.destroy', $model) }}')"
                >
                    <i class="ri ri-delete-bin-line me-1"></i> {{ $menuAction->label }}
                </button>

            @elseif ($menuAction->action === 'export' && !isset($model))
                <a href="{{ route($menu->route_name . '.export') }}" class="btn btn-success btn-sm">
                    <i class="ri ri-file-excel-line me-1"></i> {{ $menuAction->label }}
                </a>

            @elseif ($menuAction->action === 'import' && !isset($model))
                <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#importModal">
                    <i class="ri ri-upload-line me-1"></i> {{ $menuAction->label }}
                </button>

            @else
                {{-- Generic fallback for custom actions --}}
                @if (!isset($model))
                    <button type="button" class="btn btn-secondary btn-sm"
                        data-action="{{ $menuAction->action }}"
                        data-permission="{{ $menuAction->permission_name }}">
                        {{ $menuAction->label }}
                    </button>
                @endif
            @endif

        @endcan
    @endforeach
@endif
