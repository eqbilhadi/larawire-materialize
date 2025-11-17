<?php

namespace Modules\Rbac\Services\Actions\User;

use App\Models\SysRole;
use App\Models\SysUser;
use Illuminate\Support\Arr;
use Modules\Rbac\Models\ComRole;
use Modules\Rbac\Services\Actions\ActionsService;

class UserActions extends ActionsService
{
    protected array $data;
    public ?SysUser $sysUser;

    public function __construct(array $data = [], ?SysUser $sysUser = null)
    {
        $this->data = $data;
        $this->sysUser = $sysUser;
    }

    public function save(): static
    {
        return $this->sysUser->exists ? $this->update() : $this->create();
    }

    protected function create(): static
    {
        $this->sysUser = SysUser::create($this->getActionsDataSysUser());

        if ($roles = Arr::get($this->data, 'roles')) {
            $this->sysUser->syncRoles(Arr::get($this->data, 'roles'));
        }

        return $this;
    }

    protected function update(): static
    {
        $this->sysUser->update($this->getActionsDataSysUser());

        if ($roles = Arr::get($this->data, 'roles')) {
            $this->sysUser->syncRoles(Arr::get($this->data, 'roles'));
        }

        return $this;
    }

    protected function getActionsDataSysUser(): array
    {
        $data = [
            'username' => Arr::get($this->data, 'username'),
            'email' => Arr::get($this->data, 'email'),
            'name' => Arr::get($this->data, 'name'),
            'birthplace' => Arr::get($this->data, 'birthplace'),
            'birthdate' => Arr::get($this->data, 'birthdate'),
            'gender' => Arr::get($this->data, 'gender'),
            'phone' => Arr::get($this->data, 'phone'),
            'address' => Arr::get($this->data, 'address'),
            'is_active' => Arr::get($this->data, 'is_active'),
        ];

        // Add password only if provided
        if ($password = Arr::get($this->data, 'password')) {
            $data['password'] = $password;
        }

        return array_map(fn($value) => $value === "" ? null : $value, $data);
    }
}
