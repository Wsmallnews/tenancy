<?php

namespace App\Livewire;

use Livewire\Attributes\Url;
use Wsmallnews\Cms\Livewire\Base;
use Wsmallnews\Cms\Support\Utils as CmsUtils;

class Index extends Base
{
    public function mount()
    {
    }

    public function render()
    {
        return view('livewire.index', [
        ])->layout(CmsUtils::getLayout());
    }
}
