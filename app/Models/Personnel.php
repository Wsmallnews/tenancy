<?php

namespace App\Models;

use App\Enums\Personnels\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Personnel extends Model implements HasMedia
{
    use InteractsWithMedia;
    use LogsActivity;
    use SoftDeletes;

    protected $table = 'personnels';

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

    public function scopeScopeTenant($query)
    {
        if (has_tenancy()) {
            return $query->where('team_id', current_tenant()->id);
        } else {
            return $query->whereNull('team_id');
        }
    }

    public function content(): MorphOne
    {
        return $this->morphOne(Content::class, 'contentable');
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}
