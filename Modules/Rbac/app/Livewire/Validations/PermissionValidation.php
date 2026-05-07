<?php

namespace Modules\Rbac\Livewire\Validations;

trait PermissionValidation
{
    protected array $validationAttributes = [
        // Standalone permission
        'form.name'              => 'permission name',
        'form.group'             => 'permission group',
        // Menu action
        'actionData.menu_id'     => 'menu',
        'actionData.action'      => 'action slug',
        'actionData.label'       => 'action label',
        'actionData.route_name'  => 'route name',
        'actionData.route_method'=> 'http method',
    ];

    protected function rules(): array
    {
        return [
            // Standalone permission
            'form.name'               => 'required|max:255|string',
            'form.group'              => 'required|max:255|string',
            // Menu action
            'actionData.menu_id'      => 'required|exists:sys_menus,id',
            'actionData.action'       => 'required|max:100|regex:/^[a-z0-9_\-]+$/',
            'actionData.label'        => 'required|max:255',
            'actionData.route_name'   => 'nullable|max:255',
            'actionData.route_method' => 'required|in:GET,POST,PUT,PATCH,DELETE',
        ];
    }
}
