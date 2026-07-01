<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Auth\AdminLogin;
use App\Filament\Tables\PostsTable;
use App\Http\Middleware\CheckTenant;
use App\Models\Team;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use BezhanSalleh\FilamentShield\Middleware\SyncShieldTenant;
use Filament\Auth\MultiFactor\App\AppAuthentication;
use Filament\Auth\MultiFactor\Email\EmailAuthentication;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Width;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Wsmallnews\Cms\CmsPlugin;
use Wsmallnews\Cms\Filament\Pages\Category as CategoryPage;
use Wsmallnews\Cms\Filament\Pages\GeneralSetting as GeneralSettingPage;
use Wsmallnews\Cms\Filament\Pages\Navigation\NavigationPage;
use Wsmallnews\Cms\Filament\Resources\Posts\PostResource;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login(AdminLogin::class)
            ->authGuard('admin')
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
                // 'primary' => Color::Blue,
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                // Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                // Widgets\FilamentInfoWidget::class,
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
                CmsPlugin::make()
                    ->forResource(NavigationPage::class)
                    ->navigationGroup('网站管理')
                    ->navigationLabel('导航管理')
                    ->customProperties([
                        'emptyLabel' => '呀，怎么没数据呀！',
                        'level' => 3,
                    ])
                    ->forResource(PostResource::class)
                    ->navigationGroup('网站管理')
                    ->navigationLabel('图文管理')
                    ->customProperties([
                        'table' => fn ($table) => PostsTable::configure($table),
                    ])
                    ->forResource(CategoryPage::class)
                    ->navigationGroup('网站管理')
                    ->navigationParentItem('图文管理')
                    ->navigationLabel('图文分类')
                    ->forResource(GeneralSettingPage::class)
                    ->navigationGroup('网站管理')
                    ->navigationLabel('网站设置'),
            ])
            ->navigationGroups([
                '网站管理',
                '种质资源库(圃)',
                '属性选项',
                '研究成果',
                '设置管理',
                __('filament-shield::filament-shield.nav.group'),       // 权限管理
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->databaseNotifications()
            ->maxContentWidth(Width::Full)
            ->sidebarWidth('16rem')             // 侧边栏的宽度
            ->sidebarCollapsibleOnDesktop()
            ->collapsedSidebarWidth('8rem')     // 折叠侧边栏时的宽度（这个没效果啊）
            ->databaseTransactions()
            ->tenant(Team::class, slugAttribute: 'slug')
            // ->tenantDomain('{tenant:slug}.tenancy.test')
            ->tenantRoutePrefix('tenant')
            // ->tenantMenu(false)         // 隐藏左侧 navigation 顶部的 租户菜单
            // ->tenantMenuItems([
            //     'profile' => MenuItem::make()->label('Edit 团队 profile')->url(fn(): string => 'https://www.taobao.com'),
            //     MenuItem::make()
            //         ->label('Settings')
            //         ->url(fn(): string => 'https://www.baidu.com')
            //         ->icon('heroicon-m-cog-8-tooth'),
            //     // ...
            // ])
            ->tenantMiddleware([
                SyncShieldTenant::class,
                CheckTenant::class,
            ], isPersistent: true)
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->spa()
            ->spaUrlExceptions([
                '/sso/redirect',
                '/sso/callback',
            ]);
    }
}
