<?php

namespace App\Livewire;

use Livewire\Attributes\Url;
use Wsmallnews\Cms\Livewire\Base;
use Wsmallnews\Cms\Support\Utils as CmsUtils;

class AppraiseCategories extends Base
{
    public function render()
    {
        $breadcrumbs = [
            ['label' => '首页', 'url' => CmsUtils::route('index')],
            ['label' => '种质资源分类', 'url' => CmsUtils::route('appraise-categories')],
        ];

        return view('livewire.appraise-categories', [
            'breadcrumbs' => $breadcrumbs,
        ])->layout(CmsUtils::getLayout());
    }
}
