<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class AppraiseSettings extends Settings
{
    public array $germplasm_type;

    public array $germplasm_use;

    public array $fruit_use;

    public array $plant_use;

    public array $assemble_resource;

    public array $assemble_material_type;

    public static function group(): string
    {
        return 'appraise';
    }
}
