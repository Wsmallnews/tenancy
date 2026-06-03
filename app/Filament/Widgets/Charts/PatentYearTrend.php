<?php

namespace App\Filament\Widgets\Charts;

use App\Models\Patent;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

/**
 * 专利申请/授权年度趋势双线折线图
 */
class PatentYearTrend extends ChartWidget
{
    protected static ?string $heading = '专利申请/授权年度趋势';

    protected int | string | array $columnSpan = 'full';

    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        // 获取所有年份范围
        $applyData = Patent::select(
                DB::raw('YEAR(applied_at) as year'),
                DB::raw('count(*) as total')
            )
            ->whereNotNull('applied_at')
            ->groupBy('year')
            ->orderBy('year')
            ->get()
            ->keyBy('year');

        $authData = Patent::select(
                DB::raw('YEAR(authd_at) as year'),
                DB::raw('count(*) as total')
            )
            ->whereNotNull('authd_at')
            ->groupBy('year')
            ->orderBy('year')
            ->get()
            ->keyBy('year');

        // 合并所有年份
        $years = $applyData->keys()->merge($authData->keys())->unique()->sort()->values();

        return [
            'datasets' => [
                [
                    'label' => '申请量',
                    'data' => $years->map(fn($year) => $applyData->get($year)?->total ?? 0)->toArray(),
                    'borderColor' => '#3b82f6',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'fill' => 'start',
                    'tension' => 0.3,
                ],
                [
                    'label' => '授权量',
                    'data' => $years->map(fn($year) => $authData->get($year)?->total ?? 0)->toArray(),
                    'borderColor' => '#10b981',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.1)',
                    'fill' => 'start',
                    'tension' => 0.3,
                ],
            ],
            'labels' => $years->toArray(),
        ];
    }
}