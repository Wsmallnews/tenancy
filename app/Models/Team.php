<?php

namespace App\Models;

use App\Enums\Teams\Status;
use Filament\Models\Contracts\HasAvatar;
use Filament\Models\Contracts\HasCurrentTenantLabel;
use Filament\Models\Contracts\HasName;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Team extends Model implements HasAvatar, HasName, HasCurrentTenantLabel
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use SoftDeletes;

    protected $casts = [
        'status' => Status::class,
    ];

    public function getFilamentAvatarUrl(): ?string
    {
        return $this->avatar_url ? Storage::url($this->avatar_url) : null;
    }

    public function getFilamentName(): string
    {
        return $this->name . '-' . $this->id;
    }

    public function getCurrentTenantLabel(): string
    {
        return 'current';
    }


    public function theses(): HasMany
    {
        return $this->hasMany(Thesis::class);
    }

    public function thesisTypes(): HasMany
    {
        return $this->hasMany(ThesisType::class);
    }

    public function awards(): HasMany
    {
        return $this->hasMany(Award::class);
    }

    public function awardTypes(): HasMany
    {
        return $this->hasMany(AwardType::class);
    }

    public function patents(): HasMany
    {
        return $this->hasMany(Patent::class);
    }

    public function patentTypes(): HasMany
    {
        return $this->hasMany(PatentType::class);
    }

    public function roles(): HasMany
    {
        return $this->hasMany(Role::class);
    }
}
