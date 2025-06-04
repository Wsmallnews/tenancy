<?php

namespace App\Models;

use App\Enums\Categories\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Kalnoy\Nestedset\NodeTrait;
use Studio15\FilamentTree\Concerns\InteractsWithTree;

class Category extends Model
{
    use NodeTrait;
    use InteractsWithTree;

    protected $table = 'categories';

    protected $casts = [
        // 'status' => Status::class,
        'options' => 'array',
    ];


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
