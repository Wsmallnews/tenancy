<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('project.project_type', ['国家级', '省级', '校内', '横向', '种质引进', '内部试验']);
        $this->migrator->add('project.project_subject', ['果树遗传', '资源收集', '分子育种', '栽培研究']);
        $this->migrator->add('project.project_level', ['国家重点', '一般项目', '自筹']);
    }
};