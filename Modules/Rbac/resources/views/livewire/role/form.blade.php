<div>
    <div class="card">
        <div class="card-body">
            <div class="row d-flex align-items-center">
                <div class="col-6">
                    <h5 class="card-title mb-2">
                        @if ($sysRole->exists) Edit @else Create @endif Role
                    </h5>
                    <h6 class="card-subtitle text-muted fw-light">
                        Provide the role details and select the permissions it should have.
                    </h6>
                </div>
                <div class="col-6 text-end">
                    <a href="{{ route('rbac.role.index') }}" class="btn btn-primary">
                        <i class="ri ri-arrow-left-circle-line me-sm-1 icon-20px"></i>
                        <span class="d-none d-sm-inline align-self-center">
                            Back
                        </span>
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body border-top">
            <form wire:submit="save">
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <x-ui.form.input
                            label="Role name"
                            placeholder="Name of the role"
                            model="name"
                            modifier="model"
                            wrapperClass="mb-5"
                        />
                    </div>
                </div>

                <div class="row">

                    <div class="col-md-6">
                        <div class="card shadow-none border" style="height: 100%;">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="card-title mb-2">Role Permissions</h5>
                                    <h6 class="card-subtitle text-muted fw-light">
                                        Choose what this role is allowed to do in the system.
                                    </h6>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="d-flex w-100 gap-2 align-items-center mb-2">
                                    <div class="flex-grow-1">
                                        <x-ui.form.input
                                            label="Search"
                                            placeholder="Search permissions"
                                            model="searchPermission"
                                        />
                                    </div>

                                    <div class="d-flex align-items-end">
                                        <button
                                            class="btn btn-primary"
                                            type="button"
                                            wire:click="toggleSelectAllPermissions"
                                            style="padding: 0.8555rem 1rem;"
                                        >
                                            Select All
                                        </button>
                                    </div>
                                </div>
                                <ul class="list-unstyled" style="overflow-y: auto; max-height: 350px;">
                                    @forelse ($this->permissions as $groupName => $permissions)
                                        <div class="mt-3" wire:key="group-{{ $groupName }}">
                                            @php
                                                $idsInGroup = collect($this->permissions[$groupName] ?? [])->pluck('id');
                                                $allSelected = $idsInGroup->every(fn($id) => in_array($id, $this->selectedPermissions));
                                                $someSelected = $idsInGroup->contains(fn($id) => in_array($id, $this->selectedPermissions)) && !$allSelected;
                                            @endphp

                                            <div class="form-check border-bottom pb-3" x-data>
                                                <input
                                                    class="form-check-input"
                                                    type="checkbox"
                                                    id="group-{{ $loop->index }}"
                                                    x-ref="checkbox"

                                                    x-effect="
                                                        $refs.checkbox.checked = {{ $allSelected ? 'true' : 'false' }};
                                                        $refs.checkbox.indeterminate = {{ $someSelected ? 'true' : 'false' }};
                                                    "

                                                    wire:click="togglePermissionGroup('{{ $groupName }}', true)"
                                                >

                                                <label class="form-check-label fw-bold" for="group-{{ $loop->index }}">
                                                    {{ $groupName }}
                                                </label>
                                            </div>

                                            <ul class="list-unstyled ps-4 mt-2 mb-0 border-bottom">
                                                @foreach ($permissions as $permission)
                                                    <li class="form-check" wire:key="perm-{{ $permission->id }}">
                                                        <input
                                                            class="form-check-input" type="checkbox"
                                                            value="{{ $permission->id }}"
                                                            wire:model.live="selectedPermissions"
                                                            id="perm-{{ $permission->id }}"
                                                        />
                                                        <label class="form-check-label" for="perm-{{ $permission->id }}">
                                                            {{ $permission->name }}
                                                        </label>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @empty
                                        <p class="text-center text-muted">No permissions found.</p>
                                    @endforelse
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card shadow-none border" style="height: 100%;">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="card-title mb-2">Accessible Menus</h5>
                                    <h6 class="card-subtitle text-muted fw-light">
                                        Determine which menus this role can access.
                                    </h6>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="d-flex w-100 gap-2 align-items-center mb-5">
                                    <div class="flex-grow-1">
                                        <x-ui.form.input
                                            label="Search"
                                            placeholder="Search menus"
                                            model="searchMenu"
                                        />
                                    </div>

                                    <div class="d-flex align-items-end">
                                        <button
                                            class="btn btn-primary"
                                            type="button"
                                            wire:click="toggleSelectAllMenus"
                                            style="padding: 0.8555rem 1rem;"
                                        >
                                            Select All
                                        </button>
                                    </div>
                                </div>
                                <ul class="list-unstyled" style="overflow-y: auto; max-height: 350px;">
                                    @forelse ($this->menus as $menu)
                                        @include('rbac::livewire.role.partials.menu-item', ['menu' => $menu, 'level' => 0])
                                    @empty
                                        <p class="text-center text-muted">No menus found.</p>
                                    @endforelse
                                </ul>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="text-end mt-4">
                    <a href="{{ route('rbac.role.index') }}" class="btn btn-light mr-2">Cancel</a>
                    <button type="submit" class="btn btn-primary">
                        <span wire:loading.remove wire:target="save">Save</span>
                        <span wire:loading wire:target="save">Saving...</span>
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
