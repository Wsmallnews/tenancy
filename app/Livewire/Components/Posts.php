<?php

namespace App\Livewire\Components;

use App\Livewire\Traits\CanPagination;
use App\Models\Post as PostModel;
use App\Models\PostCategory as PostCategoryModel;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class Posts extends Component
{
    use CanPagination;
    use WithoutUrlPagination;

    public int | array $category_ids = [];

    public Collection $posts;

    public function mount()
    {
        $this->posts = $this->posts ?? collect([]);
    }

    protected function getCurrents()
    {
        return $this->posts;
    }


    public function render()
    {
        $categoryIds = Arr::wrap($this->category_ids);

        $allCategories = collect([]);       // 要查询的分类，以及分类的所有子节点
        foreach ($categoryIds as $id) {
            // 查询分类以及分类的所有子节点
            $currentIds = PostCategoryModel::scoped(has_tenancy() ? ['team_id' => current_tenant()->id] : [])->descendantsAndSelf($id)->pluck('id');
            $allCategories = $allCategories->merge($currentIds);
        }
        $allCategories = $allCategories->filter()->unique()->values();

        // 查询资讯
        $query = PostModel::query()->normal()->with(['media'])->when($allCategories->isNotEmpty(), function ($query) use ($allCategories) {
            $query->whereCategoryIn($allCategories);
        })->orderBy('order_column', 'desc');

        // 分页
        $this->posts = $this->withPagination($query);

        return view('livewire.components.posts', [
            'paginatorLink' => $this->links
        ]);
    }
}
