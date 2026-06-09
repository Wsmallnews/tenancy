<?php

namespace App\Models;

use App\Enums\PhenotypeIdentifies\Status;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Wsmallnews\Category\Support\Utils as CategoryUtils;
use Wsmallnews\Support\Models\SupportModel;

class PhenotypeIdentify extends SupportModel implements HasMedia
{
    use InteractsWithMedia;
    use LogsActivity;
    use SoftDeletes;

    protected $table = 'phenotype_identifies';

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
        return '表型鉴定';
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontLogIfAttributesChangedOnly(['order_column', 'updated_at'])
            ->setDescriptionForEvent(fn (string $eventName) => "This model has been {$eventName}");
    }

    public function scopeNormal($query)
    {
        return $query->where('status', Status::Normal);
    }

    public function scopeHidden($query)
    {
        return $query->where('status', Status::Hidden);
    }

    public function appraise(): BelongsTo
    {
        return $this->belongsTo(Appraise::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(CategoryUtils::getCategoryModel());
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}
