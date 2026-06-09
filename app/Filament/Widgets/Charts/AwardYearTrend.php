<?php

namespace App\Filament\Widgets\Charts;

use App\Models\Award;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

/**
 * 获奖年度趋势折线图
 */
class AwardYearTrend extends ChartWidget
{
    protected ?string $heading = '获奖年度趋势';

    protected int|string|array $columnSpan = 'full';

    protected ?string $pollingInterval = null;

    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        $data = Award::select(
            DB::raw('YEAR(award_at) as year'),
            DB::raw('count(*) as total')
        )
            ->whereNotNull('award_at')
            ->groupBy('year')
            ->orderBy('year')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => '获奖数量',
                    'data' => $data->pluck('total')->toArray(),
                    'fill' => 'start',
                    'tension' => 0.3,
                ],
            ],
            'labels' => $data->pluck('year')->toArray(),
        ];
    }
}
