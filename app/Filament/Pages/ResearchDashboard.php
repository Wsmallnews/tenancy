<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\Charts\AwardLevelDistribution;
use App\Filament\Widgets\Charts\AwardTypeDistribution;
use App\Filament\Widgets\Charts\AwardYearTrend;
use App\Filament\Widgets\Charts\NewVarietyYearTrend;
use App\Filament\Widgets\Charts\PatentStatusDistribution;
use App\Filament\Widgets\Charts\PatentTypeDistribution;
use App\Filament\Widgets\Charts\PatentYearTrend;
use App\Filament\Widgets\Charts\ProjectBudgetSummary;
use App\Filament\Widgets\Charts\ProjectLevelDistribution;
use App\Filament\Widgets\Charts\ProjectTypeDistribution;
use App\Filament\Widgets\Charts\ProjectYearTrend;
use App\Filament\Widgets\Charts\ThesisTypeDistribution;
use App\Filament\Widgets\Charts\ThesisYearTrend;
use App\Filament\Widgets\ResearchStatsOverview;
use BackedEnum;
use Filament\Facades\Filament;
use Filament\Pages\Page;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\Widget;
use Filament\Widgets\WidgetConfiguration;
use UnitEnum;

class ResearchDashboard extends Page
{
    protected static string|UnitEnum|null $navigationGroup = '研究成果';

    protected static ?string $navigationLabel = '数据看板';

    protected static ?string $title = '研究成果数据看板';

    protected static ?string $slug = 'research-dashboard';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChartBar;

    protected static string|BackedEnum|null $activeNavigationIcon = Heroicon::ChartBar;

    protected static ?int $navigationSort = 0;

    public static function getNavigationGroup(): string|UnitEnum|null
    {
        if (Filament::getCurrentPanel()?->getId() === 'platform') {
            return '数据看板';
        }

        return static::$navigationGroup;
    }

    public static function getNavigationLabel(): string
    {
        if (Filament::getCurrentPanel()?->getId() === 'platform') {
            return static::$navigationGroup;
        }

        return static::$navigationLabel;
    }

    /**
     * @return array<class-string<Widget> | WidgetConfiguration>
     */
    public function getWidgets(): array
    {
        return [
            ResearchStatsOverview::class,
            // 论文：趋势图 + 分布图
            ThesisYearTrend::class,
            ThesisTypeDistribution::class,
            // 奖项：趋势图 + 环形图
            AwardYearTrend::class,
            AwardLevelDistribution::class,
            // 专利：趋势图 + 极坐标图
            PatentYearTrend::class,
            PatentStatusDistribution::class,
            // 新品种 + 奖项类型 + 专利类型
            NewVarietyYearTrend::class,
            AwardTypeDistribution::class,
            PatentTypeDistribution::class,
            // 项目：趋势图 + 分布图
            ProjectYearTrend::class,
            ProjectLevelDistribution::class,
            ProjectTypeDistribution::class,
            ProjectBudgetSummary::class,
        ];
    }

    public function getColumns(): int|array
    {
        return [
            'default' => 1,
            'md' => 2,
            'xl' => 3,
        ];
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make($this->getColumns())
                    ->schema(fn (): array => $this->getWidgetsSchemaComponents($this->getWidgets())),
            ]);
    }
}
