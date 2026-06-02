<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class OtherSettings extends Settings
{
    public array $qualification = ['大专', '本科', '硕士', '博士', '院士'];

    public static function group(): string
    {
        return 'other';
    }

    public static function repository(): ?string
    {
        return 'team_database';
    }

    public static function cacheKey(): string
    {
        return static::class.'_team_'.current_tenant()?->id;
    }
}
