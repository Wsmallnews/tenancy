<?php

namespace App\Filament\Widgets\Charts;

use App\Enums\Patents\Status;
use App\Models\Patent;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

/**
 * 专利状态分布极坐标图
 */
class PatentStatusDistribution extends ChartWidget
{
    protected ?string $heading = '专利状态分布';

    protected static bool $isLazy = false;

    protected int|string|array $columnSpan = 1;

    protected ?string $pollingInterval = null;

    protected function getType(): string
    {
        return 'polarArea';
    }

    protected function getData(): array
    {
        $data = Patent::select('status', DB::raw('count(*) as total'))
            ->whereNotNull('status')
            ->where('status', '!=', '')
            ->groupBy('status')
            ->orderByDesc('total')
            ->get();

        $labels = $data->map(function ($item) {
            $enum = $item->status;

            return $enum?->getLabel() ?? $item->status;
        })->toArray();

        return [
            'datasets' => [
                [
                    'data' => $data->pluck('total')->toArray(),
                    'backgroundColor' => ['#6366f1', '#10b981', '#ef4444', '#f59e0b'],
                ],
            ],
            'labels' => $labels,
        ];
    }
}
