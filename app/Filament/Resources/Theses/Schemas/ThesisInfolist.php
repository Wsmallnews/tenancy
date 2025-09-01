<?php

namespace App\Filament\Resources\Theses\Schemas;

use App\Enums\Theses\Status;
use Filament\Infolists;
use Filament\Schemas;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;
use Filament\Support\Enums\IconPosition;

class ThesisInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Schemas\Components\Flex::make([
                    Schemas\Components\Group::make()->schema([
                        Schemas\Components\Section::make('基础信息')
                            ->schema([
                                Infolists\Components\TextEntry::make('thesisType.name')
                                    ->label('论文类型'),
                                
                                Infolists\Components\TextEntry::make('title')
                                    ->label('标题'),
                                    
                                Infolists\Components\TextEntry::make('author_name')
                                    ->label('作者'),
                                    
                                Infolists\Components\TextEntry::make('company.name')
                                    ->label('所属单位')
                                    ->formatStateUsing(fn (Model $record, $state) => $record->company ? "{$record->company->name} (编号：{$record->company->code})" : '-'),
                                Infolists\Components\TextEntry::make('description')
                                    ->label('摘要')
                                    ->columnSpanFull(),
                                    
                                Infolists\Components\TextEntry::make('remark')
                                    ->label('备注')
                                    ->columnSpanFull(),
                            ])
                            ->columns(2),
                            
                        Schemas\Components\Section::make('附件管理')
                            ->schema([
                                Infolists\Components\SpatieMediaLibraryImageEntry::make('theses')
                                    ->collection('theses')
                            ]),
                    ])->columns(1),
                    Schemas\Components\Section::make('状态信息')
                        ->schema([
                            Infolists\Components\TextEntry::make('journal')
                                ->label('发布期刊'),
                                
                            Infolists\Components\TextEntry::make('issue_number')
                                ->label('卷期号'),
                                
                            Infolists\Components\TextEntry::make('published_at')
                                ->label('出版日期')
                                ->date('Y-m-d'),
                                
                            Infolists\Components\SpatieTagsEntry::make('tags')
                                ->label('关键字'),
                                
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
                                ->label('状态')
                                ->iconPosition(IconPosition::After),
                        ])
                            
                ])
                ->columnSpanFull()
                ->from('lg'),
            ]);
    }
}
