<?php

namespace App\Filament\Resources\ActivityLogs;

use App\Models\Activity;
use App\Filament\Resources\ActivityLogs\Pages;
use App\Filament\Resources\ActivityLogs\Tables\ActivityLogTable;
use Filament\Tables\Table;
use AlizHarb\ActivityLog\Resources\ActivityLogs\ActivityLogResource as BaseActivitylogResource;

class ActivitylogResource extends BaseActivitylogResource
{
    protected static ?string $slug = 'activity-logs';

    public static function getModel(): string
    {
        return Activity::class;
    }

    /**
     * Define the table schema.
     */
    public static function table(Table $table): Table
    {
        return ActivityLogTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListActivityLogs::route('/'),
            'view' => Pages\ViewActivityLog::route('/{record}'),
        ];
    }
}
