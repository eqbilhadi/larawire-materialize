<div>
    <div class="card">
        <div class="card-body">
            <div class="row d-flex align-items-center">
                <div class="col-6">
                    <h5 class="card-title mb-2">
                        @if ($sysMenu->exists) Edit @else Create @endif Navigation
                    </h5>
                    <h6 class="card-subtitle text-muted fw-light">
                        Form to @if ($sysMenu->exists) edit @else create @endif a navigation
                    </h6>
                </div>
                <div class="col-6 text-end">
                    <a href="{{ route('rbac.nav.index') }}" class="btn btn-primary">
                        <i class="ri ri-arrow-left-circle-line me-sm-1 icon-20px"></i>
                        <span class="d-none d-sm-inline align-self-center">
                            Back
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
                            label="Icon"
                            placeholder="Icon base from https://icon-sets.iconify.design/ri"
                            model="form.icon"
                            modifier="model"
                            wrapperClass="mb-5"
                        />
                        <x-ui.form.select
                            label="Nav Parent"
                            model="form.parent_id"
                            :options="$options['parents_nav']"
                            wrapperClass="mb-5"
                        />
                        <x-ui.form.input
                            label="English label name"
                            placeholder="English label of the menu"
                            model="form.label_name_en"
                            modifier="model"
                            wrapperClass="mb-5"
                        />
                        <x-ui.form.input
                            label="Portuguese label name"
                            placeholder="Portuguese label of the menu"
                            model="form.label_name_pt"
                            modifier="model"
                            wrapperClass="mb-5"
                        />
                        <x-ui.form.input
                            label="Tetun label name"
                            placeholder="Tetun label of the menu"
                            model="form.label_name_tl"
                            modifier="model"
                            wrapperClass="mb-5"
                        />
                        <x-ui.form.input
                            label="Controller class name"
                            placeholder="The name of the controller that handles the navigation"
                            model="form.controller_name"
                            modifier="model"
                            wrapperClass="mb-5"
                        />
                        <x-ui.form.input
                            label="Route name"
                            placeholder="The name of the route that handles the navigation"
                            model="form.route_name"
                            modifier="model"
                            wrapperClass="mb-5"
                        />
                        <x-ui.form.input
                            label="Url"
                            placeholder="The url that handles the navigation"
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
                                    <span class="switch-label">Divider</span>
                                </div>
                                <span class="fw-light text-muted">{{ $form['is_divider'] ? "Enabled" : "Disabled" }}</span>
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
                                    <span class="switch-label">Active Menu</span>
                                </div>
                                <span class="fw-light text-muted">{{ $form['is_active'] ? "Active" : "Inactive" }}</span>
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
                                Reset
                            </span>
                            <span wire:loading wire:target="resetForm">
                                <i
                                    class="fa-solid fa-spinner-third fa-spin"
                                    style="--fa-animation-duration: 0.7s"
                                ></i>
                                Reset
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
                                Save
                            </span>
                            <span wire:loading wire:target="save">
                                <span class="spinner-grow flex-shrink-0" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </span>
                                <span class="flex-grow-1 ms-2">
                                    Loading...
                                </span>
                            </span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
