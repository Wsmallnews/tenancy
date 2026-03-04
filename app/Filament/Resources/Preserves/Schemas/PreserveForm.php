<?php

namespace App\Filament\Resources\Preserves\Schemas;

use App\Enums\Preserves\Status;
use App\Enums\Preserves\PreserveType;
use App\Models\Appraise;
use Filament\Forms;
use Filament\Infolists;
use Filament\Schemas;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

class PreserveForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Schemas\Components\Flex::make([
                    Schemas\Components\Group::make()->schema([
                        Schemas\Components\Section::make('种质信息')->schema([
                            Forms\Components\Select::make('appraise_id')->label('选择种质')
                                ->relationship(name: 'appraise', titleAttribute: 'name', modifyQueryUsing: function (Builder $query) {
                                    return $query->normal()->orderBy('order_column', 'asc');
                                })
                                ->placeholder('请选择种质')
                                ->searchable()
                                ->preload()
                                ->live()
                                ->required(),
                            Schemas\Components\Grid::make([
                                    'default' => 1,
                                    'lg' => 2,
                                    'xl' => 3,
                                ])
                                ->extraAttributes([
                                    'class' => 'sn-grid-table',
                                ])
                                ->schema(function (Get $get) {
                                    if ($get('appraise_id') && $appraise = Appraise::findOrFail($get('appraise_id'))) {
                                        $coverMedia = $appraise->getFirstMedia('cover');

                                        return [
                                            Infolists\Components\ImageEntry::make('appraise_cover')
                                                ->label('种质封面图')
                                                ->state($coverMedia?->getFullUrl())
                                                ->extraAttributes([
                                                    'class' => 'sn-two-rows'
                                                ]),
                                            Infolists\Components\TextEntry::make('appraise_resource_no')
                                                ->label('全国统一编号')
                                                ->state($appraise->resource_no),
                                            Infolists\Components\TextEntry::make('appraise_name')
                                                ->label('种质中文名')
                                                ->state($appraise->name),
                                            Infolists\Components\TextEntry::make('appraise_en_name')
                                                ->label('种质外文名')
                                                ->state($appraise->en_name),
                                            Infolists\Components\TextEntry::make('appraise_subject_name')
                                                ->label('科名')
                                                ->state($appraise->subject_name),
                                            Infolists\Components\TextEntry::make('appraise_genus_name')
                                                ->label('属名')
                                                ->state($appraise->genus_name),
                                            Infolists\Components\TextEntry::make('appraise_species_name')
                                                ->label('学名')
                                                ->state($appraise->species_name),
                                        ];
                                    }
                                })
                                ->visible(fn(Get $get): bool => boolval($get('appraise_id')))
                                ->columnSpanFull(),
                        ]),
                        Schemas\Components\Section::make('基本信息')->schema([
                            Forms\Components\TextInput::make('preserve_no')->label('保存编号')
                                ->placeholder('请输入保存编号')
                                ->required(),
                            Forms\Components\TextInput::make('preserve_position')->label('保存位置')
                                ->placeholder('请输入保存位置')
                                ->required(),
                            Forms\Components\DatePicker::make('putin_at')->label('入库日期')
                                ->placeholder('请选择入库日期')
                                ->native(false)
                                ->displayFormat('Y-m-d')
                                ->required(),
                            Forms\Components\TextInput::make('num')->label('初始数量')->integer()
                                ->placeholder('请输入初始数量')
                                ->required(),
                            Forms\Components\TextInput::make('weight')->label('初始质量')
                                ->placeholder('请输入初始质量')
                                ->suffix('KG')
                                ->required(),
                        ])->columns(2),
                        Schemas\Components\Section::make('保存信息')->schema([
                            Forms\Components\ToggleButtons::make('preserve_type')
                                ->label('保存类型')
                                ->options(PreserveType::class)
                                ->default(PreserveType::GermplasmNursery)
                                ->live()
                                ->inline(),
                            
                            // 种质圃保存
                            Schemas\Components\Group::make()
                                ->schema([
                                    Forms\Components\TextInput::make('germplasm_nursery_habitat_information')->label('生境信息')
                                        ->placeholder('请输入生境信息')
                                        ->required(),
                                    Forms\Components\TextInput::make('germplasm_nursery_disease_pest_information')->label('病虫害信息')
                                        ->placeholder('请输入病虫害信息')
                                        ->required(),
                                ])->columns(2)
                                ->visible(fn(Get $get): bool => $get('preserve_type') === PreserveType::GermplasmNursery),

                            // 试管苗保存
                            Schemas\Components\Group::make()
                                ->schema([
                                    Forms\Components\TextInput::make('test_tube_seedling_cultivation_medium_formula')->label('培养基配方')
                                        ->placeholder('请输入培养基配方')
                                        ->required(),
                                    Forms\Components\TextInput::make('test_tube_seedling_culture_conditions')->label('培养条件')
                                        ->placeholder('请输入培养条件')
                                        ->required(),
                                ])->columns(2)
                                ->visible(fn(Get $get): bool => $get('preserve_type') === PreserveType::TestTubeSeedling),

                            // 超低温保存
                            Schemas\Components\Group::make()
                                ->schema([
                                    Forms\Components\DatePicker::make('ultra_low_temperature_at')->label('储藏日期')
                                        ->placeholder('请选择储藏日期')
                                        ->native(false)
                                        ->displayFormat('Y-m-d')
                                        ->required(),
                                    Forms\Components\TextInput::make('ultra_low_temperature_position')->label('保存位置')
                                        ->placeholder('请输入保存位置')
                                        ->required(),
                                    Forms\Components\TextInput::make('ultra_low_temperature_no')->label('冷冻管编号')
                                        ->placeholder('请输入冷冻管编号')
                                        ->required(),
                                    Forms\Components\TextInput::make('ultra_low_temperature_before_handle_method')->label('前处理方式')
                                        ->placeholder('请输入前处理方式')
                                        ->required(),
                                    Forms\Components\TextInput::make('ultra_low_temperature_quick_freeze_method')->label('降温速冻方法')
                                        ->placeholder('请输入降温速冻方法')
                                        ->required(),
                                    Forms\Components\TextInput::make('ultra_low_temperature_defrost_method')->label('解冻方法')
                                        ->placeholder('请输入解冻方法')
                                        ->required(),
                                    Forms\Components\TextInput::make('ultra_low_temperature_original_vitality_data')->label('原始活力数据')
                                        ->placeholder('请输入原始活力数据')
                                        ->required(),
                                    Forms\Components\TextInput::make('ultra_low_temperature_recover_cultivation_medium')->label('恢复培养基')
                                        ->placeholder('请输入恢复培养基')
                                        ->required(),
                                    Forms\Components\TextInput::make('ultra_low_temperature_recovery_process')->label('复苏程序')
                                        ->placeholder('请输入复苏程序')
                                        ->required(),
                                ])->columns(2)
                                ->visible(fn(Get $get): bool => $get('preserve_type') === PreserveType::UltraLowTemperature),

                            // 原生境保存
                            Schemas\Components\Group::make()
                                ->schema([
                                    Forms\Components\TextInput::make('original_habitat_variant_type')->label('变种类型')
                                        ->placeholder('请输入变种类型')
                                        ->required(),
                                    Forms\Components\TextInput::make('original_habitat_address')->label('详细地点')
                                        ->placeholder('请输入详细地点')
                                        ->required(),
                                    Forms\Components\TextInput::make('original_habitat_longitude')->label('经度')
                                        ->placeholder('请输入经度')
                                        ->required(),
                                    Forms\Components\TextInput::make('original_habitat_latitude')->label('纬度')
                                        ->placeholder('请输入纬度')
                                        ->required(),
                                    Forms\Components\TextInput::make('original_habitat_terrain')->label('地形')
                                        ->placeholder('请输入地形')
                                        ->required(),
                                    Forms\Components\TextInput::make('original_habitat_slope')->label('坡度')
                                        ->placeholder('请输入坡度')
                                        ->required(),
                                    Forms\Components\TextInput::make('original_habitat_illuminate')->label('光照')
                                        ->placeholder('请输入光照')
                                        ->required(),
                                    Forms\Components\TextInput::make('original_habitat_moisture')->label('水分')
                                        ->placeholder('请输入水分')
                                        ->required(),
                                    Forms\Components\TextInput::make('original_habitat_nursery_habitat_type')->label('生境类型')
                                        ->placeholder('请输入生境类型')
                                        ->required(),
                                    Forms\Components\TextInput::make('original_habitat_associated_plants')->label('伴生植物')
                                        ->placeholder('请输入伴生植物')
                                        ->required(),
                                    Forms\Components\TextInput::make('original_habitat_population_num')->label('种群数量')
                                        ->placeholder('请输入种群数量')
                                        ->required(),
                                    Forms\Components\TextInput::make('original_habitat_breeding_situation')->label('繁殖情况')
                                        ->placeholder('请输入繁殖情况')
                                        ->required(),
                                    Forms\Components\TextInput::make('original_habitat_phenological_record')->label('物候记录')
                                        ->placeholder('请输入物候记录')
                                        ->required(),
                                ])->columns(2)
                                ->visible(fn(Get $get): bool => $get('preserve_type') === PreserveType::OriginalHabitat),
                        ])
                    ])->columns(1),
                    Schemas\Components\Section::make('状态')->schema([
                        Forms\Components\TextInput::make('order_column')->label('排序')->integer()
                            ->placeholder('正序排列')
                            ->rules(['integer', 'min:0']),
                        Forms\Components\Radio::make('status')
                            ->label('状态')
                            ->default(Status::Normal)
                            ->inline()
                            ->options(Status::class),
                    ])->grow(false),
                ])
                ->columnSpanFull()
                ->from('lg')
            ]);
    }
}
