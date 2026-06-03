<?php

namespace App\Filament\Widgets;

use App\Models\AccurateIdentify;
use App\Models\Appraise;
use App\Models\AppraiseApply;
use App\Models\Assemble;
use App\Models\Catalog;
use App\Models\NewVariety;
use App\Models\PhenotypeIdentify;
use App\Models\Preserve;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AppraiseStat extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make(
                label: '种质评价数量',
                value: Appraise::count(),
            ),
            Stat::make(
                label: '收集数量',
                value: Assemble::count(),
            ),
            Stat::make(
                label: '保存数量',
                value: Preserve::count(),
            ),
            Stat::make(
                label: '编目数量',
                value: Catalog::count(),
            ),
            Stat::make(
                label: '新品种数量',
                value: NewVariety::count(),
            ),
            Stat::make(
                label: '精准鉴定数量',
                value: AccurateIdentify::count(),
            ),
            Stat::make(
                label: '表型鉴定数量',
                value: PhenotypeIdentify::count(),
            ),
            Stat::make(
                label: '用种申请(待处理)',
                value: AppraiseApply::applying()->count(),
            ),
            Stat::make(
                label: '用种申请(同意)',
                value: AppraiseApply::agree()->count(),
            ),
            Stat::make(
                label: '用种申请(拒绝)',
                value: AppraiseApply::refuse()->count(),
            ),
        ];
    }
}
