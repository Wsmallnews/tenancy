<?php

namespace App\Filament\Widgets\Charts;

use App\Models\Assemble;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

/**
 * 收集记录年度趋势折线图
 */
class AssembleYearTrend extends ChartWidget
{
    protected ?string $heading = '收集记录年度趋势';

    protected int|string|array $columnSpan = 'full';

    protected ?string $pollingInterval = null;

    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        $data = Assemble::select(
            DB::raw('YEAR(created_at) as year'),
            DB::raw('count(*) as total')
        )
            ->groupBy('year')
            ->orderBy('year')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => '收集记录数',
                    'data' => $data->pluck('total')->toArray(),
                    'fill' => 'start',
                    'tension' => 0.3,
                ],
            ],
            'labels' => $data->pluck('year')->toArray(),
        ];
    }
}
