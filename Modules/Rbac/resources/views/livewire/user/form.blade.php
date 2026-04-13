<div>
    <div class="card">
        <div class="card-body">
            <div class="row d-flex align-items-center">
                <div class="col-6">
                    <h5 class="card-title mb-2">
                        @if ($sysUser->exists) @lang('rbac.user.title.edit') @else @lang('rbac.user.title.add') @endif
                    </h5>
                    <h6 class="card-subtitle text-muted fw-light">
                        @if ($sysUser->exists) @lang('rbac.user.subtitle.edit') @else @lang('rbac.user.subtitle.add') @endif
                    </h6>
                </div>
                <div class="col-6 text-end">
                    <a href="{{ route('rbac.user.index') }}" class="btn btn-primary">
                        <i class="ri ri-arrow-left-circle-line me-sm-1 icon-20px"></i>
                        <span class="d-none d-sm-inline align-self-center">
                            @lang('button.back')
                        </span>
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body border-top">
            <form wire:submit="save">
                <div class="row">
                    <div class="col-lg-4">
                        <x-ui.form.input
                            :label="__('rbac.user.form.lb.email')"
                            :placeholder="__('rbac.user.form.ph.email')"
                            model="form.email"
                            modifier="model"
                            wrapperClass="mb-5"
                        />
                        <x-ui.form.input
                            :label="__('rbac.user.form.lb.username')"
                            :placeholder="__('rbac.user.form.ph.username')"
                            model="form.username"
                            modifier="model"
                            wrapperClass="mb-5"
                        />
                        <x-ui.form.input
                            :label="__('rbac.user.form.lb.password')"
                            :placeholder="__('rbac.user.form.ph.password')"
                            model="form.password"
                            modifier="model"
                            wrapperClass="mb-5"
                        />
                        <x-ui.form.select
                            :label="__('rbac.user.form.lb.role')"
                            model="form.roles"
                            :placeholder="__('rbac.user.form.ph.role')"
                            :options="$options['roles']"
                            wrapperClass="mb-5"
                        />
                        <x-ui.form.select
                            :label="__('rbac.user.form.lb.district')"
                            model="form.district_code"
                            :placeholder="__('rbac.user.form.ph.district')"
                            :options="$options['districts']"
                            wrapperClass="mb-5"
                        />
                        <label class="switch switch-square w-100 form-control mb-5">
                            <div class="d-flex justify-content-between">
                                <div class="d-flex align-items-center">
                                    <input type="checkbox" class="switch-input" wire:model.live='form.is_active' />
                                    <span class="switch-toggle-slider">
                                        <span class="switch-on"></span>
                                        <span class="switch-off"></span>
                                    </span>
                                    <span class="switch-label">{{ __('rbac.user.form.lb.status') }}</span>
                                </div>
                                <span class="fw-light text-muted">{{ $form['is_active'] ? __('labels.active') : __('labels.inactive') }}</span>
                            </div>
                            @error('form.is_active')
                                <div class="invalid-feedback d-block">{{ $message }}
                            @enderror
                        </label>
                    </div>
                    <div class="col-lg-8">
                        <div class="row">
                            <div class="col-lg-12">
                                <x-ui.form.input
                                    :label="__('rbac.user.form.lb.name')"
                                    :placeholder="__('rbac.user.form.ph.name')"
                                    model="form.name"
                                    modifier="model"
                                    wrapperClass="mb-5"
                                />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <x-ui.form.input
                                    :label="__('rbac.user.form.lb.birthplace')"
                                    :placeholder="__('rbac.user.form.ph.birthplace')"
                                    model="form.birthplace"
                                    modifier="model"
                                    wrapperClass="mb-5"
                                />
                            </div>
                            <div class="col-lg-6">
                                <x-ui.form.input
                                    :label="__('rbac.user.form.lb.birthdate')"
                                    type="date"
                                    model="form.birthdate"
                                    modifier="model"
                                    wrapperClass="mb-5"
                                />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <x-ui.form.input
                                    :label="__('rbac.user.form.lb.phone')"
                                    :placeholder="__('rbac.user.form.ph.phone')"
                                    model="form.phone"
                                    modifier="model"
                                    wrapperClass="mb-5"
                                />
                            </div>
                            <div class="col-lg-6 mb-5">
                                <div class="form-control">
                                    <div class="form-check form-check-inline mb-0">
                                        <input class="form-check-input" type="radio" name="gender" id="male" value="l" wire:model='form.gender'>
                                        <label class="form-check-label" for="male">{{ __('labels.gender.male') }}</label>
                                    </div>
                                    <div class="form-check form-check-inline mb-0">
                                        <input class="form-check-input" type="radio" name="gender" id="female" value="p" wire:model='form.gender'>
                                        <label class="form-check-label" for="female">{{ __('labels.gender.female') }}</label>
                                    </div>
                                </div>
                                @error('form.gender')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <x-ui.form.textarea
                                    :label="__('rbac.user.form.lb.address')"
                                    :placeholder="__('rbac.user.form.ph.address')"
                                    model="form.phone"
                                    modifier="model"
                                    wrapperClass="mb-5"
                                    class="h-px-120"
                                />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-lg-12 text-end">
                        <a
                            type="button"
                            class="btn btn-light mt-3 me-2"
                            href="{{ route('rbac.user.index') }}"
                        >
                            @lang('button.cancel')
                        </a>
                        <button
                            type="submit"
                            class="btn btn-primary mt-3"
                            wire:loading.attr="disabled"
                            wire:target="save"
                        >
                            <span wire:loading.remove wire:target="save">
                                <i class="fa-regular fa-floppy-disk me-1"></i>
                                @lang('button.save')
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
