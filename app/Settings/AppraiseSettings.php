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

    // 基因型鉴定方法(精准鉴定)
    public array $gene_identify_method;

    public static function group(): string
    {
        return 'appraise';
    }
}
