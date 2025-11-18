<?php

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new
    #[Layout('components.layouts.auth.cover', ['title' => 'Forgot Password'])]
class extends Component {
    public string $email = '';

    /**
     * Send a password reset link to the provided email address.
     */
    public function sendPasswordResetLink(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $status = Password::sendResetLink(
            $this->only('email')
        );

        if ($status != Password::RESET_LINK_SENT) {
            $this->addError('email', __($status));

            return;
        }

        $this->reset('email');

        session()->flash('status', __($status));
    }
}; ?>

<div>
    <h4 class="mb-1">Forgot Password? 🔒</h4>
    <p class="mb-5"
        >Enter your email and we'll send you instructions to reset your password</p
    >
    <form
        wire:submit="sendPasswordResetLink"
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
        <button class="btn btn-primary d-grid w-100 mb-5">Send Reset Link</button>
    </form>
    <div class="text-center">
        <a
            href="{{ route('login') }}"
            class="d-flex align-items-center justify-content-center"
        >
            <i
                class="icon-base ri ri-arrow-left-s-line scaleX-n1-rtl icon-20px me-1_5"
            >
            </i>
            Back to login
        </a>
    </div>

</div>
