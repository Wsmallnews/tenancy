<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('general.wechat', '');
        $this->migrator->add('general.phone', '');
        $this->migrator->add('general.email', '');
        $this->migrator->add('general.address', '');

        $this->migrator->add('general.wechat_qrcode', '');
        $this->migrator->add('general.wechat_official_qrcode', '');

        $this->migrator->add('general.copyright', '');
        $this->migrator->add('general.copytime', '');
        $this->migrator->add('general.beian_no', '');
        $this->migrator->add('general.beian_url', '');
    }
};
