<?php

namespace App\Filament\Widgets\Charts;

use App\Models\Appraise;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

/**
 * 种质类型分布环形图
 */
class AppraiseTypeDistribution extends ChartWidget
{
    protected ?string $heading = '种质类型分布';

    protected static bool $isLazy = false;

    protected int|string|array $columnSpan = 1;

    protected ?string $pollingInterval = null;

    protected function getType(): string
    {
        return 'doughnut';
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
                    'data' => $data->pluck('total')->toArray(),
                    'backgroundColor' => ['#8b5cf6', '#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#ec4899', '#06b6d4'],
                ],
            ],
            'labels' => $data->pluck('germplasm_type')->toArray(),
        ];
    }
}
