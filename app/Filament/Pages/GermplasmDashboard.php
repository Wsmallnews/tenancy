<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\AppraiseApplyStatsOverview;
use App\Filament\Widgets\Charts\AppraiseApplyMonthlyTrend;
use App\Filament\Widgets\Charts\AppraiseApplyStatus;
use App\Filament\Widgets\Charts\AppraiseCategoryDistribution;
use App\Filament\Widgets\Charts\AppraiseMonthlyTrend;
use App\Filament\Widgets\Charts\AppraiseOriginDistribution;
use App\Filament\Widgets\Charts\AppraiseTypeDistribution;
use App\Filament\Widgets\Charts\AssembleRegionDistribution;
use App\Filament\Widgets\Charts\AssembleYearTrend;
use App\Filament\Widgets\Charts\CatalogYearTrend;
use App\Filament\Widgets\Charts\NewVarietyYearTrend;
use App\Filament\Widgets\Charts\PreserveTypeDistribution;
use App\Filament\Widgets\GermplasmStatsOverview;
use BackedEnum;
use Filament\Facades\Filament;
use Filament\Pages\Page;
use Filament\Panel;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\Widget;
use Filament\Widgets\WidgetConfiguration;
use UnitEnum;

class GermplasmDashboard extends Page
{
    protected static string|UnitEnum|null $navigationGroup = '种质资源库(圃)';

    protected static ?string $navigationLabel = '数据看板';

    protected static ?string $title = '种质资源数据看板';

    protected static ?string $slug = null;

    protected static string $routePath = '/';

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

    public static function getRoutePath(Panel $panel): string
    {
        return static::$routePath;
    }

    /**
     * @return array<class-string<Widget> | WidgetConfiguration>
     */
    public function getWidgets(): array
    {
        return [
            GermplasmStatsOverview::class,
            AppraiseApplyStatsOverview::class,
            // 趋势图（line, colSpan=2）+ 分布图（doughnut/pie/polarArea, colSpan=1）
            AppraiseMonthlyTrend::class,
            AppraiseTypeDistribution::class,
            // 三列分布图
            AppraiseCategoryDistribution::class,
            AppraiseOriginDistribution::class,
            PreserveTypeDistribution::class,
            // 趋势图 + 分布图
            AssembleYearTrend::class,
            AssembleRegionDistribution::class,
            AppraiseApplyMonthlyTrend::class,
            AppraiseApplyStatus::class,
            // 趋势图
            CatalogYearTrend::class,
            NewVarietyYearTrend::class,
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
