<?php

namespace App\Filament\Resources\Catalogs\Schemas;

use App\Features\Common;
use Filament\Infolists;
use Filament\Schemas;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;

class CatalogInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Schemas\Components\Text::make(Common::title('编目信息')),
                Schemas\Components\Grid::make([
                    'default' => 1,
                    'lg' => 2,
                    'xl' => 3,
                ])
                    ->extraAttributes([
                        'class' => 'sn-grid-table',
                    ])
                    ->schema([
                        Infolists\Components\TextEntry::make('name')
                            ->label('作物名称'),
                        Infolists\Components\TextEntry::make('code_type')
                            ->label('编码类别'),
                        Infolists\Components\TextEntry::make('assemble_no')
                            ->label('收集编号'),
                        Infolists\Components\TextEntry::make('original_no')
                            ->label('原始编号'),
                        Infolists\Components\TextEntry::make('assemble_at')
                            ->label('收集日期')
                            ->date('Y-m-d'),
                        Infolists\Components\TextEntry::make('resource_method')
                            ->label('资源来源方式'),
                        Infolists\Components\TextEntry::make('catalog_at')
                            ->label('编目时间')
                            ->date('Y-m-d'),
                        Infolists\Components\TextEntry::make('country_name')
                            ->label('原产国'),
                        Infolists\Components\TextEntry::make('district_name')
                            ->label('原产地区')
                            ->state(fn (Model $record) => "{$record->province_name} / {$record->city_name}")
                            ->visible(fn (Model $record) => $record->country_code == 'CN'),
                        Infolists\Components\TextEntry::make('address')
                            ->label('原产地'),
                        Infolists\Components\TextEntry::make('source_country_name')
                            ->label('来源国'),
                        Infolists\Components\TextEntry::make('source_district_name')
                            ->label('来源地区')
                            ->state(fn (Model $record) => "{$record->source_province_name} / {$record->source_city_name}")
                            ->visible(fn (Model $record) => $record->source_country_code == 'CN'),
                        Infolists\Components\TextEntry::make('source_address')
                            ->label('来源地址'),
                        Infolists\Components\TextEntry::make('lng_lat')
                            ->label('经纬度')
                            ->state(fn (Model $record) => "{$record->longitude}, {$record->latitude}"),
                        Infolists\Components\TextEntry::make('altitude')
                            ->label('海拔')
                            ->suffix('米'),
                    ])->columnSpanFull(),
                Schemas\Components\Text::make(Common::title('收集信息')),
                Schemas\Components\Grid::make([
                    'default' => 1,
                    'lg' => 2,
                    'xl' => 3,
                ])
                    ->extraAttributes([
                        'class' => 'sn-grid-table',
                    ])
                    ->schema([
                        Infolists\Components\TextEntry::make('assemble_address')
                            ->label('收集地址'),
                        Infolists\Components\TextEntry::make('assembleCompany.name')
                            ->label('收集单位')
                            ->formatStateUsing(fn (Model $record, $state) => $record->assembleCompany ? "{$record->assembleCompany->name} (编号：{$record->assembleCompany->code})" : '-'),
                        Infolists\Components\TextEntry::make('assember')
                            ->label('收集者'),
                        Infolists\Components\TextEntry::make('assember_phone')
                            ->label('收集者手机号'),
                        Infolists\Components\TextEntry::make('provider')
                            ->label('提供者'),
                        Infolists\Components\TextEntry::make('provider_phone')
                            ->label('提供者手机号'),

                        Infolists\Components\TextEntry::make('tempSaveCompany.name')
                            ->label('临时保存单位')
                            ->formatStateUsing(fn (Model $record, $state) => $record->tempSaveCompany ? "{$record->tempSaveCompany->name} (编号：{$record->tempSaveCompany->code})" : '-'),
                        Infolists\Components\TextEntry::make('originalSaveCompany.name')
                            ->label('原保存单位')
                            ->formatStateUsing(fn (Model $record, $state) => $record->originalSaveCompany ? "{$record->originalSaveCompany->name} (编号：{$record->originalSaveCompany->code})" : '-'),
                        Infolists\Components\TextEntry::make('inspect_assemble_project')
                            ->label('考察收集项目'),
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
                            ->label('状态'),
                    ])->columnSpanFull(),
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
                                'class' => 'sn-two-rows',
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
                                return $record->appraise?->province_name.' / '.$record->appraise?->city_name;
                            })
                            ->visible(fn (Model $record) => $record->appraise?->country_code == 'CN'),
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
