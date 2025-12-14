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

    public string $style = 'card';
    
    #[On('sn-filament-nestedset-leaf-click')]
    public function clickCategory($recordId)
    {
        $this->categoryId = $recordId;
    }

    public function render()
    {
        return view('livewire.components.appraise-show', []);
    }
}
