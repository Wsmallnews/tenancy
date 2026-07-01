<?php

namespace App\Filament\Widgets\Charts;

use App\Models\AppraiseApply;
use Filament\Widgets\ChartWidget;

/**
 * 用种申请状态分布柱状图
 */
class AppraiseApplyStatus extends ChartWidget
{
    protected ?string $heading = '用种申请状态分布';

    protected static bool $isLazy = false;

    protected int|string|array $columnSpan = 1;

    protected ?string $pollingInterval = null;

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getData(): array
    {
        $applying = AppraiseApply::applying()->count();
        $agree = AppraiseApply::agree()->count();
        $refuse = AppraiseApply::refuse()->count();

        return [
            'datasets' => [
                [
                    'label' => '申请数量',
                    'data' => [$applying, $agree, $refuse],
                    'backgroundColor' => ['#f59e0b', '#10b981', '#ef4444'],
                ],
            ],
            'labels' => ['待处理', '已同意', '已拒绝'],
        ];
    }
}
