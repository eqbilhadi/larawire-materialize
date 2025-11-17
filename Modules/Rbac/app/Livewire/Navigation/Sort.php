<?php

namespace Modules\Rbac\Livewire\Navigation;

use App\Jobs\ForgetCacheMenu;
use App\Models\SysMenu;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\Attributes\On;

class Sort extends Component
{
    public $parentOrder = [];
    public $childOrders = [];

    #[Computed]
    public function lists()
    {
        return SysMenu::orderBy('sort_num')->whereNull('parent_id')->get()->map(function ($menu) {
            return [
                'id' => $menu->id,
                'label_name_en' => $menu->label_name_en,
                'label_name_pt' => $menu->label_name_pt,
                'label_name_tl' => $menu->label_name_tl,
                'icon' => $menu->icon,
                'sort' => $menu->sort_num,
                'is_divider' => $menu->is_divider,
                'children' => $menu->children->map(function ($child) {
                    return [
                        'id' => $child->id,
                        'label_name_en' => $child->label_name_en,
                        'label_name_pt' => $child->label_name_pt,
                        'label_name_tl' => $child->label_name_tl,
                        'icon' => $child->icon,
                        'sort' => $child->sort_num,
                        'is_divider' => $child->is_divider,
                        'link' => $child->link,
                    ];
                })->toArray(),
            ];
        });
    }

    public function save()
    {
        try {
            // Save parent order
            foreach ($this->parentOrder as $i => $id) {
                SysMenu::where('id', $id)->update([
                    'sort_num' => $i + 1,
                    'parent_id' => null,
                ]);
            }

            // Save child orders
            foreach ($this->childOrders as $parentId => $children) {
                foreach ($children as $i => $id) {
                    SysMenu::where('id', $id)->update([
                        'sort_num' => $i + 1,
                        'parent_id' => $parentId,
                    ]);
                }
            }
            dispatch(new ForgetCacheMenu());
            flash()->success('Sort navigation successfully');
            return $this->redirect(route('rbac.nav.index'));
        } catch (\Exception $err) {
            flash()->error($err->getMessage());
            Log::info($err->getMessage());
        }


        session()->flash('success', 'Order updated!');
    }


    public function render()
    {
        return view('rbac::livewire.navigation.sort');
    }
}
