<?php

namespace App\Filament\Platform\Resources\Teams\Schemas;

use Filament\Infolists;
use Filament\Schemas;
use Filament\Schemas\Schema;

class TeamInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Schemas\Components\Section::make('基础信息')
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
                                Infolists\Components\TextEntry::make('name')
                                    ->label('租户名称'),
                                Infolists\Components\ImageEntry::make('avatar_url')
                                    ->label('头像')
                                    ->extraAttributes([
                                        'class' => 'sn-two-rows',
                                    ]),
                                Infolists\Components\TextEntry::make('slug')
                                    ->label('标识'),
                                Infolists\Components\TextEntry::make('status')
                                    ->label('状态'),
                                Infolists\Components\TextEntry::make('created_at')
                                    ->label('创建时间')
                                    ->dateTime(),
                                Infolists\Components\TextEntry::make('updated_at')
                                    ->label('更新时间')
                                    ->dateTime(),
                            ]),
                    ])->columnSpanFull(),
            ]);
    }
}
