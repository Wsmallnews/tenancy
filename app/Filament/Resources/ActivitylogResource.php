<?php

namespace App\Filament\Resources;

use App\Models\Activity;
use App\Filament\Resources\ActivitylogResource\Pages\ListActivitylog;
use App\Filament\Resources\ActivitylogResource\Pages\ViewActivitylog;
use Rmsramos\Activitylog\Resources\ActivitylogResource as BaseActivitylogResource;

class ActivitylogResource extends BaseActivitylogResource
{
    protected static ?string $slug = 'activity-logs';

    public static function getModel(): string
    {
        return Activity::class;
    }


    public static function getPages(): array
    {
        return [
            'index' => ListActivitylog::route('/'),
            'view'  => ViewActivitylog::route('/{record}'),
        ];
    }
}
