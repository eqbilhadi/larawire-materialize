<div
    class="modal fade"
    id="permissionModalForm"
    tabindex="-1"
    aria-hidden="true"
    x-data="{ isLoading: false }"
    x-on:open-permission-form.window="isLoading = true"
    x-on:open-action-form.window="isLoading = true"
    x-on:open-modal.window="isLoading = false"
    wire:ignore.self
>
    {{-- Loading spinner --}}
    <div class="modal-dialog modal-dialog-centered" role="document" x-show="isLoading">
        <div class="modal-content">
            <div class="modal-body d-flex justify-content-center align-items-center" style="height: 300px;">
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Actual form --}}
    <div class="modal-dialog modal-dialog-centered" x-show="!isLoading">
        <div class="modal-content p-3 p-md-5">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-body">

                {{-- Modal title --}}
                <div class="text-center mb-4">
                    <h3 class="mb-0">
                        {{ $actionForm }}
                        {{ $modalType === 'action' ? 'Menu Action' : __('rbac.permission.entity') }}
                    </h3>
                    <p class="text-muted mb-0" style="font-size:13px">
                        @if ($modalType === 'action')
                            Define an action and its route. The permission name is built automatically.
                        @else
                            @if ($actionForm === 'Add') @lang('rbac.permission.subtitle.add')
                            @else @lang('rbac.permission.subtitle.edit') @endif
                        @endif
                    </p>
                </div>

                <form wire:submit="save">

                    {{-- ══════════════════════════════════════════
                         BRANCH A — Standalone permission form
                    ══════════════════════════════════════════ --}}
                    @if ($modalType === 'permission')
                        <x-ui.form.input
                            :label="__('rbac.permission.form.lb.name')"
                            :placeholder="__('rbac.permission.form.ph.name')"
                            model="form.name"
                            modifier="model"
                            wrapperClass="mb-4"
                            class="auto-focus"
                        />
                        <x-ui.form.input
                            :label="__('rbac.permission.form.lb.group')"
                            :placeholder="__('rbac.permission.form.ph.group')"
                            model="form.group"
                            modifier="model"
                            wrapperClass="mb-5"
                        />
                    @endif

                    {{-- ══════════════════════════════════════════
                         BRANCH B — Menu action form
                    ══════════════════════════════════════════ --}}
                    @if ($modalType === 'action')

                        {{-- Menu selector --}}
                        <div class="mb-4">
                            <label class="form-label">Menu <span class="text-danger">*</span></label>
                            <select
                                class="form-select @error('actionData.menu_id') is-invalid @enderror"
                                wire:model.live="actionData.menu_id"
                            >
                                <option value="">— Select menu —</option>
                                @foreach ($menuOptions as $opt)
                                    <option value="{{ $opt['id'] }}">{{ $opt['label'] }}</option>
                                @endforeach
                            </select>
                            @error('actionData.menu_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Action slug --}}
                        <div class="mb-4">
                            <label class="form-label">Action slug <span class="text-danger">*</span></label>
                            <input
                                type="text"
                                class="form-control auto-focus @error('actionData.action') is-invalid @enderror"
                                wire:model.live="actionData.action"
                                placeholder="create, edit, delete, sort, import, export, print, view"
                            >
                            @error('actionData.action')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @if ($this->permissionPreview)
                                <small class="text-info mt-1 d-block">
                                    Permission that will be created:
                                    <code>{{ $this->permissionPreview }}</code>
                                </small>
                            @endif
                        </div>

                        {{-- Label --}}
                        <div class="mb-4">
                            <label class="form-label">Label <span class="text-danger">*</span></label>
                            <input
                                type="text"
                                class="form-control @error('actionData.label') is-invalid @enderror"
                                wire:model="actionData.label"
                                placeholder="Add Teacher"
                            >
                            @error('actionData.label')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Route name <span class="text-danger">*</span></label>
                            <input
                                type="text"
                                class="form-control @error('actionData.route_name') is-invalid @enderror"
                                wire:model="actionData.route_name"
                                placeholder="teach.store"
                            >
                            @error('actionData.route_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    @endif

                    {{-- Submit row --}}
                    <div class="col-12 text-center">
                        <a
                            href="#"
                            class="btn btn-label-secondary me-sm-3 me-1"
                            data-bs-dismiss="modal"
                            aria-label="Close"
                        >
                            @lang('button.cancel')
                        </a>
                        <button
                            type="submit"
                            class="btn btn-primary"
                            wire:loading.attr="disabled"
                            wire:target="save"
                        >
                            <span wire:loading.remove wire:target="save">
                                <i class="fa-regular fa-floppy-disk me-1"></i>
                                @lang('button.save')
                            </span>
                            <span wire:loading wire:target="save">
                                <span class="spinner-grow flex-shrink-0" role="status"></span>
                                <span class="ms-2">@lang('button.loading')</span>
                            </span>
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("livewire:initialized", () => {
            Livewire.on("open-modal", () => $("#permissionModalForm").modal("show"));
            $("#permissionModalForm").on("shown.bs.modal", () => $(".auto-focus").focus());

            // Handle delete confirmation — route to correct Livewire method
            Livewire.on("delete-action",     (e) => @this.deleteAction(e.id));
            Livewire.on("delete-permission",  (e) => @this.deletePermission(e.id));
        }, { once: true });
    </script>
</div>
