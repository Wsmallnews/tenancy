<?php

namespace App\Filament\Widgets\Charts;

use App\Models\Thesis;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

/**
 * 论文类型分布柱状图
 */
class ThesisTypeDistribution extends ChartWidget
{
    protected ?string $heading = '论文类型分布';

    protected int|string|array $columnSpan = 'full';

    protected ?string $pollingInterval = null;

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getData(): array
    {
        $data = Thesis::select('thesis_type_id', DB::raw('count(*) as total'))
            ->where('thesis_type_id', '>', 0)
            ->groupBy('thesis_type_id')
            ->orderByDesc('total')
            ->with('thesisType')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => '论文数量',
                    'data' => $data->pluck('total')->toArray(),
                    'backgroundColor' => '#3b82f6',
                ],
            ],
            'labels' => $data->pluck('thesisType.name')->toArray(),
        ];
    }
}
