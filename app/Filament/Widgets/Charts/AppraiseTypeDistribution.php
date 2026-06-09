<?php

namespace App\Filament\Widgets\Charts;

use App\Models\Appraise;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

/**
 * 种质类型与用途分布柱状图
 */
class AppraiseTypeDistribution extends ChartWidget
{
    protected ?string $heading = '种质类型分布';

    protected int|string|array $columnSpan = 'full';

    protected ?string $pollingInterval = null;

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getData(): array
    {
        $data = Appraise::select('germplasm_type', DB::raw('count(*) as total'))
            ->whereNotNull('germplasm_type')
            ->where('germplasm_type', '!=', '')
            ->groupBy('germplasm_type')
            ->orderByDesc('total')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => '种质数量',
                    'data' => $data->pluck('total')->toArray(),
                    'backgroundColor' => '#8b5cf6',
                ],
            ],
            'labels' => $data->pluck('germplasm_type')->toArray(),
        ];
    }
}
