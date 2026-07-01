<?php

namespace App\Enums\Navigations;

use App\Enums\Traits\EnumHelper;
use Filament\Support\Contracts\HasLabel;

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
