<?php

namespace App\Livewire\Components;

use App\Livewire\Traits\CanPagination;
use App\Models\Appraise as AppraiseModel;
use App\Models\Category as CategoryModel;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class Appraises extends Component
{
    use CanPagination;
    use WithoutUrlPagination;

    public int | array $category_ids = [];      // @sn todo 有时间把这个改为驼峰

    public Collection $appraises;

    public string $wrapperView = 'base.empty-block';

    public string $itemWrapperView = 'base.block';

    public function mount()
    {
        $this->appraises = $this->appraises ?? collect([]);
    }

    protected function getCurrents()
    {
        return $this->appraises;
    }


    public function render()
    {
        $categoryIds = Arr::wrap($this->category_ids);

        $allCategories = collect([]);       // 要查询的分类，以及分类的所有子节点
        foreach ($categoryIds as $id) {
            // 查询分类以及分类的所有子节点
            $currentIds = CategoryModel::scoped(has_tenancy() ? ['team_id' => current_tenant()->id] : [])->descendantsAndSelf($id)->pluck('id');
            $allCategories = $allCategories->merge($currentIds);
        }
        $allCategories = $allCategories->filter()->unique()->values();

        // 查询评价
        $query = AppraiseModel::query()->scopeTenant()->normal()->with(['media'])->when($allCategories->isNotEmpty(), function ($query) use ($allCategories) {
            $query->whereIn('category_id', $allCategories);
        })->orderBy('order_column', 'desc');

        // 分页
        $this->appraises = $this->withPagination($query);

        return view('livewire.components.appraises', [
            'paginatorLink' => $this->links
        ]);
    }
}
