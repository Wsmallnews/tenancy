<?php

namespace App\Livewire;

use App\Models\Appraise as AppraiseModel;
use Livewire\Attributes\Url;
use Wsmallnews\Cms\Support\Utils as CmsUtils;

class Appraise extends Base
{

    public int $id;
    
    public function render()
    {
        $breadcrumbs = [
            ['label' => '首页', 'url' => CmsUtils::route('index')],
            ['label' => '种质详情', 'url' => CmsUtils::route('appraises.show', $this->id)],
        ];

        return view('livewire.appraise', compact('breadcrumbs'))->layout(CmsUtils::getLayout());
    }
}