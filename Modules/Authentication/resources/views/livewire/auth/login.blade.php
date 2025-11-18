<?php

use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use Modules\Authentication\Livewire\Forms\LoginForm;

new
    #[Layout('components.layouts.auth.cover', ['title' => 'Login'])]
class extends Component {
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false));
    }
}; ?>

<div>
    <h4 class="mb-1">Welcome to {{ config('app.name') }}! 👋</h4>
    <p class="mb-5">
        Please sign-in to your account and start the
        adventure
    </p>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form wire:submit="login" class="mb-5">
        <div
            class="form-floating form-floating-outline mb-5 form-control-validation"
        >
            <input
                type="text"
                class="form-control"
                id="email"
                wire:model="form.email"
                placeholder="Enter your email or username"
                tabindex="1"
                autofocus
            />
            <label for="email">Email or Username</label>
            @error('form.email')
                <span class="text-danger" style="font-size: 11.5px">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-5">
            <div
                class="form-password-toggle form-control-validation"
            >
                <div class="input-group input-group-merge">
                    <div
                        class="form-floating form-floating-outline"
                    >
                        <input
                            type="password"
                            id="password"
                            class="form-control"
                            wire:model="form.password"
                            placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                            aria-describedby="password"
                            tabindex="2"
                        />
                        <label for="password"
                            >Password</label
                        >
                    </div>
                    <span
                        class="input-group-text cursor-pointer"
                        ><i
                            class="icon-base ri ri-eye-off-line icon-20px"
                        ></i
                    ></span>
                </div>
            </div>
            @error('form.password')
                <span class="text-danger" style="font-size: 11.5px">{{ $message }}</span>
            @enderror
        </div>
        <div
            class="mb-5 d-flex justify-content-between mt-5"
        >
            <div class="form-check mt-2">
                <input
                    class="form-check-input"
                    type="checkbox"
                    id="remember-me"
                    wire:model="form.remember"
                />
                <label
                    class="form-check-label"
                    for="remember-me"
                >
                    Remember Me
                </label>
            </div>
            @if (Route::has('password.request'))
                <a
                    href="{{ route('password.request') }}"
                    class="float-end mb-1 mt-2"
                >
                    <span>Forgot Password?</span>
                </a>
            @endif
        </div>
        <button class="btn btn-primary d-grid w-100" tabindex="3">
            Sign in
        </button>
    </form>
    @if (Route::has('register'))
        <p class="text-center">
            <span>New on our platform?</span>
            <a href="{{ route('register') }}">
                <span>Create an account</span>
            </a>
        </p>
    @endif
</div>
