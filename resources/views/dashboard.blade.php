{{-- <x-layouts.app>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("You're logged in!") }}
                </div>
            </div>
        </div>
    </div>
</x-layouts.app> --}}
<x-layouts.app.base title="Dashboard">
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Card Border Shadow -->
        <div class="row g-6">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="me-1">
                                <p class="text-heading mb-1">Session</p>
                                <div class="d-flex align-items-center">
                                    <h4 class="mb-1 me-2">21,459</h4>
                                    <p class="text-success mb-1">(+29%)</p>
                                </div>
                                <small class="mb-0">Total Users</small>
                            </div>
                            <div class="avatar">
                                <div class="avatar-initial bg-label-primary rounded-3">
                                <div class="icon-base ri ri-group-line icon-26px"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app.base>
