<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class ProjectSettings extends Settings
{
    public array $project_type;

    public array $project_subject;

    public array $project_level;

    public static function group(): string
    {
        return 'project';
    }
}
