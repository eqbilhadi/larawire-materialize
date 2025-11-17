<div>
        <div class="card">
        <div class="card-body">
            <div class="row d-flex align-items-center">
                <div class="col-6">
                    <h5 class="card-title mb-2">User Management</h5>
                    <h6 class="card-subtitle text-muted fw-light">Manage existing users by editing, deleting, or blocking their access.</h6>
                </div>
                @can('create user')
                    <div class="col-6 text-end">
                        <a href="{{ route('rbac.user.create') }}" class="btn btn-primary">
                            <i class="ri ri-add-circle-line me-sm-1 icon-20px"></i>
                            <span class="d-none d-sm-inline">
                                Add User
                            </span>
                        </a>
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
                <div class="col-lg-2">
                    <x-ui.form.select
                        label="User Status"
                        model="filter.status"
                        :options="[
                            'true' => 'Active',
                            'false' => 'Inactive',
                        ]"
                    />
                </div>
                <div class="col-lg-2">
                    <x-ui.form.select
                        label="User Gender"
                        model="filter.gender"
                        :options="[
                            'l' => 'Male',
                            'p' => 'Female',
                        ]"
                    />
                </div>
            </div>
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 5px">No</th>
                        <th>User Info</th>
                        <th>Account Info</th>
                        <th>Gender</th>
                        <th class="text-center">Status</th>
                        @canany(['edit user', 'delete user'])
                            <th class="text-end">Actions</th>
                        @endcanany
                    </tr>
                </thead>
                <tbody>
                    @forelse ($this->lists as $user)
                        <tr>
                            <td class="text-center">{{ $this->lists->firstItem() + $loop->index }}</td>
                            <td>
                                <div class="d-flex justify-content-start align-items-center user-name">
                                    <div class="avatar-wrapper me-2">
                                        <x-ui.avatar :$user size='2.5' />
                                    </div>
                                    <div class="d-flex flex-column">
                                        <a
                                            href="#"
                                            class="text-heading text-truncate"
                                        >
                                            <span class="fw-medium">{{ $user->name }}</span>
                                        </a>
                                        <small>{{ $user->main_role }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <h6 class="mb-0 text-truncate">{{ $user->username }}</h6>
                                <small class="text-truncate">{{ $user->email }}</small>
                            </td>
                            <td>
                                {{ $user->gender == "l" ? "Male" : "Female" }}
                            </td>
                            <td class="text-center">
                                @if ($user->is_active)
                                    <span class="badge rounded-pill text-bg-dark">Active</span>
                                @else
                                    <span class="badge rounded-pill text-bg-light">Inactive</span>
                                @endif
                            </td>
                            @canany(['edit user', 'delete user'])
                                <td class="text-end">
                                    @can('edit user')
                                        <a
                                            href="{{ route('rbac.user.edit', $user->id) }}"
                                            class="btn btn-icon btn-warning"
                                        >
                                            <i class="icon-base ri ri-edit-2-line icon-22px text-white"></i>
                                        </a>
                                    @endcan
                                    @can('delete user')
                                        <button
                                            type="button"
                                            class="btn btn-icon btn-danger"
                                            data-bs-toggle="modal"
                                            data-bs-target="#deleteModal"
                                            data-delete-id={{ "$user->id" }}
                                        >
                                            <i class="icon-base ri ri-delete-bin-5-line icon-22px text-white"></i>
                                        </button>
                                    @endcan
                                </td>
                            @endcanany
                        </tr>
                    @empty
                        <td colspan="100%" class="text-center">Data not Found</td>
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
    <x-confirm-delete-modal />
</div>
