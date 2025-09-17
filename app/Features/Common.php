<?php

namespace App\Features;

use App\Filament\Infolists\Components\SwiperEntry;
use Filament\Actions;
use Filament\Infolists;
use Filament\Schemas;
use Filament\Support\Enums\IconSize;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;

use function Filament\Support\generate_icon_html;

class Common
{
    public static function mediasEntry($record, $collection = 'default', $label = null)
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

        if ($images->isNotEmpty()) {
            // 图片
            $schemas[] = Schemas\Components\Section::make(($label ? $label . ' 图片' : '图片'))
                ->schema([
                    SwiperEntry::make($collection)
                        ->hiddenLabel()
                        ->collection($collection)
                        ->filterMediaUsing(
                            fn ($media): MediaCollection => $media->filter(function ($item) {
                                return Str::startsWith($item->mime_type, 'image/');
                            })
                        )->columnSpanFull()
                ])
                ->extraAttributes([
                    'class' => 'sn-attachment-image-list',
                ])
                ->columnSpan(1);
        }

        if ($files->isNotEmpty()) {
            $schemas[] = Schemas\Components\Section::make(($label ? $label . ' 文件' : '文件'))
                ->schema(function () use ($files) {
                    return $files->map(function ($media) {
                        return Infolists\Components\TextEntry::make('fileentry-' . $media->fila_name)
                            ->hiddenLabel()
                            ->state($media)
                            ->formatStateUsing(fn ($state) => $state->name . '.' . $state->extension)
                            ->afterContent(function ($state) {
                                return Actions\Action::make('download-' . $state->file_name)
                                    ->icon(Heroicon::ArrowDownTray)
                                    ->iconButton()
                                    ->action(function ($state) {
                                        return response()->download($state->getPath(), $state->name . '.' . $state->extension);
                                    }
                                );
                            })
                            ->columnSpanFull();
                    })->all();
                })
                ->extraAttributes([
                    'class' => 'sn-attachment-file-list',
                ])
                ->columnSpan(fn () => $images->isNotEmpty() ? 1 : 2);
        }

        return $schemas;
    }


    /**
     * sidebar 带下划线
     *
     * @param string $title
     * @return HtmlString
     */
    public static function sidebar($title, $id = null, $icon = null)
    {
        return new HtmlString('<div class="sn-page-sidebar-item w-full flex gap-2 items-center" @click="swithSidebar(\'' . $id . '\')" :class="currentTab == \'' . $id . '\' ? \'sn-active\' : \'\'" >' . ($icon ? generate_icon_html($icon, size: IconSize::Large)->toHtml() : '') . '<a ' . ($id ? 'href="#' . $id . '"' : '') . ' class="inline-block w-full">' . $title . '</a></div>');
    }

    /**
     * 标题 带下划线
     *
     * @param string $title
     * @return HtmlString
     */
    public static function title($title, $id = null, $icon = null)
    {
        return new HtmlString('<span ' . ($id ? 'id="' . $id . '"' : '') . ' class="sn-page-sidebar-content-item scroll-mt-20 relative inline text-lg font-bold text-gray-950 dark:text-white after:absolute after:bg-primary-600 after:w-full after:h-1 after:rounded-md after:left-0 after:-bottom-2">' . $title . '</span>');
    }
}
