<x-layouts.app.base title="{{ isset($role) ? 'Edit' : 'Create' }} Role">
    <div class="flex-grow-1 container-p-y container-fluid">
        @isset($role)
            <livewire:rbac::role.form :sys-role="$role" />
        @else
            <livewire:rbac::role.form />
        @endisset
    </div>
</x-layouts.app.base>
