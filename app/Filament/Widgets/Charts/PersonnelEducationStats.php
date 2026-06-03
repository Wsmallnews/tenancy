<?php

namespace App\Filament\Widgets\Charts;

use App\Models\Personnel;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

/**
 * 人员学历与职称分布柱状图
 */
class PersonnelEducationStats extends ChartWidget
{
    protected static ?string $heading = '人员学历分布';

    protected int | string | array $columnSpan = 'full';

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getData(): array
    {
        $data = Personnel::select('qualification', DB::raw('count(*) as total'))
            ->whereNotNull('qualification')
            ->where('qualification', '!=', '')
            ->groupBy('qualification')
            ->orderByDesc('total')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => '人数',
                    'data' => $data->pluck('total')->toArray(),
                    'backgroundColor' => '#6366f1',
                ],
            ],
            'labels' => $data->pluck('qualification')->toArray(),
        ];
    }
}