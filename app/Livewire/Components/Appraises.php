<?php

namespace App\Livewire\Components;

use App\Livewire\Traits\CanPagination;
use App\Models\Appraise as AppraiseModel;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Reactive;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Wsmallnews\Category\Livewire\Concerns\Categoryable;
use Wsmallnews\Category\Models\Category as CategoryModel;

class Appraises extends Component
{
    use CanPagination;
    use WithoutUrlPagination;
    use Categoryable;

    #[Reactive]
    public int | string | array $categoryIds = [];

    public Collection $appraises;

    public string $wrapperView = 'base.empty-block';

    public string $itemWrapperView = 'base.block';

    public function mount()
    {
        $this->appraises = $this->appraises ?? collect([]);
    }

    public function getScopeable(): array
    {
        return ['scope_type' => 'appraise', 'scope_id' => 0];
    }

    protected function getCurrents()
    {
        return $this->appraises;
    }

    public function updatedCategoryIds()
    {
        dd($this->categoryIds);
    }

    public function updating($property, $value)
    {
        dd($property);
    }


    public function render()
    {
        $categoryIds = Arr::wrap($this->categoryIds);

        $allCategories = collect([]);       // 要查询的分类，以及分类的所有子节点
        foreach ($categoryIds as $id) {
            // 查询分类以及分类的所有子节点
            $currentIds = $this->getScopedQuery()->normal()->descendantsAndSelf($id)
                ->pluck('id');

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
