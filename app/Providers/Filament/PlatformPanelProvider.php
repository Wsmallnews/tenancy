<?php

namespace App\Providers\Filament;

use App\Filament\Pages\AttributeDashboard;
use App\Filament\Pages\Auth\PlatformLogin;
use App\Filament\Pages\GermplasmDashboard;
use App\Filament\Pages\ResearchDashboard;
use App\Filament\Pages\SystemDashboard;
use App\Filament\Platform\Pages\Backup;
use App\Http\Middleware\PlatformSetPermissionsTeamId;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use BezhanSalleh\FilamentShield\Support\Utils;
use Filament\Facades\Filament;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Enums\Width;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use ShuvroRoy\FilamentSpatieLaravelBackup\FilamentSpatieLaravelBackupPlugin;

class PlatformPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('platform')
            ->path('platform')
            ->login(PlatformLogin::class)
            ->authGuard('platform')
            ->profile()
            // ->multiFactorAuthentication([        // 设置双因素认证
            //         AppAuthentication::make()
            //             ->recoverable()
            //             ->recoveryCodeCount(10),
            //         EmailAuthentication::make()
            //             ->codeExpiryMinutes(2),
            //     ],
            //     isRequired: false
            // )
            ->colors([
                'primary' => '#1A69D0',
            ])
            ->discoverResources(in: app_path('Filament/Platform/Resources'), for: 'App\Filament\Platform\Resources')
            ->discoverPages(in: app_path('Filament/Platform/Pages'), for: 'App\Filament\Platform\Pages')
            ->pages([
                // Dashboard::class,
                GermplasmDashboard::class,
                AttributeDashboard::class,
                ResearchDashboard::class,
                SystemDashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Platform/Widgets'), for: 'App\Filament\Platform\Widgets')
            ->widgets([
                // AccountWidget::class,
                // FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->middleware([
                PlatformSetPermissionsTeamId::class,        // 因为 spatie/laravel-permission 的 model_has_roles and model_has_permissions 的 team_id 不可为 null, 所以给 platform 设置一个超大的 team_id
            ], isPersistent: true)
            ->plugins([
                FilamentShieldPlugin::make(),
                FilamentSpatieLaravelBackupPlugin::make()
                    ->usingPage(Backup::class)
                    ->authorize(fn () => Filament::auth()->user()?->hasRole(Utils::getSuperAdminName()))            // 只有超管可以访问备份，内部权限download-backup 和 delete-backup，因为已经验证必须 超管了， 不再需要额外配置
                    ->usingPolingInterval('10s')                                                                    // 再看看这个是干啥的
                    ->usingQueue('default') // default value is null
                    // ->timeout(120)              // 超时时间 120s
                    ->noTimeout(),               // 不限制超时时间
            ])
            ->navigationGroups([
                '数据看板',
                '资源库管理',
                '设置管理',
                __('filament-shield::filament-shield.nav.group'),       // 权限管理
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->maxContentWidth(Width::Full)
            ->sidebarWidth('16rem')             // 侧边栏的宽度
            ->sidebarCollapsibleOnDesktop()
            ->collapsedSidebarWidth('8rem')     // 折叠侧边栏时的宽度（这个没效果啊）
            ->databaseTransactions()
            ->viteTheme('resources/css/filament/platform/theme.css')
            ->spa()
            ->spaUrlExceptions([
                '/sso/redirect',
                '/sso/callback',
            ]);
    }
}
