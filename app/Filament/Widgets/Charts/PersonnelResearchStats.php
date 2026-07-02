<?php

namespace App\Filament\Widgets\Charts;

use App\Models\Personnel;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

/**
 * 人员研究方向分布柱状图
 */
class PersonnelResearchStats extends ChartWidget
{
    protected ?string $heading = '研究方向分布';

    protected static bool $isLazy = false;

    protected int|string|array $columnSpan = 1;

    protected ?string $pollingInterval = null;

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getData(): array
    {
        $data = Personnel::select('research_focus', DB::raw('count(*) as total'))
            ->whereNotNull('research_focus')
            ->where('research_focus', '!=', '')
            ->groupBy('research_focus')
            ->orderByDesc('total')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => '人数',
                    'data' => $data->pluck('total')->toArray(),
                    'backgroundColor' => '#14b8a6',
                ],
            ],
            'labels' => $data->pluck('research_focus')->toArray(),
        ];
    }
}
