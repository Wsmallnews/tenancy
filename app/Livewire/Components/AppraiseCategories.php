<?php

namespace App\Livewire\Components;

use Livewire\Attributes\Locked;
use Livewire\Component;
use Wsmallnews\Category\Livewire\Concerns\Categoryable;

class AppraiseCategories extends Component
{
    use Categoryable;

    #[Locked]
    public string $scopeType = 'appraise';

    #[Locked]
    public int $scopeId = 0;

    public function getScopeable(): array
    {
        return ['scope_type' => $this->scopeType, 'scope_id' => $this->scopeId];
    }

    public function getCategories()
    {
        return $this->getScopedQuery()->normal()
            ->defaultOrder()
            ->get()->toTree();
    }

    public function render()
    {
        $categories = $this->getCategories();

        return view('livewire.components.categories', [
            'categories' => $categories,
        ]);
    }
}
