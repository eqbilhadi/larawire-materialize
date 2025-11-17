<x-layouts.app.base title="{{ isset($user) ? 'Edit' : 'Create' }} User">
    <div class="flex-grow-1 container-p-y container-fluid">
        @isset($user)
            <livewire:rbac::user.form :sys-user="$user" />
        @else
            <livewire:rbac::user.form />
        @endisset
    </div>
</x-layouts.app.base>
