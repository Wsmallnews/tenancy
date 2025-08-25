<?php

namespace App\Filament\Resources\ActivitylogResource\Pages;

use App\Filament\Resources\ActivitylogResource;
use Rmsramos\Activitylog\Resources\ActivitylogResource\Pages\ViewActivitylog as BaseViewActivitylog;

class ViewActivitylog extends BaseViewActivitylog
{
    public static function getResource(): string
    {
        return ActivitylogResource::class;
    }
}
