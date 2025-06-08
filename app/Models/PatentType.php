<?php

namespace App\Models;

use App\Enums\PatentTypes\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class PatentType extends Model
{
    use LogsActivity;

    protected $table = 'patent_types';

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


    public function patents(): HasMany
    {
        return $this->hasMany(Patent::class);
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}
