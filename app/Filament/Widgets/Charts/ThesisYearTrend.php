<?php

namespace App\Filament\Widgets\Charts;

use App\Models\Thesis;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

/**
 * 论文发表年度趋势折线图
 */
class ThesisYearTrend extends ChartWidget
{
    protected static ?string $heading = '论文发表年度趋势';

    protected int | string | array $columnSpan = 'full';

    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        $data = Thesis::select(
                DB::raw('YEAR(published_at) as year'),
                DB::raw('count(*) as total')
            )
            ->whereNotNull('published_at')
            ->groupBy('year')
            ->orderBy('year')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => '论文数量',
                    'data' => $data->pluck('total')->toArray(),
                    'fill' => 'start',
                    'tension' => 0.3,
                ],
            ],
            'labels' => $data->pluck('year')->toArray(),
        ];
    }
}