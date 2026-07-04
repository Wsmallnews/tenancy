<?php

namespace App\Filament\Resources\AccurateIdentifies\Schemas;

use App\Features\Common;
use Filament\Infolists;
use Filament\Schemas;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;

class AccurateIdentifyInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Schemas\Components\Text::make(Common::title('鉴定信息')),
                Schemas\Components\Grid::make([
                    'default' => 1,
                    'lg' => 2,
                    'xl' => 3,
                ])
                    ->extraAttributes([
                        'class' => 'sn-grid-table',
                    ])
                    ->schema([
                        Infolists\Components\TextEntry::make('gene_identify_method')
                            ->label('基因型鉴定方法'),
                        Infolists\Components\TextEntry::make('method_params')
                            ->label('方法参数'),
                        Infolists\Components\TextEntry::make('sequencing_platform')
                            ->label('测序平台'),
                        Infolists\Components\TextEntry::make('sequencing_technology')
                            ->label('测序技术'),
                        Infolists\Components\TextEntry::make('f_reads_length')
                            ->label('F端reads读长'),
                        Infolists\Components\TextEntry::make('entity_data_one')
                            ->label('实体数据1 MD5'),
                        Infolists\Components\TextEntry::make('r_reads_length')
                            ->label('R端reads读长'),
                        Infolists\Components\TextEntry::make('entity_data_two')
                            ->label('实体数据2 MD5'),
                        Infolists\Components\TextEntry::make('reference_sequence')
                            ->label('参考序列 MD5'),
                        Infolists\Components\TextEntry::make('sample_no')
                            ->label('样本编号'),
                        Infolists\Components\TextEntry::make('identify_name')
                            ->label('鉴定人'),
                        Infolists\Components\TextEntry::make('identify_at')
                            ->label('鉴定时间'),
                        Infolists\Components\TextEntry::make('identify_conclusion')
                            ->label('鉴定结论'),
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
                Schemas\Components\Text::make(Common::title('种质图集')),
                Schemas\Components\Group::make()
                    ->schema(function (Model $record) {
                        return Common::mediasEntry($record, 'sample_galleries');
                    })
                    ->extraAttributes([
                        'class' => 'sn-attachment-group',
                    ])
                    ->columns(['default' => 1,  'xl' => 2])->columnSpanFull(),
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
