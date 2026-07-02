<?php

namespace App\Filament\Widgets\Charts;

use App\Models\Award;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

/**
 * 奖项级别分布环形图
 */
class AwardLevelDistribution extends ChartWidget
{
    protected ?string $heading = '奖项级别分布';

    protected static bool $isLazy = false;

    protected int|string|array $columnSpan = 1;

    protected ?string $pollingInterval = null;

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getData(): array
    {
        $data = Award::select('level', DB::raw('count(*) as total'))
            ->whereNotNull('level')
            ->where('level', '!=', '')
            ->groupBy('level')
            ->orderByDesc('total')
            ->get();

        return [
            'datasets' => [
                [
                    'data' => $data->pluck('total')->toArray(),
                    'backgroundColor' => ['#f59e0b', '#3b82f6', '#10b981', '#8b5cf6', '#ef4444'],
                ],
            ],
            'labels' => $data->pluck('level')->toArray(),
        ];
    }
}
