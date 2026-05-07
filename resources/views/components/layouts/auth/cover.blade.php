@props(['title' => null])
<x-layouts.master {{ $attributes }} :$title>
    <style>
        .authentication-wrapper {
            min-height: 100vh;
        }

        .authentication-inner {
            height: 100%;
            min-height: 100vh;
        }

        .auth-left {
            height: 100%;
        }

        .authentication-wrapper .authentication-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            inset-block-end: 0%;
        }
    </style>
    <div class="authentication-wrapper authentication-cover">
        <!-- Logo -->
        <a
            href="{{ route('home') }}"
            class="auth-cover-brand d-flex align-items-center gap-2"
        >
            <span class="app-brand-logo demo">
                <span class="text-primary">
                    <img src="{{ asset('assets/img/icons/app/e-proc.png') }}" alt="" width="75">
                </span>
            </span>
            <span class="app-brand-text demo text-heading fw-semibold"
                >{{ config('app.name') }}</span
            >
        </a>
        <!-- /Logo -->
        <div class="authentication-inner row m-0">
            <!-- /Left Section -->
            <div
                class="d-none d-lg-flex col-lg-7 col-xl-8 align-items-center justify-content-center p-12 pb-2"
            >
                <img
                    src="../../assets/img/illustrations/auth-login-illustration-light.png"
                    class="auth-cover-illustration w-100"
                    alt="auth-illustration"
                    data-app-light-img="illustrations/auth-login-illustration-light.png"
                    data-app-dark-img="illustrations/auth-login-illustration-dark.png"
                />
                <img
                    alt="mask"
                    src="../../assets/img/illustrations/auth-basic-login-mask-light.png"
                    class="authentication-image d-none d-lg-block"
                    data-app-light-img="illustrations/auth-basic-login-mask-light.png"
                    data-app-dark-img="illustrations/auth-basic-login-mask-dark.png"
                />
            </div>
            <!-- /Left Section -->
            <div class="position-absolute top-4 end-0 p-3 text-end" style="z-index: 10; width: 80px; top: 1rem; inset-inline-end: 1rem;">
                <x-ui.lang-switcher />
            </div>

            <!-- Login -->
            <div
                class="d-flex col-12 col-lg-5 col-xl-4 align-items-center authentication-bg position-relative py-sm-12 px-12 py-6"
            >
                <div class="w-px-400 mx-auto pt-12 pt-lg-0">
                    {{ $slot }}
                </div>
            </div>
            <!-- /Login -->
        </div>
    </div>
</x-layouts.master>
