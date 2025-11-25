<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class AppraiseSettings extends Settings
{
    public array $germplasm_type = ['野生资源', '地方品种', '选育品种', '品系', '遗传材料', '其他'];

    public array $germplasm_use = ['果实', '植株', '两者'];

    public array $fruit_use = ['鲜食', '加工', '两者或多种用途'];

    public array $plant_use = ['无性系砧木', '中间砧', '实生砧', '观赏', '多种用途'];

    public array $assemble_resource = ['野生', '农田', '庭院', '市场', '资源圃', '研究机构', '生产单位'];

    public array $assemble_material_type = ['枝条', '叶片', '花粉', '果实（种子）', '苗木'];

    // 基因型鉴定方法(精准鉴定)
    public array $gene_identify_method = ['全基因组重测序', '基因芯片', 'SSR标记', '其他'];

    public static function group(): string
    {
        return 'appraise';
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
