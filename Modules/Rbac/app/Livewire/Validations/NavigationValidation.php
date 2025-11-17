<?php

namespace Modules\Rbac\Livewire\Validations;

trait NavigationValidation
{

    protected array $validationAttributes = [
        'form.parent_id' => 'parent navigation',
        'form.icon' => 'icon',
        'form.label_name_en' => 'english label name',
        'form.label_name_tl' => 'tetun label name',
        'form.label_name_pt' => 'portuguese label name',
        'form.controller_name' => 'controller name',
        'form.route_name' => 'route name',
        'form.url' => 'url',
        'form.is_active' => 'status',
        'form.is_divider' => 'divider',
    ];

    protected function rules(): array
    {
        return [
            'form.parent_id' => 'nullable|numeric',
            'form.icon' => 'required|max:255',
            'form.label_name_en' => 'required|max:255',
            'form.label_name_pt' => 'required|max:255',
            'form.label_name_tl' => 'required|max:255',
            'form.controller_name' => ($this->form['parent_id'] != '') ? 'required' : 'nullable|max:255',
            'form.route_name' => 'required|max:255',
            'form.url' => 'required|max:255',
            'form.is_active' => 'required',
            'form.is_divider' => 'required',
        ];
    }
}
