<?php

namespace App\Filament\Widgets\Charts;

use App\Models\Patent;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

/**
 * 专利类型分布柱状图
 */
class PatentTypeDistribution extends ChartWidget
{
    protected static ?string $heading = '专利类型分布';

    protected int | string | array $columnSpan = 'full';

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getData(): array
    {
        $data = Patent::select('patent_type_id', DB::raw('count(*) as total'))
            ->where('patent_type_id', '>', 0)
            ->groupBy('patent_type_id')
            ->orderByDesc('total')
            ->with('PatentType')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => '专利数量',
                    'data' => $data->pluck('total')->toArray(),
                    'backgroundColor' => '#ef4444',
                ],
            ],
            'labels' => $data->pluck('PatentType.name')->toArray(),
        ];
    }
}