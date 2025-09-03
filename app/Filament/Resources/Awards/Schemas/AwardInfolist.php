<?php

namespace App\Filament\Resources\Awards\Schemas;

use App\Features\Common;
use Filament\Infolists;
use Filament\Schemas;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;

class AwardInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Schemas\Components\Flex::make([
                    Schemas\Components\Group::make()
                        ->schema([
                            Schemas\Components\Section::make('基础信息')
                                ->schema([
                                    Infolists\Components\TextEntry::make('awardType.name')
                                        ->label('奖项类型'),
                                    Infolists\Components\TextEntry::make('name')
                                        ->label('奖项名称'),
                                    Infolists\Components\TextEntry::make('award_agency')
                                        ->label('授奖机构'),
                                    Infolists\Components\TextEntry::make('level')
                                        ->label('奖项级别'),
                                    Infolists\Components\TextEntry::make('award_name')
                                        ->label('获奖人/团队'),
                                    Infolists\Components\TextEntry::make('remark')
                                        ->label('备注')
                                        ->columnSpanFull(),
                                ])
                                ->columns(2),
                            Schemas\Components\Section::make('证书管理')
                                ->schema(function (Model $record) {
                                    return Common::mediasEntry($record, 'certs');
                                })
                        ])
                        ->columns(1),
                    Schemas\Components\Section::make('状态信息')
                        ->schema([
                            Infolists\Components\TextEntry::make('award_at')
                                ->label('获奖日期')
                                ->date('Y-m-d'),
                                
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
                        ])
                        ->extraAttributes(['class' => 'min-w-60'])
                        ->grow(false)
                ])
                ->columnSpanFull()
                ->from('lg'),
            ]);
    }
}
