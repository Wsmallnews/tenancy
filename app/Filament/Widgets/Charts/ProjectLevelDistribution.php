<?php

namespace App\Filament\Widgets\Charts;

use App\Models\ProjectManage;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

/**
 * 项目级别分布柱状图
 */
class ProjectLevelDistribution extends ChartWidget
{
    protected ?string $heading = '项目级别分布';

    protected static bool $isLazy = false;

    protected int|string|array $columnSpan = 1;

    protected ?string $pollingInterval = null;

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getData(): array
    {
        $data = ProjectManage::select('level', DB::raw('count(*) as total'))
            ->whereNotNull('level')
            ->where('level', '!=', '')
            ->groupBy('level')
            ->orderByDesc('total')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => '项目数量',
                    'data' => $data->pluck('total')->toArray(),
                    'backgroundColor' => '#f59e0b',
                ],
            ],
            'labels' => $data->pluck('level')->toArray(),
        ];
    }
}
