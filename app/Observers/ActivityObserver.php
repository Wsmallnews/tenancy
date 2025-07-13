<?php

namespace App\Observers;

use App\Models\Activity;
use Filament\Facades\Filament;

class ActivityObserver
{
    public function creating(Activity $activity): void
    {
        if (has_tenancy()) {
            $activity->team_id = current_tenant()->id;
        } else {
            $activity->team_id = Filament::getTenant()->id;
        }
    }
}