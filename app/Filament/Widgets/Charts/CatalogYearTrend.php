<?php

namespace App\Filament\Widgets\Charts;

use App\Models\Catalog;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

/**
 * 编目年度趋势折线图
 */
class CatalogYearTrend extends ChartWidget
{
    protected ?string $heading = '编目年度趋势';

    protected int|string|array $columnSpan = 'full';

    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        $data = Catalog::select(
            DB::raw('YEAR(created_at) as year'),
            DB::raw('count(*) as total')
        )
            ->groupBy('year')
            ->orderBy('year')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => '编目数量',
                    'data' => $data->pluck('total')->toArray(),
                    'fill' => 'start',
                    'tension' => 0.3,
                ],
            ],
            'labels' => $data->pluck('year')->toArray(),
        ];
    }
}
