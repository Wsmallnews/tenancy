<?php

namespace App\Filament\Widgets;

use App\Models\Award;
use App\Models\NewVariety;
use App\Models\Patent;
use App\Models\ProjectManage;
use App\Models\Thesis;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ResearchStatsOverview extends StatsOverviewWidget
{
    protected ?string $heading = '研究成果概览';

    protected ?string $pollingInterval = null;

    protected function getStats(): array
    {
        return [
            Stat::make('论文', Thesis::count())
                ->description('论文发表总数')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('primary'),

            Stat::make('奖项', Award::count())
                ->description('获奖总数')
                ->descriptionIcon('heroicon-m-trophy')
                ->color('warning'),

            Stat::make('专利', Patent::count())
                ->description('专利申请/授权总数')
                ->descriptionIcon('heroicon-m-shield-check')
                ->color('info'),

            Stat::make('新品种', NewVariety::count())
                ->description('新品种审定总数')
                ->descriptionIcon('heroicon-m-sparkles')
                ->color('success'),

            Stat::make('项目', ProjectManage::count())
                ->description('项目管理总数')
                ->descriptionIcon('heroicon-m-folder-open')
                ->color('secondary'),
        ];
    }
}
