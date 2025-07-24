<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('appraise.germplasm_type', ['野生资源', '地方品种', '选育品种', '品系', '遗传材料', '其他']);
        $this->migrator->add('appraise.germplasm_use', ['果实', '植株', '两者']);
        $this->migrator->add('appraise.fruit_use', ['鲜食', '加工', '两者或多种用途']);
        $this->migrator->add('appraise.plant_use', ['无性系砧木', '中间砧', '实生砧', '观赏', '多种用途']);
        $this->migrator->add('appraise.assemble_resource', ['野生', '农田', '庭院', '市场', '资源圃', '研究机构', '生产单位']);
        $this->migrator->add('appraise.assemble_material_type', ['枝条', '叶片', '花粉', '果实（种子）', '苗木']);
    }
};