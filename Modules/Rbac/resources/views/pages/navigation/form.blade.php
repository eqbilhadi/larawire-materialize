<x-layouts.app.base title="{{ isset($menu) ? 'Edit' : 'Create' }} Navigation">
    <div class="flex-grow-1 container-p-y container-fluid">
        @isset($menu)
            <livewire:rbac::navigation.form :sys-menu="$menu" />
        @else
            <livewire:rbac::navigation.form />
        @endisset
    </div>
</x-layouts.app.base>
