<?php

namespace Modules\Rbac\Livewire\Validations;

use App\Models\SysUser;
use Illuminate\Validation\Rule;

trait UserValidation
{
    protected array $validationAttributes = [
        'form.username' => 'username',
        'form.email' => 'email',
        'form.name' => 'fullname',
        'form.gender' => 'gender',
        'form.password' => 'password',
        'form.roles' => 'role',
    ];

    protected function rules(): array
    {
        return [
            'form.username' => ['required', 'string', 'max:255', 'lowercase', Rule::unique(SysUser::class, 'username')->ignore($this->sysUser->id)],
            'form.email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(SysUser::class, 'email')->ignore($this->sysUser->id)],
            'form.name' => ['required', 'string', 'max:255'],
            'form.gender' => ['required', 'in:l,p'],
            'form.password' => ['required_if:actionForm,Added'],
            'form.roles' => ['required'],
        ];
    }

    protected function messages(): array
    {
        return [
            'form.password.required_if' => 'The :attribute is required when create a user.',
        ];
    }
}
