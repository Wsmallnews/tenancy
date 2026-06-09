<?php

namespace App\Filament\Widgets\Charts;

use App\Models\Award;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

/**
 * 奖项级别分布柱状图
 */
class AwardLevelDistribution extends ChartWidget
{
    protected ?string $heading = '奖项级别分布';

    protected int|string|array $columnSpan = 'full';

    protected ?string $pollingInterval = null;

    protected function getType(): string
    {
        return 'bar';
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
                    'label' => '奖项数量',
                    'data' => $data->pluck('total')->toArray(),
                    'backgroundColor' => '#ef4444',
                ],
            ],
            'labels' => $data->pluck('level')->toArray(),
        ];
    }
}
