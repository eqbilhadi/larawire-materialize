<div>
    <div class="card">
        <div class="card-body">
            <div class="row d-flex align-items-center">
                <div class="col-6">
                    <h5 class="card-title mb-2">Permission Management</h5>
                    <h6 class="card-subtitle text-muted fw-light">View and organize permissions that control user access to features.</h6>
                </div>
                @can('create permission')
                    <div class="col-6 text-end">
                        <button
                            class="btn btn-primary"
                            x-on:click="$dispatch('open-permission-form')"
                            data-bs-toggle="modal"
                            data-bs-target="#permissionModalForm"
                        >
                            <i class="ri ri-add-circle-line me-sm-1 icon-20px"></i>
                            <span class="d-none d-sm-inline"> Add Permissions </span>
                        </button>
                    </div>
                @endcan
            </div>
        </div>
        <div class="card-header border-top">
            <div class="row">
                <div class="col-lg-4">
                    <div class="input-group input-group-merge">
                        <span
                            class="input-group-text"
                            id="basic-addon-search31"
                        >
                            <i class="icon-base ri ri-search-line"></i>
                        </span>
                        <x-ui.form.input
                            label="Search"
                            placeholder="Search by menu name ..."
                            model="filter.search"
                        />
                    </div>
                </div>
            </div>
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table table-hover table-sm">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 5px">No</th>
                        <th>Permission Name</th>
                        <th>Group</th>
                        @canany(['edit permission', 'delete permission'])
                            <th class="text-end">Actions</th>
                        @endcanany
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse ($this->lists as $permission)
                        <tr>
                            <td class="text-center">
                                {{ $this->lists->firstItem() + $loop->index }}
                            </td>
                            <td>
                                {{ $permission->name }}
                            </td>
                            <td>
                                {{ $permission->group }}
                            </td>
                            @canany(['edit permission', 'delete permission'])
                                <td class="text-end">
                                    @can('edit permission')
                                        <button
                                            type="button"
                                            class="btn btn-icon btn-warning"
                                            data-bs-toggle="modal"
                                            data-bs-target="#permissionModalForm"
                                            x-on:click="$dispatch('open-permission-form', { comPermission: {{ $permission }} })"
                                        >
                                            <i class="icon-base ri ri-edit-2-line icon-22px text-white"></i>
                                        </button>
                                    @endcan
                                    @can('delete permission')
                                        <button
                                            type="button"
                                            class="btn btn-icon btn-danger"
                                            data-bs-toggle="modal"
                                            data-bs-target="#deleteModal"
                                            data-delete-id={{ "$permission->id" }}
                                        >
                                            <i class="icon-base ri ri-delete-bin-5-line icon-22px text-white"></i>
                                        </button>
                                    @endcan
                                </td>
                            @endcanany
                        </tr>
                    @empty
                        <tr>
                            <td colspan="100%" class="text-center">Data Not Found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($this->lists->hasPages())
            <div class="pt-5 px-5">
                {{ $this->lists->links() }}
            </div>
        @endif
    </div>
    <livewire:rbac::permission.form />
    <x-confirm-delete-modal />
    <script>
        document.addEventListener("livewire:initialized", () => {
            // Listening close modal
            Livewire.on("close-modal", (event) => {
                $("#permissionModalForm").modal("hide");
            });
        }, { once: true });
    </script>
</div>
