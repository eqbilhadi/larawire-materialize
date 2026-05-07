<div>
    <div class="card mb-4">
        <div class="card-body">
            <div class="row d-flex align-items-center">
                <div class="col-6">
                    <h5 class="card-title mb-2">@lang('rbac.permission.title.list')</h5>
                    <h6 class="card-subtitle text-muted fw-light">@lang('rbac.permission.subtitle.list')</h6>
                </div>
            </div>
        </div>

        <div class="card-body border-top p-1 py-2">
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <button
                        class="nav-link {{ $activeTab === 'menu_actions' ? 'active' : '' }}"
                        wire:click="$set('activeTab', 'menu_actions')"
                        type="button"
                    >
                        <i class="ri ri-list-check-3 me-1"></i>
                        Menu Actions
                    </button>
                </li>
                <li class="nav-item">
                    <button
                        class="nav-link {{ $activeTab === 'standalone' ? 'active' : '' }}"
                        wire:click="$set('activeTab', 'standalone')"
                        type="button"
                    >
                        <i class="ri ri-key-2-line me-1"></i>
                        Standalone Permissions
                    </button>
                </li>
            </ul>
        </div>
    </div>

    @if ($activeTab === 'menu_actions')
        <div class="card shadow-none border-0">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div class="input-group input-group-merge" style="max-width: 320px;">
                    <span class="input-group-text"><i class="ri ri-search-line"></i></span>
                    <input type="text" class="form-control" placeholder="Search menu..." wire:model.live="searchMenu">
                </div>
            </div>

            <div class="accordion" id="accordionExample">
                @forelse ($this->menus as $menu)
                    <div class="accordion-item" wire:key="menu-accordion-{{ $menu->id }}">
                        <div class="accordion-header d-flex justify-content-between align-items-center">
                            @can ('permission.create')
                                <div class="p-2 flex-shrink-0">
                                    <button
                                        type="button" class="btn btn-sm btn-primary"
                                        data-bs-toggle="modal" data-bs-target="#permissionModalForm"
                                        x-on:click.stop="$dispatch('open-action-form', { menuId: {{ $menu->id }} })"
                                    >
                                        <i class="icon-base ri ri-add-circle-line icon-16px text-white me-xl-2"></i>
                                        <div class="d-none d-xl-block">
                                            Add Action
                                        </div>
                                    </button>
                                </div>
                            @endcan
                            <div
                                class="accordion-button ps-2 collapsed"
                                type="button" data-bs-toggle="collapse" data-bs-target="#accordion-{{ $menu->id }}"
                                aria-expanded="false" aria-controls="accordion-{{ $menu->id }}"
                            >
                                <div class="d-flex align-items-center flex-wrap">

                                    <i class="{{ $menu->icon }} menu-icon"></i>
                                    <span class="fw-semibold text-dark me-2">{{ $menu->label_name_en }}</span>
                                    <code class="text-muted bg-label-light px-2 py-1 rounded-1 border" style="font-size:11px">{{ $menu->permission_prefix }}.*</code>
                                    <span class="badge bg-label-dark ms-2 rounded-1">
                                        {{ $menu->actions->count() }} {{ Str::plural('action', $menu->actions->count()) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div
                            id="accordion-{{ $menu->id }}" class="accordion-collapse collapse" data-bs-parent="#accordionExample"
                        >
                            <div class="accordion-body pb-0">
                                @if($menu->actions->isNotEmpty())
                                    <div class="list-group list-group-flush">
                                        @foreach ($menu->actions->sortBy('order') as $menuAction)
                                            <div class="list-group-item d-flex flex-column flex-md-row justify-content-between align-items-md-center py-2 px-3" wire:key="action-{{ $menuAction->id }}">

                                                <div class="d-flex align-items-center gap-3 mb-2 mb-md-0" style="min-width: 250px;">
                                                    <span class="badge bg-label-light text-uppercase text-center" style="width: 70px;">
                                                        {{ $menuAction->action }}
                                                    </span>
                                                    <span class="fw-medium text-heading mb-0">{{ $menuAction->label }}</span>
                                                </div>

                                                <div class="d-flex flex-wrap align-items-center gap-4 flex-grow-1 text-muted" style="font-size: 13px;">
                                                    <div class="d-flex align-items-center" title="Permission Name">
                                                        <i class="ri ri-shield-keyhole-line me-1 text-secondary"></i>
                                                        {{ $menuAction->permission_name }}
                                                    </div>
                                                    <div class="d-flex align-items-center" title="Route Name">
                                                        <i class="ri ri-link-m me-1 text-secondary"></i>
                                                        {{ $menuAction->route_name ?? '—' }}
                                                    </div>
                                                </div>

                                                <div class="text-md-end ms-md-3 mt-2 mt-md-0 text-nowrap">
                                                    @can('permission.edit')
                                                        <button
                                                            type="button"
                                                            class="btn btn-icon btn-sm btn-text-warning rounded-pill me-1"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#permissionModalForm"
                                                            x-on:click="$dispatch('open-action-form', { menuId: {{ $menu->id }}, menuActionId: {{ $menuAction->id }} })"
                                                        >
                                                            <i class="ri ri-edit-2-line fs-5"></i>
                                                        </button>
                                                    @endcan
                                                    @can('permission.delete')
                                                        <button
                                                            type="button"
                                                            class="btn btn-icon btn-sm btn-text-danger rounded-pill"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#deleteModal"
                                                            data-delete-id="{{ $menuAction->id }}"
                                                            data-delete-type="action"
                                                        >
                                                            <i class="ri ri-delete-bin-5-line fs-5"></i>
                                                        </button>
                                                    @endcan
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center text-muted py-3 small fst-italic">
                                        No actions yet. Click "Add Action" to create one.
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center border border-dashed rounded py-5 text-muted">
                        <i class="ri ri-folder-forbid-line fs-2 mb-2 d-block mx-auto"></i>
                        No menus with a permission prefix found.<br>
                        <a href="{{ route('rbac.nav.index') }}" class="text-primary fw-medium">Set a prefix</a> in Navigation Management first.
                    </div>
                @endforelse
            </div>
        </div>
    @endif

    @if ($activeTab === 'standalone')
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div class="input-group input-group-merge" style="max-width: 320px;">
                    <span class="input-group-text"><i class="ri ri-search-line"></i></span>
                    <input
                        type="text"
                        class="form-control"
                        placeholder="Search permission..."
                        wire:model.live="searchPerm"
                    >
                </div>
                @can ('permission.create')
                    <button
                        class="btn btn-primary"
                        data-bs-toggle="modal"
                        data-bs-target="#permissionModalForm"
                        x-on:click="$dispatch('open-permission-form')"
                    >
                        <i class="ri ri-add-circle-line me-sm-1"></i>
                        <span class="d-none d-sm-inline">{{ __('button.add') }} {{ __('rbac.permission.entity') }}</span>
                    </button>
                @endcan
            </div>

            <div class="table-responsive text-nowrap">
                <table class="table table-hover table-sm">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 5px">@lang('rbac.permission.table.header_no')</th>
                            <th>@lang('rbac.permission.table.header_name')</th>
                            <th>@lang('rbac.permission.table.header_group')</th>
                            <th class="text-end">@lang('rbac.permission.table.header_actions')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($this->standalonePermissions as $permission)
                            <tr>
                                <td class="text-center">
                                    {{ $this->standalonePermissions->firstItem() + $loop->index }}
                                </td>
                                <td>{{ $permission->name }}</td>
                                <td>{{ $permission->group }}</td>
                                <td class="text-end">
                                    @can('permission.edit')
                                        <button
                                            type="button"
                                            class="btn btn-icon btn-warning"
                                            data-bs-toggle="modal"
                                            data-bs-target="#permissionModalForm"
                                            x-on:click="$dispatch('open-permission-form', { comPermission: {{ $permission }} })"
                                        >
                                            <i class="ri ri-edit-2-line text-white"></i>
                                        </button>
                                    @endcan
                                    @can('permission.delete')
                                        <button
                                            type="button"
                                            class="btn btn-icon btn-danger"
                                            data-bs-toggle="modal"
                                            data-bs-target="#deleteModal"
                                            data-delete-id="{{ $permission->id }}"
                                            data-delete-type="permission"
                                        >
                                            <i class="ri ri-delete-bin-5-line text-white"></i>
                                        </button>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="100%" class="text-center">No permissions found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($this->standalonePermissions->hasPages())
                <div class="pt-5 px-5">{{ $this->standalonePermissions->links() }}</div>
            @endif
        </div>
    @endif

    <livewire:rbac::permission.form />

    <x-confirm-delete-modal />

    <script>
        document.addEventListener("livewire:initialized", () => {
            Livewire.on("close-modal", () => $("#permissionModalForm").modal("hide"));
        }, { once: true });

        // Override tombol delete di modal agar dispatch event dengan type yang benar
        document.getElementById('deleteModal').addEventListener('show.bs.modal', function (e) {
            const trigger = e.relatedTarget;
            const id      = trigger?.dataset?.deleteId;
            const type    = trigger?.dataset?.deleteType ?? 'permission';

            const confirmBtn = document.getElementById('delete-notification');

            // Clone button untuk remove event listener lama
            const newBtn = confirmBtn.cloneNode(true);
            if (type === 'action') {
                newBtn.setAttribute('wire:click', 'deleteAction("' + id + '")');
            } else {
                newBtn.setAttribute('wire:click', 'deletePermission("' + id + '")');
            }
            confirmBtn.parentNode.replaceChild(newBtn, confirmBtn);
        });
    </script>
</div>
