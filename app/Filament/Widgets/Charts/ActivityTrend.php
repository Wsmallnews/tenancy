<?php

namespace App\Filament\Widgets\Charts;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Support\Config as ActivitylogConfig;

/**
 * 系统操作活跃度趋势折线图
 */
class ActivityTrend extends ChartWidget
{
    protected ?string $heading = '系统操作活跃度趋势（近30天）';

    protected static bool $isLazy = false;

    protected int|string|array $columnSpan = 2;

    protected ?string $pollingInterval = null;

    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        $data = ActivitylogConfig::activityModel()::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('count(*) as total')
        )
            ->where('created_at', '>=', now()->subDays(29)->startOfDay())
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => '操作次数',
                    'data' => $data->pluck('total')->toArray(),
                    'fill' => 'start',
                    'tension' => 0.3,
                ],
            ],
            'labels' => $data->pluck('date')->toArray(),
        ];
    }
}
