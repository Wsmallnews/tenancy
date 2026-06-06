<?php

namespace App\Filament\Widgets\Charts;

use App\Models\Company;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

/**
 * 合作单位地区分布柱状图
 */
class CompanyRegionDistribution extends ChartWidget
{
    protected ?string $heading = '合作单位地区分布（Top 10）';

    protected int|string|array $columnSpan = 'full';

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getData(): array
    {
        $data = Company::select('province_name', DB::raw('count(*) as total'))
            ->whereNotNull('province_name')
            ->where('province_name', '!=', '')
            ->groupBy('province_name')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        return [
            'datasets' => [
                [
                    'label' => '单位数量',
                    'data' => $data->pluck('total')->toArray(),
                    'backgroundColor' => '#f97316',
                ],
            ],
            'labels' => $data->pluck('province_name')->toArray(),
        ];
    }
}
