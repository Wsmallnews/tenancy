<?php

namespace App\Enums\AppraiseApplies;

use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasColor;
use App\Enums\Traits\EnumHelper;

enum Status: string implements HasLabel, HasIcon, HasColor
{

    use EnumHelper;

    case Applying = 'applying';

    case Agree = 'agree';

    case Refuse = 'refuse';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Applying => '申请中',
            self::Agree => '同意',
            self::Refuse => '拒绝',
        };
    }


    public function getColor(): string | array | null
    {
        return match ($this) {
            self::Applying => 'info',
            self::Agree => 'success',
            self::Refuse => 'danger',
        };
    }


    public function getIcon(): ?string
    {
        return match ($this) {
            self::Applying => 'heroicon-m-clipboard-list',
            self::Agree => 'heroicon-m-check',
            self::Refuse => 'heroicon-m-x',
        };
    }
}
