<div>
    <div class="card">
        <div class="card-body">
            <div class="row d-flex align-items-center">
                <div class="col-6">
                    <h5 class="card-title mb-2">
                        @if ($sysMenu->exists) @lang('rbac.nav.title.edit') @else @lang('rbac.nav.title.add') @endif
                    </h5>
                    <h6 class="card-subtitle text-muted fw-light">
                        @if ($sysMenu->exists) @lang('rbac.nav.subtitle.edit') @else @lang('rbac.nav.subtitle.add') @endif
                    </h6>
                </div>
                <div class="col-6 text-end">
                    <a href="{{ route('rbac.nav.index') }}" class="btn btn-primary">
                        <i class="ri ri-arrow-left-circle-line me-sm-1 icon-20px"></i>
                        <span class="d-none d-sm-inline align-self-center">
                            @lang('button.back')
                        </span>
                    </a>
                </div>
            </div>
        </div>

        <div class="card-body border-top">
            <form wire:submit.prevent="save">
                <div class="row justify-content-center gap-4">
                    <div class="col-lg-8">
                        <x-ui.form.input
                            :label="__('rbac.nav.form.lb.icon')"
                            :placeholder="__('rbac.nav.form.ph.icon')"
                            model="form.icon"
                            modifier="model"
                            wrapperClass="mb-5"
                        />
                        <x-ui.form.select
                            :label="__('rbac.nav.form.lb.nav_parent')"
                            model="form.parent_id"
                            :placeholder="__('rbac.nav.form.ph.nav_parent')"
                            :options="$options['parents_nav']"
                            wrapperClass="mb-5"
                        />
                        <x-ui.form.input
                            :label="__('rbac.nav.form.lb.name_en')"
                            :placeholder="__('rbac.nav.form.ph.name_en')"
                            model="form.label_name_en"
                            modifier="model"
                            wrapperClass="mb-5"
                        />
                        <x-ui.form.input
                            :label="__('rbac.nav.form.lb.name_pt')"
                            :placeholder="__('rbac.nav.form.ph.name_pt')"
                            model="form.label_name_pt"
                            modifier="model"
                            wrapperClass="mb-5"
                        />
                        <x-ui.form.input
                            :label="__('rbac.nav.form.lb.name_tl')"
                            :placeholder="__('rbac.nav.form.ph.name_tl')"
                            model="form.label_name_tl"
                            modifier="model"
                            wrapperClass="mb-5"
                        />
                        <x-ui.form.input
                            :label="__('rbac.nav.form.lb.controller')"
                            :placeholder="__('rbac.nav.form.ph.controller')"
                            model="form.controller_name"
                            modifier="model"
                            wrapperClass="mb-5"
                        />
                        <x-ui.form.input
                            :label="__('rbac.nav.form.lb.route')"
                            :placeholder="__('rbac.nav.form.ph.route')"
                            model="form.route_name"
                            modifier="model"
                            wrapperClass="mb-5"
                        />
                        <x-ui.form.input
                            :label="__('rbac.nav.form.lb.url')"
                            :placeholder="__('rbac.nav.form.ph.url')"
                            model="form.url"
                            modifier="model"
                            wrapperClass="mb-5"
                        />
                        <label class="switch switch-square w-100 form-control mb-5">
                            <div class="d-flex justify-content-between">
                                <div class="d-flex align-items-center">
                                    <input type="checkbox" class="switch-input" wire:model.live='form.is_divider' />
                                    <span class="switch-toggle-slider">
                                        <span class="switch-on"></span>
                                        <span class="switch-off"></span>
                                    </span>
                                    <span class="switch-label">{{ __('rbac.nav.form.lb.divider') }}</span>
                                </div>
                                <span class="fw-light text-muted">{{ $form['is_divider'] ? __('labels.enabled') : __('labels.disabled') }}</span>
                            </div>
                            @error('form.is_divider')
                                <div class="invalid-feedback d-block">{{ $message }}
                            @enderror
                        </label>
                        <label class="switch switch-square w-100 form-control mb-5">
                            <div class="d-flex justify-content-between">
                                <div class="d-flex align-items-center">
                                    <input type="checkbox" class="switch-input" wire:model.live='form.is_active' />
                                    <span class="switch-toggle-slider">
                                        <span class="switch-on"></span>
                                        <span class="switch-off"></span>
                                    </span>
                                    <span class="switch-label">{{ __('rbac.nav.form.lb.active') }}</span>
                                </div>
                                <span class="fw-light text-muted">{{ $form['is_active'] ? __('labels.active') : __('labels.inactive') }}</span>
                            </div>
                        </label>
                        @error('form.is_divider')
                            <div class="invalid-feedback d-block">{{ $message }}
                        @enderror
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-lg-8 text-end">
                        <button
                            type="button"
                            class="btn btn-light mt-3 me-2"
                            wire:click="resetForm"
                            wire:loading.attr="disabled"
                            wire:target="resetForm"
                        >
                            <span wire:loading.remove wire:target="resetForm">
                                <i class="fa-regular fa-rotate-left me-1"></i>
                                @lang('button.reset')
                            </span>
                            <span wire:loading wire:target="resetForm">
                                <span class="spinner-grow flex-shrink-0" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </span>
                                <span class="flex-grow-1 ms-2">
                                    @lang('button.loading')
                                </span>
                            </span>
                        </button>
                        <button
                            type="submit"
                            class="btn btn-primary mt-3"
                            wire:loading.attr="disabled"
                            wire:target="save"
                        >
                            <span wire:loading.remove wire:target="save">
                                <i class="fa-regular fa-floppy-disk me-1"></i>
                                @if ($sysMenu->exists) @lang('button.update') @else @lang('button.save') @endif
                            </span>
                            <span wire:loading wire:target="save">
                                <span class="spinner-grow flex-shrink-0" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </span>
                                <span class="flex-grow-1 ms-2">
                                    @lang('button.loading')
                                </span>
                            </span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
