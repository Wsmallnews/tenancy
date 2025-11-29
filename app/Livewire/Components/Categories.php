<?php

namespace App\Livewire\Components;

use App\Models\Category as CategoryModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;
use Livewire\Component;

class Categories extends Component
{
    public ?string $view = 'livewire.components.categories';

    public ?string $itemView = 'categories.category';

    public ?string $style = 'simple';        // vivid=鲜明的, simple=简单的

    public function getRecordLabel(Model $category): HtmlString | string
    {
        return $category->name;
    }


    public function getItemView(): string
    {
        return $this->itemView;
    }

    public function getCategories()
    {
        return CategoryModel::scoped(has_tenancy() ? ['team_id' => current_tenant()->id] : [])
            ->normal()->defaultOrder()
            ->get()->toTree();
    }

    public function render()
    {
        return view($this->view);
    }
}
