<?php

use Modules\Authentication\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component
{
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/');
    }
}; ?>

<div class="d-grid px-4 pt-2 pb-1">
    <button
        class="btn btn-sm btn-danger d-flex"
        wire:click="logout"
        wire:loading.attr="disabled"
    >
        <small class="align-middle">Logout</small>
        <i class="icon-base ri ri-logout-box-r-line ms-2 icon-16px"></i>
    </button>
</div>
