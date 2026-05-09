<?php

namespace App\Models;

use App\Enums\Shares\Status;
use App\Models\Team;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Support\LogOptions;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Wsmallnews\Support\Models\SupportModel;

class Share extends SupportModel
{
    use LogsActivity;
    use SoftDeletes;

    protected $table = 'appraises';     // @sn todo

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
        return '共享';
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

    // public function appraise(): BelongsTo
    // {
    //     return $this->belongsTo(Appraise::class);
    // }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}
