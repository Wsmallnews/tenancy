<?php

namespace App\Enums\Activities;

use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasColor;
use App\Enums\Traits\EnumHelper;

enum LogEvent: string implements HasLabel, HasIcon, HasColor
{

    use EnumHelper;

    case Created = 'created';

    case Updated = 'updated';

    case Deleted = 'deleted';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Created => '创建',
            self::Updated => '更新',
            self::Deleted => '删除',
        };
    }


    public function getColor(): string | array | null
    {
        return match ($this) {
            self::Created => 'success',
            self::Updated => 'warning',
            self::Deleted => 'danger',
        };
    }


    public function getIcon(): ?string
    {
        return match ($this) {
            self::Created => 'heroicon-m-plus',
            self::Updated => 'heroicon-m-pencil',
            self::Deleted => 'heroicon-m-trash',
        };
    }
}
