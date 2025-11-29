<?php

namespace App\Livewire\Components;

use Livewire\Component;

class AppraiseShow extends Component
{
    public string $wrapperView = 'base.empty-block';
    
    public function render()
    {
        return view('livewire.components.appraise-show', []);
    }
}
