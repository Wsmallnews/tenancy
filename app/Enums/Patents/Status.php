<?php

namespace App\Enums\Patents;

use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasColor;
use App\Enums\Traits\EnumHelper;

enum Status: string implements HasLabel, HasIcon, HasColor
{

    use EnumHelper;

    case Ing = 'ing';

    case Authd = 'authd';

    case Expired = 'expired';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Ing => '申请中',
            self::Authd => '已授权',
            self::Expired => '已过期',
        };
    }


    public function getColor(): string | array | null
    {
        return match ($this) {
            self::Ing => 'gary',
            self::Authd => 'success',
            self::Expired => 'danger',
        };
    }


    public function getIcon(): ?string
    {
        return match ($this) {
            self::Ing => 'heroicon-m-eye',
            self::Authd => 'heroicon-m-eye-slash',
            self::Expired => 'heroicon-m-eye-slash',
        };
    }
}
