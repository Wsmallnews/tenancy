<?php

namespace App\Observers;

use App\Models\Activity;
use Filament\Facades\Filament;

class ActivityObserver
{
    public function creating(Activity $activity): void
    {
        $activity->team_id = Filament::getTenant()->id;
    }
}