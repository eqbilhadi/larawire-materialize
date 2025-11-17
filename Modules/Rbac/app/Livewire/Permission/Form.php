<?php

namespace Modules\Rbac\Livewire\Permission;

use App\Models\SysPermission;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Livewire\Component;
use Modules\Rbac\Livewire\Validations\PermissionValidation;
use Modules\Rbac\Services\Actions\Permission\PermissionActions;

class Form extends Component
{
    use PermissionValidation;

    public string $actionForm = "add";

    public array $form = [
        "name" => "",
        "group" => ""
    ];

    public ?SysPermission $comPermission = null;

    /**
     * Method openModal
     *
     * @return void
     */
    #[On('open-permission-form')]
    public function openModal(?SysPermission $comPermission = null)
    {
        $this->resetForm();

        $this->comPermission = $comPermission;
        if ($this->comPermission->exists) {
            $this->actionForm = "Edit";
            $this->fillForm();
        } else {
            $this->actionForm = "Add";
        }

        $this->dispatch('open-modal');
    }

    public function fillForm()
    {
        $this->form = [
            "name" => $this->comPermission->name,
            "group" => $this->comPermission->group
        ];
    }

    public function save()
    {
        $this->validate();

        try {
            (new PermissionActions($this->form, $this->comPermission))->handle();

            $this->dispatch('saved');
            flash()->success($this->actionForm . ' permission successfully');
        } catch (\Exception $err) {
            flash()->error($err->getMessage());
            Log::info($err->getMessage());
        }
    }

    protected function resetForm()
    {
        $this->resetValidation();
        $this->form = [
            "name" => "",
            "role" => ""
        ];
    }

    public function render()
    {
        return view('rbac::livewire.permission.form');
    }
}
