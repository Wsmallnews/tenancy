<?php

namespace App\Enums\Assembles;

use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasColor;
use App\Enums\Traits\EnumHelper;

enum Status: string implements HasLabel, HasIcon, HasColor
{

    use EnumHelper;

    case Normal = 'normal';

    case Hidden = 'hidden';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Normal => '正常',
            self::Hidden => '隐藏',
        };
    }


    public function getColor(): string | array | null
    {
        return match ($this) {
            self::Normal => 'success',
            self::Hidden => 'gary',
        };
    }


    public function getIcon(): ?string
    {
        return match ($this) {
            self::Normal => 'heroicon-m-eye',
            self::Hidden => 'heroicon-m-eye-slash',
        };
    }
}
