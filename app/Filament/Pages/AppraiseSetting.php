<?php

namespace App\Filament\Pages;

use App\Settings\AppraiseSettings;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;
use Filament\Support\Enums\Alignment;

class AppraiseSetting extends SettingsPage
{
    use HasPageShield;

    protected static ?string $navigationLabel = '种质设置';

    protected static ?string $navigationGroup = '设置管理';

    protected static ?string $slug = 'appraise-settings';

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?int $navigationSort = 1;

    protected static string $settings = AppraiseSettings::class;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('选项字典')->schema([
                    Forms\Components\Repeater::make('germplasm_type')
                        ->label('种质类型')
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

                    Forms\Components\Repeater::make('germplasm_use')
                        ->label('用途')
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

                    Forms\Components\Repeater::make('fruit_use')
                        ->label('果实用途')
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

                    Forms\Components\Repeater::make('plant_use')
                        ->label('植株用途')
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

                    Forms\Components\Repeater::make('assemble_resource')
                        ->label('种植收集源')
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

                    Forms\Components\Repeater::make('assemble_material_type')
                        ->label('收集材料类型')
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
                ])->columns(2),
            ]);
    }
}
