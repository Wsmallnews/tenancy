<?php

namespace App\Livewire\Components;

use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;

class AppraiseShow extends Component
{
    #[Url]
    public string $categoryId = '';

    public string $wrapperView = 'base.empty-block';
    
    #[On('sn-category-click')]
    public function clickCategory($categoryId)
    {
        $this->categoryId = $categoryId;
    }

    public function render()
    {
        return view('livewire.components.appraise-show', []);
    }
}
