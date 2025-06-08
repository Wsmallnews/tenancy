<?php

namespace App\Filament\Resources\ActivitylogResource\Pages;

use App\Filament\Resources\ActivitylogResource;
use Rmsramos\Activitylog\Resources\ActivitylogResource\Pages\ListActivitylog as BaseListActivitylog;

class ListActivitylog extends BaseListActivitylog
{
    protected static string $resource = ActivitylogResource::class;
}
