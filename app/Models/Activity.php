<?php

namespace App\Models;

use App\Observers\ActivityObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Models\Activity as ModelsActivity;

#[ObservedBy([ActivityObserver::class])]
class Activity extends ModelsActivity
{
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}