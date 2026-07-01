<?php

namespace App\Filament\Widgets\Charts;

use App\Enums\Patents\Status;
use App\Models\Patent;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

/**
 * 专利状态分布柱状图
 */
class PatentStatusDistribution extends ChartWidget
{
    protected ?string $heading = '专利状态分布';

    protected static bool $isLazy = false;

    protected int|string|array $columnSpan = 1;

    protected ?string $pollingInterval = null;

    protected function getType(): string
    {
        return 'bar';
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
            $enum = Status::tryFrom($item->status);

            return $enum?->getLabel() ?? $item->status;
        })->toArray();

        return [
            'datasets' => [
                [
                    'label' => '专利数量',
                    'data' => $data->pluck('total')->toArray(),
                    'backgroundColor' => ['#6366f1', '#10b981', '#ef4444'],
                ],
            ],
            'labels' => $labels,
        ];
    }
}
