<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class GeneralSettings extends Settings
{
    public ?string $wechat;

    public ?string $phone;

    public ?string $email;

    public ?string $address;

    public ?string $wechat_qrcode;

    public ?string $wechat_official_qrcode;

    public ?string $copyright;

    public ?string $copytime;

    public ?string $beian_no;

    public ?string $beian_url;

    public static function group(): string
    {
        return 'general';
    }
}
