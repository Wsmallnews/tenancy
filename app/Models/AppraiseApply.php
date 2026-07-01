<?php

namespace App\Models;

use App\Enums\AppraiseApplies\Status;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Wsmallnews\Support\Models\SupportModel;

class AppraiseApply extends SupportModel implements HasMedia
{
    use InteractsWithMedia;
    use LogsActivity;
    use SoftDeletes;

    protected $table = 'appraise_applies';

    protected $casts = [
        'options' => 'array',
        'status' => Status::class,
    ];

    /**
     * 默认模型名称
     */
    public static function getModelLabel(): string
    {
        return '种质申请';
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontLogIfAttributesChangedOnly(['order_column', 'updated_at'])        // 如果只更新排序，则忽略不记录日志
            ->setDescriptionForEvent(fn (string $eventName) => "This model has been {$eventName}");
    }

    public function scopeApplying($query)
    {
        return $query->where('status', Status::Applying);
    }

    public function scopeAgree($query)
    {
        return $query->where('status', Status::Agree);
    }

    public function scopeRefuse($query)
    {
        return $query->where('status', Status::Refuse);
    }

    public function appraise(): BelongsTo
    {
        return $this->belongsTo(Appraise::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}
