<?php

namespace App\Filament\Resources\AlizharbActivityLogs;

use App\Filament\Resources\AlizharbActivityLogs\Pages;
use App\Filament\Resources\AlizharbActivityLogs\Tables\AlizharbActivityLogTable;
use Filament\Tables\Table;
use AlizHarb\ActivityLog\Resources\ActivityLogs\ActivityLogResource as BaseActivitylogResource;
use Spatie\Activitylog\ActivitylogServiceProvider;

class AlizharbActivityLogResource extends BaseActivitylogResource
{
    protected static ?string $slug = 'alizharb-activity-logs';

    public static function getModel(): string
    {
        return ActivitylogServiceProvider::determineActivityModel();
    }

    /**
     * Define the table schema.
     */
    public static function table(Table $table): Table
    {
        return AlizharbActivityLogTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAlizharbActivityLogs::route('/'),
            'view' => Pages\ViewAlizharbActivityLog::route('/{record}'),
        ];
    }
}