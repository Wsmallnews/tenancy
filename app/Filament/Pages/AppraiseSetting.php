<?php

namespace App\Filament\Pages;

use BackedEnum;
use App\Settings\AppraiseSettings;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Forms;
use Filament\Pages\SettingsPage;
use Filament\Schemas;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Alignment;
use UnitEnum;

class AppraiseSetting extends SettingsPage
{
    use HasPageShield;

    protected static ?string $navigationLabel = '种质设置';

    protected static string | UnitEnum | null $navigationGroup = '设置管理';

    protected static ?string $title = '种质设置';

    protected static ?string $slug = 'appraise-settings';

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?int $navigationSort = 2;

    protected static string $settings = AppraiseSettings::class;

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Schemas\Components\Section::make('种质类型')->schema([
                    Forms\Components\Repeater::make('germplasm_type')
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
                Schemas\Components\Section::make('用途')->schema([
                    Forms\Components\Repeater::make('germplasm_use')
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
                Schemas\Components\Section::make('果实用途')->schema([
                    Forms\Components\Repeater::make('fruit_use')
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
                Schemas\Components\Section::make('植株用途')->schema([
                    Forms\Components\Repeater::make('plant_use')
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
                Schemas\Components\Section::make('种植收集源')->schema([
                    Forms\Components\Repeater::make('assemble_resource')
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
                Schemas\Components\Section::make('收集材料类型')->schema([
                    Forms\Components\Repeater::make('assemble_material_type')
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
                Schemas\Components\Section::make('基因型鉴定方法')->schema([
                    Forms\Components\Repeater::make('gene_identify_method')
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
                ])->columns(2)->columnSpanFull()
            ]);
    }
}
