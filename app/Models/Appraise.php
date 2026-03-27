<?php

namespace App\Models;

use App\Enums\Appraises\Status;
use App\Models\Team;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Tags\HasTags;
use Wsmallnews\Category\Support\Utils as CategoryUtils;
use Wsmallnews\Support\Models\SupportModel;

class Appraise extends SupportModel implements HasMedia
{
    use HasTags;
    use InteractsWithMedia;
    use LogsActivity;
    use SoftDeletes;

    protected $table = 'appraises';

    protected $casts = [
        'options' => 'array',
        'status' => Status::class,
    ];


    /**
     * 默认模型名称
     *
     * @return string
     */
    public static function getModelLabel(): string
    {
        return '种质评价';
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

    public function scopeSearch($query, $search)
    {
        $query->where(function ($query) use ($search) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('en_name', 'like', "%{$search}%")
                ->orWhere('resource_no', 'like', "%{$search}%")
                ->orWhere('germplasm_no', 'like', "%{$search}%")
                ->orWhere('original_no', 'like', "%{$search}%");
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(CategoryUtils::getCategoryModel(), 'category_id')->scopeable('appraise', 0);
    }

    public function saveCompany(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'save_company_id');
    }

    public function breedingCompany(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'breeding_company_id');
    }

    public function preserves(): HasMany
    {
        return $this->hasMany(Preserve::class);
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}
