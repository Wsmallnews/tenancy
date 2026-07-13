<?php

namespace App\Filament\Resources\Patents\Schemas;

use App\Features\Common;
use Filament\Infolists;
use Filament\Schemas;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;

class PatentInfolist
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
                                Infolists\Components\TextEntry::make('patentType.name')
                                    ->label('专利类型'),
                                Infolists\Components\TextEntry::make('name')
                                    ->label('专利名称'),
                                Infolists\Components\TextEntry::make('patent_apply_no')
                                    ->label('专利申请号'),
                                Infolists\Components\TextEntry::make('patent_no')
                                    ->label('专利号'),
                                Infolists\Components\TextEntry::make('author_name')
                                    ->label('发明人/作者'),
                                Infolists\Components\TextEntry::make('applied_at')
                                    ->label('申请日期')
                                    ->date('Y-m-d'),
                                Infolists\Components\TextEntry::make('authd_at')
                                    ->label('授权日期')
                                    ->date('Y-m-d'),
                                Infolists\Components\TextEntry::make('description')
                                    ->label('摘要'),
                                Infolists\Components\TextEntry::make('remark')
                                    ->label('备注'),
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
                            ]),
                    ])->columnSpanFull(),
                Schemas\Components\Section::make('附件')
                    ->schema(function (Model $record) {
                        return Common::mediasEntry($record, 'patents');
                    })
                    ->extraAttributes([
                        'class' => 'sn-attachment-group',
                    ])
                    ->columns(['default' => 1,  'xl' => 2])->columnSpanFull(),
            ]);
    }
}
