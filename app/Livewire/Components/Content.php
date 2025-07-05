<?php

namespace App\Livewire\Components;

use App\Models\Content as ContentModel;
use Livewire\Component;

class Content extends Component
{
    public ?ContentModel $content = null;

    public function render()
    {
        return view('livewire.components.content');
    }
}
