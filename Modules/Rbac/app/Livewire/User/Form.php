<?php

namespace Modules\Rbac\Livewire\User;

use App\Models\SysRole;
use App\Models\SysUser;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Modules\Rbac\Livewire\Validations\UserValidation;
use Modules\Rbac\Services\Actions\User\UserActions;

class Form extends Component
{
    use UserValidation;

    public array $form = [
        'username' => '',
        'email' => '',
        'password' => '',
        'name' => '',
        'birthplace' => '',
        'birthdate' => '',
        'gender' => '',
        'phone' => '',
        'address' => '',
        'is_active' => '',
        'roles' => ''
    ];

    public array $options = [];

    public SysUser $sysUser;

    public string $actionForm = "Added";

    public function mount(?SysUser $sysUser = null): void
    {
        $this->sysUser = $sysUser;

        if ($this->sysUser->exists) {
            $this->fillForm();
            $this->actionForm = "Updated";
        }

        $this->options['roles'] = SysRole::latest()
            ->get()
            ->map(fn($row) => [
                'id' => $row->name,
                'label' => $row->name,
            ])
            ->toArray();
    }

    public function fillForm()
    {
        $this->form = [
            'username' => $this->sysUser->username,
            'email' => $this->sysUser->email,
            'name' => $this->sysUser->name,
            'birthplace' => $this->sysUser->birthplace,
            'birthdate' => $this->sysUser->birthdate,
            'gender' => $this->sysUser->gender,
            'phone' => $this->sysUser->phone,
            'address' => $this->sysUser->address,
            'is_active' => $this->sysUser->is_active,
            'roles' => $this->sysUser->roles()->first()?->name ?? '',
        ];

    }

    public function save()
    {
        $this->validate();

        try {
            (new UserActions($this->form, $this->sysUser))->handle();
            flash()->success($this->actionForm. ' user successfully');
            return $this->redirect(route('rbac.user.index'));
        } catch (\Exception $err) {
            flash()->error('Something went wrong, try again later!');
            Log::error($err->getMessage());
        }
    }

    public function render()
    {
        return view('rbac::livewire.user.form');
    }
}
