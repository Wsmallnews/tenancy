<?php

namespace App\Livewire;

use Wsmallnews\Cms\Livewire\Base;
use Wsmallnews\Cms\Support\Utils as CmsUtils;

class Personnels extends Base
{
    public function render()
    {
        $breadcrumbs = [
            ['label' => '首页', 'url' => CmsUtils::route('index')],
            ['label' => '人员列表', 'url' => CmsUtils::route('personnels')],
        ];

        return view('livewire.personnels', [
            'breadcrumbs' => $breadcrumbs,
        ])->layout(CmsUtils::getLayout());
    }
}
