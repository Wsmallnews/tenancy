<?php

namespace App\Filament\Resources;

use App\Models\Activity;
use App\Filament\Resources\ActivitylogResource\Pages\ListActivitylog;
use App\Filament\Resources\ActivitylogResource\Pages\ViewActivitylog;
use Filament\Tables\Table;
use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\TextColumn;
use Rmsramos\Activitylog\Resources\ActivitylogResource as BaseActivitylogResource;

class ActivitylogResource extends BaseActivitylogResource
{
    protected static ?string $slug = 'activity-logs';

    public static function getModel(): string
    {
        return Activity::class;
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                static::getDescriptionColumnComponent(),
                static::getLogNameColumnComponent(),
                static::getEventColumnComponent(),
                static::getSubjectTypeColumnComponent(),
                static::getCauserNameColumnComponent(),
                static::getPropertiesColumnComponent(),
                static::getCreatedAtColumnComponent(),
            ])
            ->defaultSort(config('filament-activitylog.resources.default_sort_column', 'created_at'), config('filament-activitylog.resources.default_sort_direction', 'asc'))
            ->filters([
                static::getDateFilterComponent(),
                static::getEventFilterComponent(),
            ]);
    }


    public static function getDescriptionColumnComponent(): Column
    {
        return TextColumn::make('description')
            ->label(__('activitylog::tables.columns.description.label'))
            ->searchable()
            ->sortable();
    }


    public static function getPages(): array
    {
        return [
            'index' => ListActivitylog::route('/'),
            'view'  => ViewActivitylog::route('/{record}'),
        ];
    }
}
