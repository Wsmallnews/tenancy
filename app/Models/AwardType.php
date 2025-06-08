<?php

namespace App\Models;

use App\Enums\AwardTypes\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class AwardType extends Model
{
    use LogsActivity;

    protected $table = 'award_types';

    protected $casts = [
        'status' => Status::class,
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontLogIfAttributesChangedOnly(['order_column', 'updated_at'])        // 如果只更新排序，则忽略不记录日志
            ->setDescriptionForEvent(fn(string $eventName) => "This model has been {$eventName}");
    }

    public function scopeNormal($query)
    {
        return $query->where('status', Status::Normal);
    }

    public function scopeHidden($query)
    {
        return $query->where('status', Status::Hidden);
    }


    public function awards(): HasMany
    {
        return $this->hasMany(Award::class);
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}
