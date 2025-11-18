<?php

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Volt\Component;

new
    #[Layout('components.layouts.auth.cover', ['title' => 'Reset Password'])]
class extends Component {
    #[Locked]
    public string $token = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Mount the component.
     */
    public function mount(string $token): void
    {
        $this->token = $token;

        $this->email = request()->string('email');
    }

    /**
     * Reset the password for the given user.
     */
    public function resetPassword(): void
    {
        $this->validate([
            'token' => ['required'],
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $status = Password::reset(
            $this->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) {
                $user->forceFill([
                    'password' => Hash::make($this->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        if ($status != Password::PASSWORD_RESET) {
            $this->addError('email', __($status));

            return;
        }

        Session::flash('status', __($status));

        $this->redirectRoute('login', navigate: true);
    }
}; ?>

<div>
    <h4 class="mb-1">Reset Password 🔒</h4>
    <p class="mb-5">
        Your new password must be different from previously used passwords
    </p>
    <form
        wire:submit="resetPassword"
        class="mb-5"
    >
        <div
            class="form-floating form-floating-outline mb-5"
        >
            <input
                type="text"
                class="form-control"
                id="email"
                wire:model="email"
                placeholder="Enter your email"
                autofocus />
            <label for="email">Email</label>
            @error('email')
                <span class="text-danger" style="font-size: 11.5px">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-5 form-password-toggle form-control-validation">
            <div class="input-group input-group-merge">
                <div class="form-floating form-floating-outline">
                    <input
                        type="password"
                        id="password"
                        class="form-control"
                        wire:model="password"
                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                        aria-describedby="password" />
                    <label for="password">New Password</label>
                </div>
                <span class="input-group-text cursor-pointer"
                    ><i class="icon-base ri ri-eye-off-line icon-20px"></i
                ></span>
            </div>
            @error('password')
                <span class="text-danger" style="font-size: 11.5px">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-5 form-password-toggle form-control-validation">
            <div class="input-group input-group-merge">
                <div class="form-floating form-floating-outline">
                    <input
                        type="password"
                        id="confirm-password"
                        class="form-control"
                        wire:model="password_confirmation"
                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                        aria-describedby="password" />
                    <label for="confirm-password">Confirm Password</label>
                </div>
                <span class="input-group-text cursor-pointer"
                    ><i class="icon-base ri ri-eye-off-line icon-20px"></i
                ></span>
            </div>
            @error('password_confirmation')
                <span class="text-danger" style="font-size: 11.5px">{{ $message }}</span>
            @enderror
        </div>
        <button class="btn btn-primary d-grid w-100 mb-5">Set new password</button>
        <div class="text-center">
            <a
                href="{{ route('login') }}"
                class="d-flex align-items-center justify-content-center">
                <i class="icon-base ri ri-arrow-left-s-line scaleX-n1-rtl icon-20px me-1_5"></i>
                Back to login
            </a>
        </div>
    </form>
</div>
