<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class ProjectSettings extends Settings
{
    public array $project_type = ['国家级', '省级', '校内', '横向', '种质引进', '内部试验'];

    public array $project_subject = ['果树遗传', '资源收集', '分子育种', '栽培研究'];

    public array $project_level = ['国家重点', '一般项目', '自筹'];

    public static function group(): string
    {
        return 'project';
    }


    public static function repository(): ?string
    {
        return 'team_database';
    }

    public static function cacheKey(): string
    {
        return static::class . '_team_' . general_current_tenant()?->id;
    }
}
