<?php

namespace App\Livewire\Components;

use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;
use Wsmallnews\Cms\Livewire\Components\Base;

class AppraiseShow extends Base
{
    #[Url]
    public string $categoryId = '';

    public string $style = 'card';

    public function render()
    {
        return view('livewire.components.appraise-show', []);
    }
}
