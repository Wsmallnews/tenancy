<?php

namespace App\Filament\Widgets\Charts;

use App\Models\Personnel;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

/**
 * 人员研究方向分布雷达图
 */
class PersonnelResearchStats extends ChartWidget
{
    protected ?string $heading = '人员研究方向分布';

    protected static bool $isLazy = false;

    protected int|string|array $columnSpan = 'full';

    protected ?string $pollingInterval = null;

    protected function getType(): string
    {
        return 'radar';
    }

    protected function getData(): array
    {
        $data = Personnel::select('research_focus', DB::raw('count(*) as total'))
            ->whereNotNull('research_focus')
            ->where('research_focus', '!=', '')
            ->groupBy('research_focus')
            ->orderByDesc('total')
            ->limit(8)
            ->get();

        return [
            'datasets' => [
                [
                    'label' => '人数',
                    'data' => $data->pluck('total')->toArray(),
                    'backgroundColor' => 'rgba(20, 184, 166, 0.3)',
                    'borderColor' => '#14b8a6',
                    'pointBackgroundColor' => '#14b8a6',
                ],
            ],
            'labels' => $data->pluck('research_focus')->toArray(),
        ];
    }
}
