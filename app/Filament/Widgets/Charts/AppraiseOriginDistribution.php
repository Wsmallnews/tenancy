<?php

namespace App\Filament\Widgets\Charts;

use App\Models\Appraise;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

/**
 * 种质原产地来源分布柱状图（Top 10省份）
 */
class AppraiseOriginDistribution extends ChartWidget
{
    protected ?string $heading = '种质原产地来源分布（Top 10）';

    protected int|string|array $columnSpan = 'full';

    protected ?string $pollingInterval = null;

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getData(): array
    {
        $data = Appraise::select('province_name', DB::raw('count(*) as total'))
            ->whereNotNull('province_name')
            ->where('province_name', '!=', '')
            ->groupBy('province_name')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        return [
            'datasets' => [
                [
                    'label' => '种质来源数量',
                    'data' => $data->pluck('total')->toArray(),
                    'backgroundColor' => '#10b981',
                ],
            ],
            'labels' => $data->pluck('province_name')->toArray(),
        ];
    }
}
