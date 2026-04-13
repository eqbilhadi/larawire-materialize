<div
    class="modal fade"
    id="permissionModalForm"
    tabindex="-1"
    aria-hidden="true"
    x-data="{ isLoading: false }"
    x-on:open-permission-form.window="isLoading = true;"
    x-on:open-modal.window="isLoading = false;"
    wire:ignore.self
>
    <div
        class="modal-dialog modal-dialog-centered"
        x-show="!isLoading"
    >
        <div class="modal-content p-3 p-md-5">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <h3 class="mb-0">
                        @if ($actionForm == 'Add')
                            @lang('rbac.permission.title.add')
                        @else
                            @lang('rbac.permission.title.edit')
                        @endif
                    </h3>
                    <p>
                        @if ($actionForm == 'Add')
                            @lang('rbac.permission.subtitle.add')
                        @else
                            @lang('rbac.permission.subtitle.edit')
                        @endif
                    </p>
                </div>
                <form wire:submit="save">
                    <x-ui.form.input
                        :label="__('rbac.permission.form.lb.name')"
                        :placeholder="__('rbac.permission.form.ph.name')"
                        model="form.name"
                        modifier="model"
                        wrapperClass="mb-5"
                        class="auto-focus"
                    />
                    <x-ui.form.input
                        :label="__('rbac.permission.form.lb.group')"
                        :placeholder="__('rbac.permission.form.ph.group')"
                        model="form.group"
                        modifier="model"
                        wrapperClass="mb-5"
                    />
                    <div class="col-12 text-center demo-vertical-spacing">
                        <a
                            href="{{ route('rbac.permission.index') }}"
                            class="btn btn-label-secondary me-sm-3 me-1"
                            data-bs-dismiss="modal"
                            aria-label="Close"
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
                </form>
            </div>
        </div>
    </div>
    <div class="modal-dialog modal-dialog-centered" role="document" x-show="isLoading">
        <div class="modal-content">
            <div class="modal-body d-flex justify-content-center align-items-center" style="height: 418px;">
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener("livewire:initialized", () => {
            // Listening open modal
            Livewire.on("open-modal", (event) => {
                $("#permissionModalForm").modal("show");
            });

            // Initialize form modal focus when shown
            $("#permissionModalForm").on("shown.bs.modal", function (e) {
                $(".auto-focus").focus();
            });
        }, { once: true });
    </script>
</div>
