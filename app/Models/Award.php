<?php

namespace App\Models;

use App\Enums\Awards\Status;
use App\Models\Team;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Tags\HasTags;
use Wsmallnews\Support\Models\SupportModel;

class Award extends SupportModel implements HasMedia
{
    use HasTags;
    use InteractsWithMedia;
    use LogsActivity;
    use SoftDeletes;

    protected $table = 'awards';

    protected $casts = [
        'status' => Status::class,
    ];

    /**
     * 默认模型名称
     *
     * @return string
     */
    public static function getModelLabel(): string
    {
        return '奖项';
    }

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

    public function AwardType(): BelongsTo
    {
        return $this->belongsTo(AwardType::class);
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}
