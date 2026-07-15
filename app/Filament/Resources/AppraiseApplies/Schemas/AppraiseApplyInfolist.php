<?php

namespace App\Filament\Resources\AppraiseApplies\Schemas;

use App\Features\Common;
use Filament\Infolists;
use Filament\Schemas;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;

class AppraiseApplyInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Schemas\Components\Section::make(Common::sectionTitle('申请信息'))
                    ->schema([
                        Schemas\Components\Grid::make([
                            'default' => 1,
                            'lg' => 2,
                            'xl' => 3,
                        ])
                            ->extraAttributes([
                                'class' => 'sn-grid-table',
                            ])
                            ->schema([
                                Infolists\Components\TextEntry::make('user.name')
                                    ->label('申请用户'),
                                Infolists\Components\TextEntry::make('name')
                                    ->label('申请人'),
                                Infolists\Components\TextEntry::make('phone')
                                    ->label('联系方式'),
                                Infolists\Components\TextEntry::make('company_name')
                                    ->label('用种单位'),
                                Infolists\Components\TextEntry::make('created_at')
                                    ->dateTime()
                                    ->label('申请时间'),
                                Infolists\Components\TextEntry::make('updated_at')
                                    ->dateTime()
                                    ->label('更新时间'),
                                Infolists\Components\TextEntry::make('status')
                                    ->label('状态'),
                            ]),
                    ])->columnSpanFull(),
                Schemas\Components\Section::make(Common::sectionTitle('申请资料'))
                    ->schema(function (Model $record) {
                        return Common::mediasEntry($record, 'apply_file');
                    })
                    ->extraAttributes([
                        'class' => 'sn-attachment-group',
                    ])
                    ->columns(['default' => 1,  'xl' => 2])->columnSpanFull(),
                Schemas\Components\Section::make(Common::sectionTitle('种质信息'))
                    ->schema([
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
                            ]),
                    ])->columnSpanFull(),
            ]);
    }
}
