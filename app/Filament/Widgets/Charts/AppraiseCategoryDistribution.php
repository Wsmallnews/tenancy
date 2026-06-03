<?php

namespace App\Filament\Widgets\Charts;

use App\Models\Appraise;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

/**
 * 种质按分类分布柱状图
 */
class AppraiseCategoryDistribution extends ChartWidget
{
    protected static ?string $heading = '种质分类分布';

    protected int | string | array $columnSpan = 'full';

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getData(): array
    {
        $data = Appraise::select('category_id', DB::raw('count(*) as total'))
            ->where('category_id', '>', 0)
            ->groupBy('category_id')
            ->orderByDesc('total')
            ->with('category')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => '种质数量',
                    'data' => $data->pluck('total')->toArray(),
                    'backgroundColor' => '#f59e0b',
                ],
            ],
            'labels' => $data->pluck('category.name')->toArray(),
        ];
    }
}