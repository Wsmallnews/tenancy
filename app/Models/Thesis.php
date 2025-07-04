<?php

namespace App\Models;

use App\Enums\Theses\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Tags\HasTags;

class Thesis extends Model implements HasMedia
{
    use HasTags;
    use InteractsWithMedia;
    use SoftDeletes;
    use LogsActivity;

    protected $table = 'theses';

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

    public function thesisType(): BelongsTo
    {
        return $this->belongsTo(ThesisType::class);
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}
