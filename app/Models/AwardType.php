<?php

namespace App\Models;

use App\Enums\AwardTypes\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AwardType extends Model
{

    protected $table = 'award_types';

    protected $casts = [
        'status' => Status::class,
    ];


    public function scopeNormal($query)
    {
        return $query->where('status', Status::Normal);
    }

    public function scopeHidden($query)
    {
        return $query->where('status', Status::Hidden);
    }


    public function theses(): HasMany
    {
        return $this->hasMany(Thesis::class);
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}
