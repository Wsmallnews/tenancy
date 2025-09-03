<?php

namespace App\Features;

use Filament\Actions;
use Filament\Infolists;
use Filament\Schemas;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;

class Common
{
    public static function mediasEntry($record, $collection = 'default')
    {
        $schemas = [];

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
            $schemas[] = Schemas\Components\Fieldset::make()
                ->label('文件')
                ->schema(function () use ($files) {
                    return $files->map(function ($media) {
                        return Infolists\Components\TextEntry::make('fileentry-' . $media->fila_name)
                            ->hiddenLabel()
                            ->state($media)
                            ->formatStateUsing(fn ($state) => $state->name . '.' . $state->extension)
                            ->beforeContent(function ($state) {
                                return Actions\Action::make('download-' . $state->file_name)
                                    ->icon(Heroicon::ArrowDownTray)
                                    ->iconButton()
                                    ->action(function ($state) {
                                        return response()->download($state->getPath(), $state->name . '.' . $state->extension);
                                    }
                                );
                            });
                    })->all();
                });
        }

        if ($images->isNotEmpty()) {
            // 图片
            $schemas[] = Schemas\Components\Fieldset::make()
                ->label('图片')
                ->schema([
                    Infolists\Components\SpatieMediaLibraryImageEntry::make($collection)
                        ->hiddenLabel()
                        ->collection($collection)
                        ->filterMediaUsing(
                            fn ($media): MediaCollection => $media->filter(function ($item) {
                                return Str::startsWith($item->mime_type, 'image/');
                            })
                        )
                ])->columns(1);
        }

        return $schemas;
    }
}
