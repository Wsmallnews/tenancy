<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\Charts\ActivityEventDistribution;
use App\Filament\Widgets\Charts\ActivityTrend;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\Widget;
use Filament\Widgets\WidgetConfiguration;
use UnitEnum;

class SystemDashboard extends Page
{
    protected static string|UnitEnum|null $navigationGroup = '设置管理';

    protected static ?string $navigationLabel = '数据看板';

    protected static ?string $title = '系统数据看板';

    protected static ?string $slug = 'system-dashboard';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChartBar;

    protected static string|BackedEnum|null $activeNavigationIcon = Heroicon::ChartBar;

    protected static ?int $navigationSort = 0;

    protected static bool $shouldRegisterNavigation = false;

    /**
     * @return array<class-string<Widget> | WidgetConfiguration>
     */
    public function getWidgets(): array
    {
        return [
            ActivityTrend::class,
            ActivityEventDistribution::class,
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
