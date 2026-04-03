<?php

namespace App\Filament\Platform\Resources\ActivityLogs\Pages;

use App\Filament\Platform\Resources\ActivityLogs\ActivityLogResource;
use Wsmallnews\Support\Filament\Resources\ActivityLogs\Pages\ViewActivityLog as ViewActivityLogRecords;

class ViewActivityLog extends ViewActivityLogRecords
{
    protected static string $resource = ActivityLogResource::class;
}