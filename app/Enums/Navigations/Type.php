<?php

namespace App\Enums\Navigations;

use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasColor;
use App\Enums\Traits\EnumHelper;

enum Type: string implements HasLabel
{

    use EnumHelper;

    case Child = 'child';

    case Route = 'route';

    case Page = 'page';

    case Url = 'url';

    case Content = 'content';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Child => '子导航',
            self::Url => '链接',
            self::Route => '路由',
            self::Page => '页面',
            self::Content => '内容',
        };
    }
}
