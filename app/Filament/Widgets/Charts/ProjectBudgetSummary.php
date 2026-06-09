<?php

namespace App\Filament\Widgets\Charts;

use App\Models\ProjectManage;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

/**
 * 年度项目经费汇总柱状图
 */
class ProjectBudgetSummary extends ChartWidget
{
    protected ?string $heading = '年度项目经费汇总';

    protected int|string|array $columnSpan = 'full';

    protected ?string $pollingInterval = null;

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getData(): array
    {
        $data = ProjectManage::select(
            DB::raw('YEAR(created_at) as year'),
            DB::raw('SUM(CAST(budget AS DECIMAL(15,2))) as total_budget')
        )
            ->whereNotNull('budget')
            ->where('budget', '!=', '')
            ->groupBy('year')
            ->orderBy('year')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => '经费总额（元）',
                    'data' => $data->pluck('total_budget')->map(fn ($v) => round((float) $v, 2))->toArray(),
                    'backgroundColor' => '#10b981',
                ],
            ],
            'labels' => $data->pluck('year')->toArray(),
        ];
    }
}
