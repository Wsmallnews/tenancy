<?php

namespace App\Filament\Pages;

use App\Settings\OtherSettings;
use BackedEnum;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Forms;
use Filament\Pages\SettingsPage;
use Filament\Schemas;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Alignment;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class OtherSetting extends SettingsPage
{
    use HasPageShield;

    protected static ?string $navigationLabel = '其他设置';

    protected static string|UnitEnum|null $navigationGroup = '设置管理';

    protected static ?string $title = '其他设置';

    protected static ?string $slug = 'other-settings';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog8Tooth;

    protected static string|BackedEnum|null $activeNavigationIcon = Heroicon::Cog8Tooth;

    protected static ?int $navigationSort = 4;

    protected static string $settings = OtherSettings::class;

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Schemas\Components\Section::make('学历')->schema([
                    Forms\Components\Repeater::make('qualification')
                        ->hiddenLabel()
                        ->simple(
                            Forms\Components\TextInput::make('value')
                                ->hiddenLabel()
                                ->placeholder('请输入选项名称')
                                ->required()
                                ->columnSpanFull()
                        )
                        ->required()
                        ->minItems(1)
                        ->addActionAlignment(Alignment::Start)
                        ->addActionLabel('添加选项')
                        ->columnSpanFull()
                        ->grid(['md' => 2, 'lg' => 3, 'xl' => 4]),
                ])->columns(2)->columnSpanFull(),
            ]);
    }
}
