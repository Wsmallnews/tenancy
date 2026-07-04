<?php

namespace App\Filament\Widgets\Charts;

use App\Models\Appraise;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

/**
 * 种质分类分布饼图
 */
class AppraiseCategoryDistribution extends ChartWidget
{
    protected ?string $heading = '种质分类分布';

    protected static bool $isLazy = false;

    protected int|string|array $columnSpan = 1;

    protected ?string $pollingInterval = null;

    protected function getType(): string
    {
        return 'pie';
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
                    'data' => $data->pluck('total')->toArray(),
                    'backgroundColor' => ['#f59e0b', '#3b82f6', '#10b981', '#8b5cf6', '#ef4444', '#ec4899', '#06b6d4', '#84cc16'],
                ],
            ],
            'labels' => $data->pluck('category.name')->toArray(),
        ];
    }
}
