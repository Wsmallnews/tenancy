<?php

namespace App\Models;

use App\Enums\Posts\Status;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Tags\HasTags;

class Post extends Model implements HasMedia
{
    use HasTags;
    use InteractsWithMedia;
    use SoftDeletes;
    use LogsActivity;

    protected $table = 'posts';

    protected $casts = [
        // 'category_ids' => 'array',
        'status' => Status::class,
        'options' => 'array',
    ];


    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontLogIfAttributesChangedOnly(['order_column', 'updated_at'])        // 如果只更新排序，则忽略不记录日志
            ->setDescriptionForEvent(fn(string $eventName) => "This model has been {$eventName}");
    }


    public function scopeScopeTenant($query)
    {
        if (has_tenancy()) {
            return $query->where('team_id', current_tenant()->id);
        } else {
            return $query->whereNull('team_id');
        }
    }


    /**
     * post 需要分类时候解开（多对多分类）
     */
    public function scopeWhereCategoryIn($query, array | Collection $categoryIds)
    {
        return $query->whereHas('categories', function ($query) use ($categoryIds) {
            $query->whereIn('categories.id', $categoryIds);
        });
    }


    public function scopeNormal($query)
    {
        return $query->where('status', Status::Normal);
    }


    public function scopeHidden($query)
    {
        return $query->where('status', Status::Hidden);
    }


    public function content(): MorphOne
    {
        return $this->morphOne(Content::class, 'contentable');
    }


    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(PostCategory::class);
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}
