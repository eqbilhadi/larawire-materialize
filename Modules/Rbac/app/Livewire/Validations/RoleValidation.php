<?php

namespace Modules\Rbac\Livewire\Validations;

use App\Models\SysRole;
use Illuminate\Validation\Rule;

trait RoleValidation
{

    protected array $validationAttributes = [
        'name' => 'role name',
    ];

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'lowercase', Rule::unique(SysRole::class, 'name')->ignore($this->sysRole->id)],
        ];
    }
}

