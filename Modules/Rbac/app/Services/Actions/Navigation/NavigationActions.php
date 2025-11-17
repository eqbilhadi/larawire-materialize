<?php

namespace Modules\Rbac\Services\Actions\Navigation;

use Illuminate\Support\Arr;
use App\Models\SysMenu;
use Modules\Rbac\Services\Actions\ActionsService;

class NavigationActions extends ActionsService
{

    protected array $data = [];

    protected ?SysMenu $sysMenu = null;

    /**
     * Method __construct
     *
     * @param ?SysMenu $sysMenu [explicite description]
     * @param array $data [explicite description]
     *
     * @return void
     */
    public function __construct(array $data = [], ?SysMenu $sysMenu = null)
    {
        $this->data = $data;

        $this->sysMenu = $sysMenu;
    }

    /**
     * Method save
     *
     * @return void
     */
    public function save(): void
    {
        if (!$this->sysMenu->exists) {
            $this->create();
        } else {
            $this->update();
        }
    }

    /**
     * Method create
     *
     * @return static
     */
    protected function create(): static
    {
        $this->sysMenu = SysMenu::create($this->getActionsDataSysMenu());

        return $this;
    }

    /**
     * Method update
     *
     * @return static
     */
    protected function update(): static
    {
        $this->sysMenu->update($this->getActionsDataSysMenu());

        return $this;
    }

    /**
     * Method getActionsDataSysMenu
     *
     * @return array
     */
    protected function getActionsDataSysMenu(): array
    {
        // set data
        $data = [
            'parent_id' => Arr::get($this->data, 'parent_id'),
            'icon' => Arr::get($this->data, 'icon'),
            'label_name_en' => Arr::get($this->data, 'label_name_en'),
            'label_name_pt' => Arr::get($this->data, 'label_name_pt'),
            'label_name_tl' => Arr::get($this->data, 'label_name_tl'),
            'controller_name' => Arr::get($this->data, 'controller_name'),
            'route_name' => Arr::get($this->data, 'route_name'),
            'url' => Arr::get($this->data, 'url'),
            'is_active' => Arr::get($this->data, 'is_active'),
            'is_divider' => Arr::get($this->data, 'is_divider'),
        ];

        if (!$this->sysMenu->exists) {
            $data['sort_num'] = $this->getSortNumNav();
        }

        $data = array_map(function ($value) {
            return $value === "" ? null : $value;
        }, $data);

        return $data;
    }

    protected function getSortNumNav()
    {
        $parentNav = $this->data['parent_id'];

        /** Jika menambah data baru dan main nav */
        if (!$this->sysMenu->exists && $parentNav == "") {
            $sortNum = SysMenu::whereNull('parent_id')->max('sort_num');
        }

        /** Jika menambah data baru dan bukan main nav */
        if (!$this->sysMenu->exists && $parentNav != "") {
            $sortNum = SysMenu::whereParentId($parentNav)->max('sort_num');
        }

        /** Jika mengubah data yang ada dan bukan main nav */
        if ($this->sysMenu->exists && $parentNav != "") {
            $this->sysMenu->parent_id = $parentNav;
            if ($this->sysMenu->isDirty('parent_id')) {
                $sortNum = SysMenu::whereParentId($parentNav)->max('sort_num');
            } else {
                return $this->sysMenu->sort_num;
            }
        }

        /** Jika mengubah data yang ada dan main nav */
        if ($this->sysMenu->exists && $parentNav == "") {
            $this->sysMenu->parent_id = $parentNav;
            if ($this->sysMenu->isDirty('parent_id')) {
                $sortNum = SysMenu::whereNull('parent_id')->max('sort_num');
            } else {
                return $this->sysMenu->sort_num;
            }
        }

        return $sortNum + 1;
    }
}
