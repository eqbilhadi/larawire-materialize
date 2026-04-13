<div>
    <div class="card">
        <div class="card-body">
            <div class="row d-flex align-items-center">
                <div class="col-6">
                    <h5 class="card-title mb-2">{{ __('rbac.nav.title.list') }}</h5>
                    <h6 class="card-subtitle text-muted fw-light">{{ __('rbac.nav.subtitle.list') }}</h6>
                </div>
                @canany(['create menu', 'sort menu'])
                    <div class="col-6 text-end">
                        @can('sort menu')
                            <a href="{{ route('rbac.nav.sort') }}" class="btn btn-primary">
                                <i class="ri ri-sort-asc me-sm-1 icon-20px"></i>
                                <span class="d-none d-sm-inline">
                                    {{ __('button.sort') }}  {{ __('rbac.nav.entity') }}
                                </span>
                            </a>
                        @endcan
                        @can('create menu')
                            <a href="{{ route('rbac.nav.create') }}" class="btn btn-primary">
                                <i class="ri ri-add-circle-line me-sm-1 icon-20px"></i>
                                <span class="d-none d-sm-inline">
                                    {{ __('button.add') }}  {{ __('rbac.nav.entity') }}
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
                            :label="__('rbac.nav.filter.lb_search')"
                            :placeholder="__('rbac.nav.filter.ph_search')"
                            model="filter.search"
                        />
                    </div>
                </div>
                <div class="col-lg-3">
                    <x-ui.form.select
                        :label="__('rbac.nav.filter.lb_status')"
                        model="filter.is_active"
                        :placeholder="__('rbac.nav.filter.ph_status')"
                        :options="[
                            'true' => __('labels.active'),
                            'false' => __('labels.inactive'),
                        ]"
                    />
                </div>
            </div>
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>{{ __('rbac.nav.table.header_name') }}</th>
                        <th>{{ __('rbac.nav.table.header_controller') }}</th>
                        <th>{{ __('rbac.nav.table.header_route') }}</th>
                        <th>{{ __('rbac.nav.table.header_url') }}</th>
                        <th class="text-center">Status</th>
                        @canany(['sort menu', 'edit menu', 'delete menu'])
                            <th class="text-end">{{ __('rbac.nav.table.header_actions') }}</th>
                        @endcanany
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse ($this->lists as $nav)
                        <x-rbac::menu-item :menu="$nav" :$loop />
                    @empty
                        <tr>
                            <td colspan="100%" class="text-center">{{ __('labels.table_no_data') }}</td>
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
