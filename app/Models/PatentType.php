<?php

namespace App\Models;

use App\Enums\PatentTypes\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PatentType extends Model
{

    protected $table = 'patent_types';

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


    public function patents(): HasMany
    {
        return $this->hasMany(Patent::class);
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}
