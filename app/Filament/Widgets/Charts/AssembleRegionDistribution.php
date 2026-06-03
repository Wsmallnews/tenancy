<?php

namespace App\Filament\Widgets\Charts;

use App\Models\Assemble;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

/**
 * 收集地区分布柱状图
 */
class AssembleRegionDistribution extends ChartWidget
{
    protected static ?string $heading = '收集地分布（Top 10）';

    protected int | string | array $columnSpan = 'full';

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getData(): array
    {
        $data = Assemble::select('province_name', DB::raw('count(*) as total'))
            ->whereNotNull('province_name')
            ->where('province_name', '!=', '')
            ->groupBy('province_name')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        return [
            'datasets' => [
                [
                    'label' => '收集数量',
                    'data' => $data->pluck('total')->toArray(),
                    'backgroundColor' => '#06b6d4',
                ],
            ],
            'labels' => $data->pluck('province_name')->toArray(),
        ];
    }
}