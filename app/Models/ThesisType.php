<?php

namespace App\Models;

use App\Enums\ThesisTypes\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ThesisType extends Model
{

    protected $table = 'thesis_types';

    protected $casts = [
        'status' => Status::class,
    ];

    public function theses(): HasMany
    {
        return $this->hasMany(Thesis::class);
    } 

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}
