<?php

namespace App\Filament\Widgets;

use App\Models\AccurateIdentify;
use App\Models\Appraise;
use App\Models\Assemble;
use App\Models\Catalog;
use App\Models\NewVariety;
use App\Models\PhenotypeIdentify;
use App\Models\Preserve;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class GermplasmStatsOverview extends StatsOverviewWidget
{
    protected ?string $heading = '种质资源概览';

    protected ?string $pollingInterval = null;

    protected function getStats(): array
    {
        return [
            Stat::make('种质评价', Appraise::count())
                ->description('种质资源评价总数')
                ->descriptionIcon('heroicon-m-beaker')
                ->color('primary'),

            Stat::make('收集', Assemble::count())
                ->description('收集记录总数')
                ->descriptionIcon('heroicon-m-arrow-down-tray')
                ->color('info'),

            Stat::make('保存', Preserve::count())
                ->description('保存记录总数')
                ->descriptionIcon('heroicon-m-archive-box')
                ->color('success'),

            Stat::make('编目', Catalog::count())
                ->description('编目记录总数')
                ->descriptionIcon('heroicon-m-bookmark-square')
                ->color('warning'),

            Stat::make('新品种', NewVariety::count())
                ->description('新品种审定总数')
                ->descriptionIcon('heroicon-m-sparkles')
                ->color('success'),

            Stat::make('精准鉴定', AccurateIdentify::count())
                ->description('精准鉴定记录总数')
                ->descriptionIcon('heroicon-m-magnifying-glass')
                ->color('info'),

            Stat::make('表型鉴定', PhenotypeIdentify::count())
                ->description('表型鉴定记录总数')
                ->descriptionIcon('heroicon-m-eye')
                ->color('primary'),
        ];
    }
}
