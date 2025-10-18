<?php

namespace App\Filament\Pages;

use BackedEnum;
use App\Settings\ProjectSettings;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Forms;
use Filament\Pages\SettingsPage;
use Filament\Schemas;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Alignment;
use UnitEnum;

class ProjectSetting extends SettingsPage
{
    use HasPageShield;

    protected static ?string $navigationLabel = '项目设置';

    protected static string | UnitEnum | null $navigationGroup = '设置管理';

    protected static ?string $title = '项目设置';

    protected static ?string $slug = 'project-settings';

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?int $navigationSort = 3;

    protected static string $settings = ProjectSettings::class;

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Schemas\Components\Section::make('项目类型')->schema([
                    Forms\Components\Repeater::make('project_type')
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
                Schemas\Components\Section::make('所属学科')->schema([
                    Forms\Components\Repeater::make('project_subject')
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
                Schemas\Components\Section::make('项目级别')->schema([
                    Forms\Components\Repeater::make('project_level')
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
