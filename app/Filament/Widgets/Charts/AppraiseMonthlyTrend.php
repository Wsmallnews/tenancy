<?php

namespace App\Filament\Widgets\Charts;

use App\Models\Appraise;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

/**
 * 种质评价月度新增趋势折线图
 */
class AppraiseMonthlyTrend extends ChartWidget
{
    protected ?string $heading = '种质评价月度新增趋势';

    protected static bool $isLazy = false;

    protected int|string|array $columnSpan = 1;

    protected ?string $pollingInterval = null;

    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        $data = Appraise::select(
            DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"),
            DB::raw('count(*) as total')
        )
            ->where('created_at', '>=', now()->subMonths(11)->startOfMonth())
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => '新增种质评价',
                    'data' => $data->pluck('total')->toArray(),
                    'fill' => 'start',
                    'tension' => 0.3,
                ],
            ],
            'labels' => $data->pluck('month')->toArray(),
        ];
    }
}
