<?php

namespace App\Models;

use App\Enums\PostCategories\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Kalnoy\Nestedset\NodeTrait;
use Studio15\FilamentTree\Concerns\InteractsWithTree;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class PostCategory extends Model
{
    use NodeTrait;
    use InteractsWithTree;
    use LogsActivity;

    protected $table = 'post_categories';

    protected $casts = [
        'options' => 'array',
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

    public function getScopeAttributes(): array
    {
        return ['team_id'];
    }


    public static function getTreeLabelAttribute(): string
    {
        return 'name';
    }


    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}
