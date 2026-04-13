<?php

use App\Models\SysUser;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new
    #[Layout('components.layouts.auth.cover', ['title' => 'Register'])]
class extends Component {
    public string $name = '';
    public string $email = '';
    public string $username = '';
    public string $password = '';
    public string $password_confirmation = '';
    public bool $terms_condition = false;

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.SysUser::class],
            'username' => ['required', 'string', 'lowercase', 'min:3', 'max:255', 'unique:'. SysUser::class . ',username'],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered($user = SysUser::create($validated)));

        Auth::login($user);

        $this->redirect(route('dashboard', absolute: false));
    }
}; ?>

<div>
    <h4 class="mb-1">@lang('auth.register.title') {{ config('app.name') }}! 👋</h4>
    <p class="mb-5">
        @lang('auth.register.subtitle')
    </p>
    <form wire:submit="register" class="mb-5">
        <div
            class="form-floating form-floating-outline mb-5"
        >
            <input
                type="text"
                class="form-control"
                id="name"
                wire:model="name"
                placeholder="@lang('auth.form.ph.fullname')"
                autofocus />
            <label for="name">@lang('auth.form.lb.fullname')</label>
            @error('name')
                <span class="text-danger" style="font-size: 11.5px">{{ $message }}</span>
            @enderror
        </div>
        <div
            class="form-floating form-floating-outline mb-5"
        >
            <input
                type="text"
                class="form-control"
                id="email"
                wire:model="email"
                placeholder="@lang('auth.form.ph.email')"
                autofocus />
            <label for="email">@lang('auth.form.lb.email')</label>
            @error('email')
                <span class="text-danger" style="font-size: 11.5px">{{ $message }}</span>
            @enderror
        </div>
        <div
            class="form-floating form-floating-outline mb-5"
        >
            <input
                type="text"
                class="form-control"
                id="username"
                wire:model="username"
                placeholder="@lang('auth.form.ph.username')"
                autofocus />
            <label for="username">@lang('auth.form.lb.username')</label>
            @error('username')
                <span class="text-danger" style="font-size: 11.5px">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-5 form-password-toggle">
            <div class="input-group input-group-merge">
                <div class="form-floating form-floating-outline">
                    <input
                        type="password"
                        id="password"
                        class="form-control"
                        wire:model="password"
                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                        aria-describedby="password" />
                    <label for="password">@lang('auth.form.lb.password')</label>
                </div>
                <span class="input-group-text cursor-pointer"
                    ><i class="icon-base ri ri-eye-off-line"></i
                ></span>
            </div>
            @error('password')
                <span class="text-danger" style="font-size: 11.5px">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-5 form-password-toggle">
            <div class="input-group input-group-merge">
                <div class="form-floating form-floating-outline">
                    <input
                        type="password"
                        id="password_confirmation"
                        class="form-control"
                        wire:model="password_confirmation"
                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                        aria-describedby="password_confirmation" />
                    <label for="password_confirmation">@lang('auth.form.lb.password_confirmation')</label>
                </div>
                <span class="input-group-text cursor-pointer"
                    ><i class="icon-base ri ri-eye-off-line"></i
                ></span>
            </div>
            @error('password_confirmation')
                <span class="text-danger" style="font-size: 11.5px">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-5">
            <div class="form-check mt-2">
                <input
                    class="form-check-input"
                    type="checkbox"
                    id="terms-conditions"
                    wire:model.live="terms_condition"
                    name="terms" />
                <label class="form-check-label" for="terms-conditions">
                    @lang('auth.register.privacy_policy.prefix')
                    <a href="javascript:void(0);">@lang('auth.register.privacy_policy.link')</a>
                </label>
            </div>
        </div>
        <button class="btn btn-primary d-grid w-100" @disabled(!$terms_condition)>@lang('button.register')</button>
    </form>
    <p class="text-center mb-5">
        <span>@lang('auth.register.already_account')</span>
        <a href="{{ route('login') }}">
            <span>@lang('auth.register.sign_in')</span>
        </a>
    </p>


</div>
