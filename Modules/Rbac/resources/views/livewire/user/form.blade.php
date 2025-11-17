<div>
    <div class="card">
        <div class="card-body">
            <div class="row d-flex align-items-center">
                <div class="col-6">
                    <h5 class="card-title mb-2">
                        @if ($sysUser->exists) Edit @else Create @endif User
                    </h5>
                    <h6 class="card-subtitle text-muted fw-light">
                        Fill in the required information to @if ($sysUser->exists) add @else edit @endif a user.
                    </h6>
                </div>
                <div class="col-6 text-end">
                    <a href="{{ route('rbac.user.index') }}" class="btn btn-primary">
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
                <div class="row">
                    <div class="col-lg-4">
                        <x-ui.form.input
                            label="Email"
                            placeholder="Email address of the user"
                            model="form.email"
                            modifier="model"
                            wrapperClass="mb-5"
                        />
                        <x-ui.form.input
                            label="Username"
                            placeholder="Username of the user"
                            model="form.username"
                            modifier="model"
                            wrapperClass="mb-5"
                        />
                        <x-ui.form.input
                            label="Password"
                            placeholder="Password of the user"
                            model="form.password"
                            modifier="model"
                            wrapperClass="mb-5"
                        />
                        <x-ui.form.select
                            label="Role"
                            model="form.roles"
                            placeholder="Select role"
                            :options="$options['roles']"
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
                                    <span class="switch-label">Status Active User</span>
                                </div>
                                <span class="fw-light text-muted">{{ $form['is_active'] ? "Active" : "Inactive" }}</span>
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
                                    label="Fullname"
                                    placeholder="Fullname of the user"
                                    model="form.name"
                                    modifier="model"
                                    wrapperClass="mb-5"
                                />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <x-ui.form.input
                                    label="Birthplace"
                                    placeholder="Birth place address of the user"
                                    model="form.birthplace"
                                    modifier="model"
                                    wrapperClass="mb-5"
                                />
                            </div>
                            <div class="col-lg-6">
                                <x-ui.form.input
                                    label="Birthdate"
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
                                    label="Phone"
                                    placeholder="Phone of the user "
                                    model="form.phone"
                                    modifier="model"
                                    wrapperClass="mb-5"
                                />
                            </div>
                            <div class="col-lg-6 mb-5">
                                <div class="form-control">
                                    <div class="form-check form-check-inline mb-0">
                                        <input class="form-check-input" type="radio" name="gender" id="male" value="l" wire:model='form.gender'>
                                        <label class="form-check-label" for="male">Male</label>
                                    </div>
                                    <div class="form-check form-check-inline mb-0">
                                        <input class="form-check-input" type="radio" name="gender" id="female" value="p" wire:model='form.gender'>
                                        <label class="form-check-label" for="female">Female</label>
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
                                    label="Address"
                                    placeholder="Address of the user "
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
