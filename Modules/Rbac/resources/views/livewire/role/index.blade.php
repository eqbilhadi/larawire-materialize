<div>
    <div class="card">
        <div class="card-body">
            <div class="row d-flex align-items-center">
                <div class="col-6">
                    <h5 class="card-title mb-2">@lang('rbac.role.title.list')</h5>
                    <h6 class="card-subtitle text-muted fw-light">@lang('rbac.role.subtitle.list')</h6>
                </div>
                @can('create role')
                    <div class="col-6 text-end">
                        <a href="{{ route('rbac.role.create') }}" class="btn btn-primary">
                            <i class="ri ri-add-circle-line me-sm-1 icon-20px"></i>
                            <span class="d-none d-sm-inline">
                                @lang('button.add') @lang('rbac.role.entity')
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
                            :label="__('rbac.role.filter.lb_search')"
                            :placeholder="__('rbac.role.filter.ph_search')"
                            model="filter.search"
                        />
                    </div>
                </div>
            </div>
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 5px">@lang('rbac.role.table.header_no')</th>
                        <th>@lang('rbac.role.table.header_name')</th>
                        <th>@lang('rbac.role.table.header_guard')</th>
                        @canany(['edit role', 'delete role'])
                            <th class="text-end">@lang('rbac.role.table.header_actions')</th>
                        @endcanany
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse ($this->lists as $role)
                        <tr>
                            <td class="text-center">
                                {{ $this->lists->firstItem() + $loop->index }}
                            </td>
                            <td>
                                {{ $role->name }}
                            </td>
                            <td>
                                {{ $role->guard_name }}
                            </td>
                            @canany(['edit role', 'delete role'])
                                <td class="text-end">
                                    @can('edit role')
                                        <a
                                            href="{{ route('rbac.role.edit', $role->id) }}"
                                            class="btn btn-icon btn-warning"
                                        >
                                            <i class="icon-base ri ri-edit-2-line icon-22px text-white"></i>
                                        </a>
                                    @endcan
                                    @can('delete role')
                                        <button
                                            type="button"
                                            class="btn btn-icon btn-danger"
                                            data-bs-toggle="modal"
                                            data-bs-target="#deleteModal"
                                            data-delete-id={{ "$role->id" }}
                                        >
                                            <i class="icon-base ri ri-delete-bin-5-line icon-22px text-white"></i>
                                        </button>
                                    @endcan
                                </td>
                            @endcanany
                        </tr>
                    @empty
                        <tr>
                            <td colspan="100%" class="text-center">@lang('labels.table_no_data')</td>
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
