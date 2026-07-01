<?php

namespace App\Enums\Teams;

use App\Enums\Traits\EnumHelper;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum Status: string implements HasColor, HasIcon, HasLabel
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

    public function getColor(): string|array|null
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
