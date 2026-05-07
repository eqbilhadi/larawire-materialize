<?php

namespace Modules\Rbac\Livewire\Navigation;

use App\Jobs\ForgetCacheMenu;
use App\Models\SysMenu;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Modules\Rbac\Livewire\Validations\NavigationValidation;
use Modules\Rbac\Services\Actions\Navigation\NavigationActions;

class Form extends Component
{
    use NavigationValidation;

    public ?SysMenu $sysMenu = null;

    public array $form = [
        'parent_id'         => '',
        'sort_num'          => '',
        'icon'              => '',
        'label_name_en'     => '',
        'label_name_pt'     => '',
        'label_name_tl'     => '',
        'controller_name'   => '',
        'route_name'        => '',
        'url'               => '',
        'permission_prefix' => '',
        'is_active'         => true,
        'is_divider'        => false,
    ];

    public string $action = 'Added';
    public array  $options = [];

    public function mount(?SysMenu $sysMenu = null): void
    {
        $this->sysMenu = $sysMenu;

        if ($this->sysMenu->exists) {
            $this->fillForm();
            $this->action = 'Updated';
        }

        $this->options['parents_nav'] = SysMenu::whereNull('parent_id')
            ->select('id', 'label_name_en', 'label_name_pt', 'label_name_tl')
            ->orderBy('sort_num')
            ->get()
            ->map(fn($row) => [
                'id'    => $row->id,
                'label' => $row->{'label_name_' . current_language()},
            ])
            ->toArray();
    }

    public function fillForm(): void
    {
        $this->form = [
            'parent_id'         => $this->sysMenu->parent_id ?? '',
            'sort_num'          => $this->sysMenu->sort_num,
            'icon'              => $this->sysMenu->icon,
            'label_name_en'     => $this->sysMenu->label_name_en,
            'label_name_pt'     => $this->sysMenu->label_name_pt,
            'label_name_tl'     => $this->sysMenu->label_name_tl,
            'controller_name'   => $this->sysMenu->controller_name,
            'route_name'        => $this->sysMenu->route_name,
            'url'               => $this->sysMenu->url,
            'permission_prefix' => $this->sysMenu->permission_prefix ?? '',
            'is_active'         => $this->sysMenu->is_active,
            'is_divider'        => $this->sysMenu->is_divider,
        ];
    }

    public function resetForm(): void
    {
        $this->reset('form');
        $this->resetValidation();

        if ($this->sysMenu->exists) {
            $this->fillForm();
        }

        $isDivider = !($this->form['parent_id'] == '');
        $this->dispatch('reseted-form', is_divider: $isDivider);
    }

    public function save()
    {
        $this->validate();

        try {
            (new NavigationActions($this->form, $this->sysMenu))->handle();
            dispatch(new ForgetCacheMenu());

            flash()->success($this->action . ' navigation successfully');
            return $this->redirect(route('rbac.nav.index'));
        } catch (\Exception $err) {
            flash()->error($err->getMessage());
            Log::error($err->getMessage());
        }
    }

    public function render()
    {
        return view('rbac::livewire.navigation.form');
    }
}
