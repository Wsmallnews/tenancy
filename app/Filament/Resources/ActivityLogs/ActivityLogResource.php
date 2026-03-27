<?php

namespace App\Filament\Resources\ActivityLogs;

use App\Filament\Resources\ActivityLogs\Pages;
use Filament\Tables\Table;
use Wsmallnews\Support\Filament\Resources\ActivityLogs\BaseResource;

class ActivityLogResource extends BaseResource
{

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListActivityLogs::route('/'),
            'view' => Pages\ViewActivityLog::route('/{record}'),
        ];
    }
}