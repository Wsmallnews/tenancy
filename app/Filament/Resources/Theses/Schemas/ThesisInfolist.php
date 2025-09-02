<?php

namespace App\Filament\Resources\Theses\Schemas;

use App\Enums\Theses\Status;
use Filament\Infolists;
use Filament\Schemas;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Filament\Support\Enums\IconPosition;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ThesisInfolist
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
                                ->schema(function (Model $record) {
                                    $schemas = [];

                                    $collection = 'theses';
                                    $medias = $record->getRelationValue('media');

                                    $files = $medias
                                        ->filter(fn ($media) => 
                                            $media->getAttributeValue('collection_name') === $collection && Str::doesntStartWith($media->mime_type, 'image/')
                                        )
                                        ->sortBy('order_column');

                                    $images = $medias
                                        ->filter(fn ($media) => 
                                            $media->getAttributeValue('collection_name') === $collection && Str::startsWith($media->mime_type, 'image/')
                                        )
                                        ->sortBy('order_column');

                                    if ($files->isNotEmpty()) {
                                        // 文件列表
                                        $schemas[] = Schemas\Components\UnorderedList::make(function () use ($files) {
                                            return $files->map(function ($media) {
                                                return Schemas\Components\Text::make($media->name);
                                            })->all();
                                        });
                                    }

                                    if ($images->isNotEmpty()) {
                                        // 图片
                                        $schemas[] = Infolists\Components\SpatieMediaLibraryImageEntry::make('theses')
                                            ->label('图片')
                                            ->collection('theses')
                                            ->filterMediaUsing(
                                                fn ($media): MediaCollection => $media->filter(function ($item) {
                                                    return Str::startsWith($item->mime_type, 'image/');
                                                })
                                            );
                                    }

                                    return $schemas;
                                })


                                // ->schema([
                                //     Schemas\Components\UnorderedList::make(function (Model $record) {
                                //         $collection = 'theses';
                                //         $medias = $record->getRelationValue('media')
                                //             ->when(
                                //                 $collection,
                                //                 fn (MediaCollection $mediaCollection) => $mediaCollection->filter(fn (Media $media): bool => $media->getAttributeValue('collection_name') === $collection),
                                //             )
                                //             ->sortBy('order_column');

                                //         $list = $medias->map(function ($media) {
                                //             if (strpos($media->mime_type, 'image/') === 0) {
                                //                 return Schemas\Components\Image::make(
                                //                     url: $media->getUrl(),
                                //                     alt: 'QR code to scan with an authenticator app',
                                //                 );
                                //             } else {
                                //                 return Schemas\Components\Text::make($media->file_name)
                                //                     ->label($media->file_name)
                                //                     ->url($media->getUrl());
                                //             }
                                //         })->all();

                                //         return $list;
                                //     })->extraAttributes([
                                //         // 'class' => 'flex gap-2',
                                //     ]),

                                //     // Infolists\Components\SpatieMediaLibraryImageEntry::make('theses')
                                //     //     ->collection('theses')
                                // ]),
                        ])
                        ->columns(1),
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
                        ->grow(false)
                ])
                ->columnSpanFull()
                ->from('lg'),
            ]);
    }
}
