<?php

namespace App\Models;

use App\Enums\Theses\Status;
use App\Models\Team;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Tags\HasTags;

class Thesis extends Model implements HasMedia
{
    use HasTags;
    use InteractsWithMedia;
    use SoftDeletes;

    protected $table = 'theses';

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

    public function thesisType(): BelongsTo
    {
        return $this->belongsTo(ThesisType::class);
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}
