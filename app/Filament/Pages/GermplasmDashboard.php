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
use Filament\Pages\Page;
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

    protected static ?string $slug = 'germplasm-dashboard';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChartBar;

    protected static string|BackedEnum|null $activeNavigationIcon = Heroicon::ChartBar;

    protected static ?int $navigationSort = 0;

    /**
     * @return array<class-string<Widget> | WidgetConfiguration>
     */
    public function getWidgets(): array
    {
        return [
            GermplasmStatsOverview::class,
            AppraiseApplyStatsOverview::class,
            AppraiseCategoryDistribution::class,
            AppraiseMonthlyTrend::class,
            AppraiseOriginDistribution::class,
            AppraiseTypeDistribution::class,
            AppraiseApplyMonthlyTrend::class,
            AppraiseApplyStatus::class,
            AssembleRegionDistribution::class,
            AssembleYearTrend::class,
            PreserveTypeDistribution::class,
            CatalogYearTrend::class,
            NewVarietyYearTrend::class,
        ];
    }

    public function getColumns(): int|array
    {
        return [
            'default' => 1,
            'md' => 1,
            'lg' => 2,
            '2xl' => 3
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
