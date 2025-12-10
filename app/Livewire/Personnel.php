<?php

namespace App\Livewire;

use App\Models\Personnel as PersonnelModel;
use Livewire\Attributes\Url;
use Wsmallnews\Cms\Support\Utils as CmsUtils;

class Personnel extends Base
{

    public int $id;

    public function render()
    {
        $breadcrumbs = [
            ['label' => '首页', 'url' => CmsUtils::route('index')],
            ['label' => '人员详情', 'url' => CmsUtils::route('personnels.show', $this->id)],
        ];

        return view('livewire.personnel', [
            'breadcrumbs' => $breadcrumbs,
        ]);
    }
}