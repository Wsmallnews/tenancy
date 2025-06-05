<?php

namespace App\Enums\Teams;

use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasColor;
use App\Enums\Traits\EnumHelper;

enum Status: string implements HasLabel, HasIcon, HasColor
{

    use EnumHelper;

    case Enable = 'enable';

    case Disabled = 'disabled';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Enable => '启用',
            self::Disabled => '已禁用',
        };
    }


    public function getColor(): string | array | null
    {
        return match ($this) {
            self::Enable => 'success',
            self::Disabled => 'gary',
        };
    }


    public function getIcon(): ?string
    {
        return match ($this) {
            self::Enable => 'heroicon-s-check-circle',
            self::Disabled => 'heroicon-s-x-circle',
        };
    }
}
