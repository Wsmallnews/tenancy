<?php

namespace App\Filament\Pages\Auth;

use Filament\Actions\Action;
use Filament\Auth\Pages\Login as BaseLogin;
use Illuminate\Contracts\Support\Htmlable;

class PlatformLogin extends BaseLogin
{
    public function getHeading(): string|Htmlable
    {
        return '平台登录';
    }

    protected function getFormActions(): array
    {
        return [
            ...parent::getFormActions(),
            Action::make('sso_login')
                ->label('使用园艺库账号登录')
                ->color('success')
                ->url(route('sso.redirect'))
                ->extraAttributes([
                    'style' => 'width: 100%; justify-content: center;',
                    'onclick' => 'window.location.href = this.href; return false;',
                ]),
        ];
    }
}
