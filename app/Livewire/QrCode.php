<?php

namespace App\Livewire;

use App\Features\QrCodeService;
use App\Models\Appraise as AppraiseModel;
use Wsmallnews\Cms\Livewire\Base;
use Wsmallnews\Cms\Support\Utils as CmsUtils;

class QrCode extends Base
{
    public string $token;

    public int $id;

    public function mount()
    {
        $params = QrCodeService::decodeToken($this->token);

        $appraise = AppraiseModel::query()
            ->scopeTenant()
            ->normal()
            ->where('resource_no', $params['resource_no'])
            ->where('germplasm_no', $params['germplasm_no'])
            ->firstOrFail();

        $this->id = $appraise->id;
    }

    public function render()
    {
        $breadcrumbs = [
            ['label' => '首页', 'url' => CmsUtils::route('index')],
            ['label' => '种质详情', 'url' => CmsUtils::route('appraises.qrcode', $this->token)],
        ];

        return view('livewire.appraise', compact('breadcrumbs'))->layout(CmsUtils::getLayout());
    }
}
