<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\Charts\CompanyRegionDistribution;
use App\Filament\Widgets\Charts\PersonnelEducationStats;
use App\Filament\Widgets\Charts\PersonnelResearchStats;
use BackedEnum;
use Filament\Facades\Filament;
use Filament\Pages\Page;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\Widget;
use Filament\Widgets\WidgetConfiguration;
use UnitEnum;

class AttributeDashboard extends Page
{
    protected static string|UnitEnum|null $navigationGroup = '属性选项';

    protected static ?string $navigationLabel = '数据看板';

    protected static ?string $title = '属性数据看板';

    protected static ?string $slug = 'attribute-dashboard';

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
            CompanyRegionDistribution::class,
            PersonnelEducationStats::class,
            PersonnelResearchStats::class,
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
