<?php

namespace App\Livewire;

use Livewire\Attributes\Url;
use Wsmallnews\Cms\Livewire\Base;
use Wsmallnews\Cms\Support\Utils as CmsUtils;

class Appraises extends Base
{
    #[Url]
    public int $categoryId;

    public function mount()
    {
        $this->categoryId = request()->input('categoryId', 0);
    }

    public function render()
    {
        $breadcrumbs = [
            ['label' => '首页', 'url' => CmsUtils::route('index')],
            ['label' => '种质列表', 'url' => CmsUtils::route('appraises')],
        ];

        return view('livewire.appraises', [
            'breadcrumbs' => $breadcrumbs,
        ])->layout(CmsUtils::getLayout());
    }
}
