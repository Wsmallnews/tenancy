<?php

namespace App\Livewire\Components\Index;

use Livewire\Attributes\Locked;
use Wsmallnews\Category\Support\Utils as CategoryUtils;
use Wsmallnews\Cms\Livewire\Components\Base;
use Wsmallnews\Cms\Support\Utils;

class Featured extends Base
{
    public int $limit = 10;

    public int $postLimit = 4;

    #[Locked]
    public ?int $activeCategoryId = null;

    public function render()
    {
        // 轮播图：置顶文章优先（flags 含 top），不足时由最新文章补齐
        $slidePosts = Utils::getPostModel()::snScope(...$this->getScopeable())->published()
            ->with(['media', 'categories'])
            ->orderByRaw('JSON_CONTAINS(flags, \'"top"\') DESC')
            ->orderBy('order_column', 'desc')
            ->orderBy('id', 'desc')
            ->limit($this->limit)
            ->get();

        $slides = $slidePosts->map(function ($post) {
            return [
                'image' => $post->getFirstMediaUrl('post_image'),
                'label' => $post->title,
                'description' => $post->description,
                // 分类作为标签展示，全部显示
                'categories' => $post->categories->pluck('name')->all(),
                'url' => Utils::route('posts.show', $post),
            ];
        })->values()->toArray();

        // 分类 tab：树形查询（后台已限制只有一级），自带排序
        $categories = $this->getCategories()->take(5);

        if (is_null($this->activeCategoryId)) {
            $this->activeCategoryId = $categories->first()?->id;
        }

        // 当前分类下的文章列表（与轮播图互不相关），固定 4 条，行高自适应
        $categoryPosts = collect([]);
        if ($this->activeCategoryId) {
            $categoryPosts = Utils::getPostModel()::snScope(...$this->getScopeable())->published()
                ->with(['media', 'categories'])
                ->whereHas('categories', fn ($query) => $query->whereKey($this->activeCategoryId))
                ->orderBy('order_column', 'desc')
                ->orderBy('id', 'desc')
                ->limit($this->postLimit)
                ->get();
        }

        return view('livewire.components.index.featured', [
            'slides' => $slides,
            'categories' => $categories,
            'categoryPosts' => $categoryPosts,
        ]);
    }

    public function selectCategory(int $categoryId): void
    {
        $this->activeCategoryId = $categoryId;
    }

    /**
     * 参考 Wsmallnews\Category\Livewire\Components\Categories::getNestedset()
     */
    protected function getCategories()
    {
        $scopeable = $this->getScopeable();

        $categoryType = CategoryUtils::getCategoryTypeModel()::scopeable(...$scopeable)->first();
        if (! $categoryType) {
            return collect([]);
        }

        $scoped = [...$scopeable, 'type_id' => $categoryType->id];
        if (has_tenancy()) {
            $scoped['team_id'] = current_tenant()?->id;
        }

        return CategoryUtils::getCategoryModel()::scoped($scoped)->normal()->defaultOrder()->get()->toTree();
    }
}
