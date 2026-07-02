<?php

namespace App\Filament\Widgets;

use App\Models\AppraiseApply;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AppraiseApplyStatsOverview extends StatsOverviewWidget
{
    protected ?string $heading = '用种申请概览';

    protected static bool $isLazy = false;

    protected ?string $pollingInterval = null;

    protected function getStats(): array
    {
        return [
            Stat::make('待处理', AppraiseApply::applying()->count())
                ->description('等待审核的申请')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),

            Stat::make('已同意', AppraiseApply::agree()->count())
                ->description('已批准的申请')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('已拒绝', AppraiseApply::refuse()->count())
                ->description('已拒绝的申请')
                ->descriptionIcon('heroicon-m-x-circle')
                ->color('danger'),
        ];
    }
}
