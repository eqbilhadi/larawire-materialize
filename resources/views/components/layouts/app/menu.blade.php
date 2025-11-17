@if ($menu->children->isEmpty())
    @if ($menu->is_divider)
        <li class="menu-header small mt-2">
            <span class="menu-header-text">{{ $menu->label_name_en }}</span>
        </li>
    @else
        <li class="menu-item @if (request()->is($menu->url . '*')) active @endif">
            <a href="{{ $menu->route_name && Route::has($menu->route_name) ? route($menu->route_name) : '/' }}" class="menu-link">
                @if ($menu->parent_id)
                    <i class="menu-icon icon-base {{ $menu->icon }} me-3"></i>
                @else
                    <i class="menu-icon icon-base {{ $menu->icon }}"></i>
                @endif
                <div>{{ $menu->label_name_en }}</div>
            </a>
        </li>
    @endif
@else
    @if ($menu->is_divider)
        <li class="menu-header small mt-2">
            <span class="menu-header-text">{{ $menu->label_name_en }}</span>
        </li>
        @foreach ($menu->children as $child)
            <x-layouts.app.menu :menu="$child" />
        @endforeach
    @else
        <li class="menu-item @if (request()->is($menu->url . '*')) active open @endif">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                @unless ($menu->parent_id)
                    <i class="menu-icon icon-base {{ $menu->icon }}"></i>
                @endunless
                <div>{{ $menu->label_name_en }}</div>
            </a>
            <ul class="menu-sub">
                @foreach ($menu->children as $child)
                    <x-layouts.app.menu :menu="$child" />
                @endforeach
            </ul>
        </li>
    @endif
@endif
