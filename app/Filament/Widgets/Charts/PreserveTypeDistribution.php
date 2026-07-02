<?php

namespace App\Filament\Widgets\Charts;

use App\Models\Preserve;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

/**
 * 保存方式分布柱状图
 */
class PreserveTypeDistribution extends ChartWidget
{
    protected ?string $heading = '保存方式分布';

    protected static bool $isLazy = false;

    protected int|string|array $columnSpan = 1;

    protected ?string $pollingInterval = null;

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getData(): array
    {
        $data = Preserve::select('preserve_type', DB::raw('count(*) as total'))
            ->whereNotNull('preserve_type')
            ->where('preserve_type', '!=', '')
            ->groupBy('preserve_type')
            ->orderByDesc('total')
            ->get();

        $labels = $data->map(function ($item) {
            return $item->preserve_type?->getLabel() ?? $item->preserve_type;
        })->toArray();

        return [
            'datasets' => [
                [
                    'label' => '保存数量',
                    'data' => $data->pluck('total')->toArray(),
                    'backgroundColor' => '#06b6d4',
                ],
            ],
            'labels' => $labels,
        ];
    }
}
