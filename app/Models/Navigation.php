<?php

namespace App\Models;

use App\Enums\Navigations\Status as NavigationStatus;
use App\Enums\Navigations\Type as NavigationTypeEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
// use RalphJSmit\Laravel\SEO\Schema\ArticleSchema;
// use RalphJSmit\Laravel\SEO\SchemaCollection;
// use RalphJSmit\Laravel\SEO\Support\HasSEO;
// use RalphJSmit\Laravel\SEO\Support\SEOData;
use Kalnoy\Nestedset\NodeTrait;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Studio15\FilamentTree\Concerns\InteractsWithTree;

class Navigation extends Model implements HasMedia
{
    use NodeTrait;
    use InteractsWithMedia;
    use InteractsWithTree;
    // use HasSEO;
    use LogsActivity;

    protected $table = 'navigations';

    protected $casts = [
        'type' => NavigationTypeEnum::class,
        'status' => NavigationStatus::class,
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


    public function getScopeAttributes(): array
    {
        return ['team_id'];
    }


    public static function getTreeLabelAttribute(): string
    {
        return 'name';
    }


    public function getRouteKeyName()
    {
        return 'slug';
    }


    // public function getDynamicSEOData(): SEOData
    // {
    //     return new SEOData(
    //         title: $this->name,
    //         description: $this->description,
    //         image: $this->getFirstMediaUrl('banner')
    //     );
    // }


    public function resolveNavigation($navigation)
    {
        $url = null;

        if ($navigation->type == NavigationTypeEnum::Route && isset($navigation->options['route'])) {
            $url = route($navigation->options['route']);
        }

        if ($navigation->type == NavigationTypeEnum::Url && isset($navigation->options['url'])) {
            $url = $navigation->options['url'];
        }

        if ($navigation->type == NavigationTypeEnum::Content) {
            $url = route('navigation', $navigation->slug);
        }

        $navigation->setAttribute('url_info', [
            'url' => $url,
            'target' => isset($navigation->options['target']) && $navigation->options['target'] == '_blank' ? true : false,
        ]);

        return $navigation;
    }


    public function scopeNormal($query)
    {
        return $query->where('status', NavigationStatus::Normal);
    }


    public function scopeHidden($query)
    {
        return $query->where('status', NavigationStatus::Hidden);
    }


    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}
