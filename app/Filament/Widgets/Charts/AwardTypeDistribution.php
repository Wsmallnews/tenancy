<?php

namespace App\Filament\Widgets\Charts;

use App\Models\Award;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

/**
 * 奖项类型分布柱状图
 */
class AwardTypeDistribution extends ChartWidget
{
    protected static ?string $heading = '奖项类型分布';

    protected int | string | array $columnSpan = 'full';

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getData(): array
    {
        $data = Award::select('award_type_id', DB::raw('count(*) as total'))
            ->where('award_type_id', '>', 0)
            ->groupBy('award_type_id')
            ->orderByDesc('total')
            ->with('AwardType')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => '奖项数量',
                    'data' => $data->pluck('total')->toArray(),
                    'backgroundColor' => '#f59e0b',
                ],
            ],
            'labels' => $data->pluck('AwardType.name')->toArray(),
        ];
    }
}