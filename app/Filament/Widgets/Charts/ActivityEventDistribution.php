<?php

namespace App\Filament\Widgets\Charts;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Support\Config as ActivitylogConfig;

/**
 * 操作事件类型分布柱状图
 */
class ActivityEventDistribution extends ChartWidget
{
    protected ?string $heading = '操作事件类型分布';

    protected int|string|array $columnSpan = 'full';

    protected ?string $pollingInterval = null;

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getData(): array
    {
        $data = ActivitylogConfig::activityModel()::select('event', DB::raw('count(*) as total'))
            ->whereNotNull('event')
            ->where('event', '!=', '')
            ->groupBy('event')
            ->orderByDesc('total')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => '操作次数',
                    'data' => $data->pluck('total')->toArray(),
                    'backgroundColor' => '#8b5cf6',
                ],
            ],
            'labels' => $data->pluck('event')->toArray(),
        ];
    }
}
