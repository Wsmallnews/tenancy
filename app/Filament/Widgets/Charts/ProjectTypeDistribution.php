<?php

namespace App\Filament\Widgets\Charts;

use App\Models\ProjectManage;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

/**
 * 项目类型与级别分布柱状图
 */
class ProjectTypeDistribution extends ChartWidget
{
    protected ?string $heading = '项目类型分布';

    protected int|string|array $columnSpan = 'full';

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getData(): array
    {
        $typeData = ProjectManage::select('type', DB::raw('count(*) as total'))
            ->whereNotNull('type')
            ->where('type', '!=', '')
            ->groupBy('type')
            ->orderByDesc('total')
            ->get();

        $levelData = ProjectManage::select('level', DB::raw('count(*) as total'))
            ->whereNotNull('level')
            ->where('level', '!=', '')
            ->groupBy('level')
            ->orderByDesc('total')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => '按类型',
                    'data' => $typeData->pluck('total')->toArray(),
                    'backgroundColor' => '#8b5cf6',
                ],
                [
                    'label' => '按级别',
                    'data' => $levelData->pluck('total')->toArray(),
                    'backgroundColor' => '#f59e0b',
                ],
            ],
            'labels' => $typeData->pluck('type')->toArray(),
        ];
    }
}
