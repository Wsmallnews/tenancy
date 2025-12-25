<?php

namespace App\Filament\Resources\Preserves\Schemas;

use App\Enums\Preserves\PreserveType;
use App\Features\Common;
use Filament\Infolists;
use Filament\Schemas;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;

class PreserveInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Schemas\Components\Text::make(Common::title('基础信息')),
                Schemas\Components\Grid::make([
                        'default' => 1,
                        'lg' => 2,
                        'xl' => 3,
                    ])
                    ->extraAttributes([
                        'class' => 'sn-grid-table',
                    ])
                    ->schema([
                        Infolists\Components\TextEntry::make('preserve_no')
                            ->label('保存编号'),
                        Infolists\Components\TextEntry::make('preserve_position')
                            ->label('保存位置'),
                        Infolists\Components\TextEntry::make('putin_at')
                            ->label('入库日期')
                            ->date('Y-m-d'),
                        Infolists\Components\TextEntry::make('num')
                            ->label('初始数量')
                            ->numeric(),
                        Infolists\Components\TextEntry::make('weight')
                            ->label('初始质量')
                            ->suffix('KG'),
                        Infolists\Components\TextEntry::make('created_at')
                            ->label('创建时间')
                            ->dateTime(),
                        Infolists\Components\TextEntry::make('updated_at')
                            ->label('更新时间')
                            ->dateTime(),
                        Infolists\Components\TextEntry::make('order_column')
                            ->label('排序')
                            ->numeric(),
                        Infolists\Components\TextEntry::make('status')
                            ->label('保存状态'),
                    ])->columnSpanFull(),

                Schemas\Components\Text::make(function (Model $record) {
                    return Common::title('保存信息: ' . $record->preserve_type?->getLabel());
                }),
                
                // 种质圃保存
                Schemas\Components\Grid::make([
                        'default' => 1,
                        'lg' => 2,
                        'xl' => 3,
                    ])
                    ->extraAttributes([
                        'class' => 'sn-grid-table',
                    ])
                    ->schema([
                        Infolists\Components\TextEntry::make('germplasm_nursery_habitat_information')
                            ->label('生境信息'),
                        Infolists\Components\TextEntry::make('germplasm_nursery_disease_pest_information')
                            ->label('病虫害信息'),
                    ])
                    ->columnSpanFull()
                    ->visible(fn(Model $record) => $record->preserve_type == PreserveType::GermplasmNursery),

                // 试管苗保存
                Schemas\Components\Grid::make([
                        'default' => 1,
                        'lg' => 2,
                        'xl' => 3,
                    ])
                    ->extraAttributes([
                        'class' => 'sn-grid-table',
                    ])
                    ->schema([
                        Infolists\Components\TextEntry::make('test_tube_seedling_cultivation_medium_formula')
                            ->label('培养基配方'),
                        Infolists\Components\TextEntry::make('test_tube_seedling_culture_conditions')
                            ->label('培养条件'),
                    ])
                    ->columnSpanFull()
                    ->visible(fn(Model $record) => $record->preserve_type == PreserveType::TestTubeSeedling),
                
                // 超低温保存
                Schemas\Components\Grid::make([
                        'default' => 1,
                        'lg' => 2,
                        'xl' => 3,
                    ])
                    ->extraAttributes([
                        'class' => 'sn-grid-table',
                    ])
                    ->schema([
                        Infolists\Components\TextEntry::make('ultra_low_temperature_at')
                            ->label('储藏日期')
                            ->date('Y-m-d'),
                        Infolists\Components\TextEntry::make('ultra_low_temperature_position')
                            ->label('保存位置'),
                        Infolists\Components\TextEntry::make('ultra_low_temperature_no')
                            ->label('冷冻管编号'),
                        Infolists\Components\TextEntry::make('ultra_low_temperature_before_handle_method')
                            ->label('前处理方式'),
                        Infolists\Components\TextEntry::make('ultra_low_temperature_quick_freeze_method')
                            ->label('降温速冻方法'),
                        Infolists\Components\TextEntry::make('ultra_low_temperature_defrost_method')
                            ->label('解冻方法'),
                        Infolists\Components\TextEntry::make('ultra_low_temperature_original_vitality_data')
                            ->label('原始活力数据'),
                        Infolists\Components\TextEntry::make('ultra_low_temperature_recover_cultivation_medium')
                            ->label('恢复培养基'),
                        Infolists\Components\TextEntry::make('ultra_low_temperature_recovery_process')
                            ->label('复苏程序'),
                    ])
                    ->columnSpanFull()
                    ->visible(fn(Model $record) => $record->preserve_type == PreserveType::UltraLowTemperature),
                
                // 原生境保存
                Schemas\Components\Grid::make([
                        'default' => 1,
                        'lg' => 2,
                        'xl' => 3,
                    ])
                    ->extraAttributes([
                        'class' => 'sn-grid-table',
                    ])
                    ->schema([
                        Infolists\Components\TextEntry::make('original_habitat_variant_type')
                            ->label('变种类型'),
                        Infolists\Components\TextEntry::make('original_habitat_address')
                            ->label('详细地点'),
                        Infolists\Components\TextEntry::make('original_habitat_longitude')
                            ->label('经度'),
                        Infolists\Components\TextEntry::make('original_habitat_latitude')
                            ->label('纬度'),
                        Infolists\Components\TextEntry::make('original_habitat_terrain')
                            ->label('地形'),
                        Infolists\Components\TextEntry::make('original_habitat_slope')
                            ->label('坡度'),
                        Infolists\Components\TextEntry::make('original_habitat_illuminate')
                            ->label('光照'),
                        Infolists\Components\TextEntry::make('original_habitat_moisture')
                            ->label('水分'),
                        Infolists\Components\TextEntry::make('original_habitat_nursery_habitat_type')
                            ->label('生境类型'),
                        Infolists\Components\TextEntry::make('original_habitat_associated_plants')
                            ->label('伴生植物'),
                        Infolists\Components\TextEntry::make('original_habitat_population_num')
                            ->label('种群数量'),
                        Infolists\Components\TextEntry::make('original_habitat_breeding_situation')
                            ->label('繁殖情况'),
                        Infolists\Components\TextEntry::make('original_habitat_phenological_record')
                            ->label('物候记录'),
                    ])
                    ->columnSpanFull()
                    ->visible(fn(Model $record) => $record->preserve_type == PreserveType::OriginalHabitat),


                Schemas\Components\Text::make(Common::title('种质信息')),
                Schemas\Components\Grid::make([
                        'default' => 1,
                        'lg' => 2,
                        'xl' => 3,
                    ])
                    ->extraAttributes([
                        'class' => 'sn-grid-table',
                    ])
                    ->schema([
                        Infolists\Components\SpatieMediaLibraryImageEntry::make('appraise.firstMedia')
                            ->label('种质封面图')
                            ->collection('cover')
                            ->extraAttributes([
                                'class' => 'sn-two-rows'
                            ]),
                        Infolists\Components\TextEntry::make('appraise.resource_no')
                            ->label('全国统一编号'),
                        Infolists\Components\TextEntry::make('appraise.name')
                            ->label('种质中文名'),
                        Infolists\Components\TextEntry::make('appraise.en_name')
                            ->label('种质外文名'),
                        Infolists\Components\TextEntry::make('appraise.country_name')
                            ->label('种质原产国'),
                        Infolists\Components\TextEntry::make('appraise.district_name')
                            ->label('种质原产地区')
                            ->state(function (Model $record) {
                                return $record->appraise?->province_name . ' / ' . $record->appraise?->city_name;
                            })
                            ->visible(fn(Model $record) => $record->appraise?->country_code == 'CN'),
                        Infolists\Components\TextEntry::make('appraise.address')
                            ->label('种质原产地址'),
                        Infolists\Components\TextEntry::make('appraise.subject_name')
                            ->label('科名'),
                        Infolists\Components\TextEntry::make('appraise.genus_name')
                            ->label('属名'),
                        Infolists\Components\TextEntry::make('appraise.species_name')
                            ->label('学名'),
                    ])->columnSpanFull(),
            ]);
    }
}
