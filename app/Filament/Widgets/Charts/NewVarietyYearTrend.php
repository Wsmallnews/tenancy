<?php

namespace App\Filament\Widgets\Charts;

use App\Models\NewVariety;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

/**
 * 新品种审定年度趋势折线图
 */
class NewVarietyYearTrend extends ChartWidget
{
    protected static ?string $heading = '新品种审定年度趋势';

    protected int | string | array $columnSpan = 'full';

    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        $data = NewVariety::select(
                DB::raw('YEAR(variety_at) as year'),
                DB::raw('count(*) as total')
            )
            ->whereNotNull('variety_at')
            ->groupBy('year')
            ->orderBy('year')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => '新品种数量',
                    'data' => $data->pluck('total')->toArray(),
                    'fill' => 'start',
                    'tension' => 0.3,
                ],
            ],
            'labels' => $data->pluck('year')->toArray(),
        ];
    }
}