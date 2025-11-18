<div>
    <div class="card">
        <div class="card-body">
            <div class="row d-flex align-items-center">
                <div class="col-6">
                    <h5 class="card-title mb-2">Navigation Table</h5>
                    <h6 class="card-subtitle text-muted fw-light">Manage the structure and appearance of navigation items in the application.</h6>
                </div>
                @canany(['create menu', 'sort menu'])
                    <div class="col-6 text-end">
                        @can('sort menu')
                            <a href="{{ route('rbac.nav.sort') }}" class="btn btn-primary">
                                <i class="ri ri-sort-asc me-sm-1 icon-20px"></i>
                                <span class="d-none d-sm-inline">
                                    Sort Navigation
                                </span>
                            </a>
                        @endcan
                        @can('create menu')
                            <a href="{{ route('rbac.nav.create') }}" class="btn btn-primary">
                                <i class="ri ri-add-circle-line me-sm-1 icon-20px"></i>
                                <span class="d-none d-sm-inline">
                                    Add Navigation
                                </span>
                            </a>
                        @endcan
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
                <div class="col-lg-3">
                    <x-ui.form.select
                        label="Menu Status"
                        model="filter.is_active"
                        :options="[
                            'true' => 'Active',
                            'false' => 'Inactive',
                        ]"
                    />
                </div>
            </div>
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Label Name</th>
                        <th>Controller</th>
                        <th>Route</th>
                        <th>URL</th>
                        <th class="text-center">Status</th>
                        @canany(['sort menu', 'edit menu', 'delete menu'])
                            <th class="text-end">Actions</th>
                        @endcanany
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse ($this->lists as $nav)
                        <x-rbac::menu-item :menu="$nav" :$loop />
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
    <x-confirm-delete-modal />
</div>
