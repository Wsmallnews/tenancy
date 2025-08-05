<?php

namespace App\Providers\Filament;

use App\Http\Middleware\ApplyTenantScopes;
use BezhanSalleh\FilamentShield\Middleware\SyncShieldTenant;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use BezhanSalleh\FilamentShield\Support\Utils;
use App\Models\Team;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\MaxWidth;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Rmsramos\Activitylog\ActivitylogPlugin;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                // 'primary' => Color::Blue,
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
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
            ->plugins([
                FilamentShieldPlugin::make(),
                ActivitylogPlugin::make()
                    ->resource(\App\Filament\Resources\ActivityLogResource::class)
                    ->label('操作日志')
                    ->pluralLabel('操作日志')
                    ->navigationGroup(function () {
                        return Utils::isResourceNavigationGroupEnabled()
                            ? __('filament-shield::filament-shield.nav.group')
                            : '';
                    })
                    ->navigationSort(3),
            ])
            ->navigationGroups([
                '内容管理',
                '资源库管理',
                '种质资源库',
                '种质目录',
                '研究成果',
                '系统设置',
                __('filament-shield::filament-shield.nav.group'),       // 权限管理
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->maxContentWidth(MaxWidth::Full)
            ->sidebarWidth('16rem')             // 侧边栏的宽度
            ->sidebarCollapsibleOnDesktop()
            ->collapsedSidebarWidth('8rem')     // 折叠侧边栏时的宽度（这个没效果啊）
            ->databaseTransactions()
            ->tenant(Team::class, slugAttribute: 'slug')
            // ->tenantDomain('{tenant:slug}.tenancy.test')
            ->tenantRoutePrefix('tenant')
            // ->tenantMenu(false)         // 隐藏左侧 navigation 顶部的 租户菜单
            ->tenantMenuItems([
                'profile' => MenuItem::make()->label('Edit 团队 profile')->url(fn(): string => 'https://www.taobao.com'),
                MenuItem::make()
                    ->label('Settings')
                    ->url(fn(): string => 'https://www.baidu.com')
                    ->icon('heroicon-m-cog-8-tooth'),
                // ...
            ])
            ->tenantMiddleware([
                SyncShieldTenant::class,
                ApplyTenantScopes::class,
            ], isPersistent: true)
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->spa();
    }
}
