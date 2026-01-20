<?php

namespace App\Livewire\User;

use App\Livewire\Base;
use App\Models\AppraiseApply as AppraiseApplyModel;
use Wsmallnews\Cms\Support\Utils as CmsUtils;

class AppraiseApply extends Base
{
    public int $id;
    
    public function render()
    {
        $breadcrumbs = [
            ['label' => '个人中心', 'url' => CmsUtils::route('index')],
            ['label' => '种质申请', 'url' => CmsUtils::route('user.appraise-applies')],
            ['label' => '用种申请详情', 'url' => CmsUtils::route('user.appraise-applies.show', $this->id)],
        ];

        return view('livewire.user.appraise-apply', compact('breadcrumbs'))->layout(CmsUtils::getLayout());
    }
}