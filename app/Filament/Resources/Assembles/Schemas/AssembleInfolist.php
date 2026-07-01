<?php

namespace App\Filament\Resources\Assembles\Schemas;

use App\Features\Common;
use Filament\Infolists;
use Filament\Schemas;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;

class AssembleInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
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
                        Infolists\Components\TextEntry::make('name')
                            ->label('收集人'),
                        Infolists\Components\TextEntry::make('assemble_no')
                            ->label('收集编号'),
                        Infolists\Components\TextEntry::make('assembleCompany.name')
                            ->label('收集单位')
                            ->formatStateUsing(fn (Model $record, $state) => $record->assembleCompany ? "{$record->assembleCompany->name} (编号：{$record->assembleCompany->code})" : '-'),
                        Infolists\Components\TextEntry::make('subject_no')
                            ->label('所属课题编号'),
                        Infolists\Components\TextEntry::make('sub_subject_no')
                            ->label('所属子课题编号'),
                        Infolists\Components\TextEntry::make('country_name')
                            ->label('收集国家'),
                        Infolists\Components\TextEntry::make('district_name')
                            ->label('收集地区')
                            ->state(fn (Model $record) => "{$record->province_name} / {$record->city_name}")
                            ->visible(fn (Model $record) => $record->country_code == 'CN'),
                        Infolists\Components\TextEntry::make('address')
                            ->label('收集地址'),
                        Infolists\Components\TextEntry::make('lng_lat')
                            ->label('经纬度')
                            ->state(fn (Model $record) => "{$record->longitude}, {$record->latitude}"),
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
