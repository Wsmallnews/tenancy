<?php

namespace App\Filament\Widgets\Charts;

use App\Models\AppraiseApply;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

/**
 * 用种申请月度趋势折线图
 */
class AppraiseApplyMonthlyTrend extends ChartWidget
{
    protected static ?string $heading = '用种申请月度趋势';

    protected int | string | array $columnSpan = 'full';

    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        $data = AppraiseApply::select(
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
                    'label' => '申请数量',
                    'data' => $data->pluck('total')->toArray(),
                    'fill' => 'start',
                    'tension' => 0.3,
                ],
            ],
            'labels' => $data->pluck('month')->toArray(),
        ];
    }
}