<?php

namespace App\Providers;

use App\Models\Permission;
use App\Models\Role;
use BezhanSalleh\FilamentShield\Commands;
use BezhanSalleh\FilamentShield\Facades\FilamentShield;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Enums\TextSize;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Str;
use Illuminate\Support\ServiceProvider;
use Laravel\Socialite\Contracts\Factory as SocialiteFactory;
use Livewire\Livewire;
use Wsmallnews\Cms\Facades\ContentRegistry as ContentRegistryFacade;
use Wsmallnews\Cms\Support\Utils as CmsUtils;
use Wsmallnews\User\Facades\SidebarMenuRegistry as SidebarMenuRegistryFacade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register custom SSO Socialite provider
        $socialite = $this->app->make(SocialiteFactory::class);
        $socialite->extend('sso', function ($app) use ($socialite) {
            $config = $app['config']['services.sso'];
            return $socialite->buildProvider(
                \App\Services\SsoProvider::class,
                $config
            );
        });

        app(\Spatie\Permission\PermissionRegistrar::class)
            ->setPermissionClass(Permission::class)
            ->setRoleClass(Role::class);

        // shield 禁止在正式环境执行命令
        Commands\SetupCommand::prohibit($this->app->isProduction());
        Commands\InstallCommand::prohibit($this->app->isProduction());
        Commands\GenerateCommand::prohibit($this->app->isProduction());
        Commands\PublishCommand::prohibit($this->app->isProduction());

        // 自定义权限节点名字
        FilamentShield::buildPermissionKeyUsing(
            function (string $entity, string $affix, string $subject, string $case, string $separator) {
                if (
                    is_subclass_of($entity, Resource::class)
                    && !Str::endsWith($entity, 'RoleResource')       // role 权限节点共用，不会出现在同一个 panel, 这里排除特异生成，使用原生规则
                ) {
                    $subject = str($subject)
                        ->prepend(Str::studly($entity::getSlug()) . $separator)
                        ->trim()
                        ->toString();
                }

                return FilamentShield::defaultPermissionKeyBuilder(
                    affix: $affix,
                    separator: $separator,
                    subject: $subject,
                    case: $case
                );
            }
        );


        Model::unguard();

        // 开启 SPA 模式
        \Filament\Support\Facades\FilamentView::spa(true);

        // pages
        Livewire::component('sn-index', \App\Livewire\Index::class);

        // components
        Livewire::component('sn-components-personnels', \App\Livewire\Components\Personnels::class);
        Livewire::component('sn-components-personnel', \App\Livewire\Components\Personnel::class);

        Livewire::component('sn-components-appraise-show', \App\Livewire\Components\AppraiseShow::class);
        Livewire::component('sn-components-appraises', \App\Livewire\Components\Appraises::class);
        Livewire::component('sn-components-appraise', \App\Livewire\Components\Appraise::class);

        Livewire::component('sn-components-user-appraise-apply', \App\Livewire\Components\User\AppraiseApply::class);

        // 首页组件
        Livewire::component('sn-components-index-overview', \App\Livewire\Components\Index\Overview::class);
        Livewire::component('sn-components-index-personnels', \App\Livewire\Components\Index\Personnels::class);
        Livewire::component('sn-components-index-posts', \App\Livewire\Components\Index\Posts::class);
        Livewire::component('sn-components-index-scientific-research', \App\Livewire\Components\Index\ScientificResearch::class);


        // 注册模型别名
        Relation::enforceMorphMap([
            'accurate_identify' => \App\Models\AccurateIdentify::class,
            'appraise' => \App\Models\Appraise::class,
            'appraise_apply' => \App\Models\AppraiseApply::class,
            'assemble' => \App\Models\Assemble::class,
            'award' => \App\Models\Award::class,
            'award_type' => \App\Models\AwardType::class,
            'catalog' => \App\Models\Catalog::class,
            'company' => \App\Models\Company::class,
            'new_variety' => \App\Models\NewVariety::class,
            'patent' => \App\Models\Patent::class,
            'patent_type' => \App\Models\PatentType::class,
            'personnel' => \App\Models\Personnel::class,
            'phenotype_identify' => \App\Models\PhenotypeIdentify::class,
            'preserve' => \App\Models\Preserve::class,
            'project_manage' => \App\Models\ProjectManage::class,
            'role' => \App\Models\Role::class,
            'share' => \App\Models\Share::class,
            'team' => \App\Models\Team::class,
            'thesis' => \App\Models\Thesis::class,
            'thesis_type' => \App\Models\ThesisType::class,
            'user' => \App\Models\User::class,
        ]);

        // 注册导航内容
        ContentRegistryFacade::registers(CmsUtils::getScopeType(), [
            [
                'type' => 'index-overview',
                'label' => '统计信息(首页)',
                'forms' => fn($fields) => [],
                'components' => [
                    \App\Livewire\Components\Index\Overview::class => [
                        'scopeType' => CmsUtils::getScopeType(),
                        'scopeId' => CmsUtils::getScopeId(),
                    ],
                ]
            ],
            [
                'type' => 'index-personnels',
                'label' => '人员列表(首页)',
                'forms' => fn($fields) => [],
                'components' => [
                    \App\Livewire\Components\Index\Personnels::class => [
                        'scopeType' => CmsUtils::getScopeType(),
                        'scopeId' => CmsUtils::getScopeId(),
                    ],
                ]
            ],
            [
                'type' => 'index-card-posts',
                'label' => '动态资讯(首页)',
                'forms' => fn($fields) => [],
                'components' => [
                    \App\Livewire\Components\Index\Posts::class => [
                        'scopeType' => CmsUtils::getScopeType(),
                        'scopeId' => CmsUtils::getScopeId(),
                    ],
                ]
            ],
            [
                'type' => 'index-scientific-research',
                'label' => '科学研究(首页)',
                'forms' => fn($fields) => [],
                'components' => [
                    \App\Livewire\Components\Index\ScientificResearch::class => [
                        'scopeType' => CmsUtils::getScopeType(),
                        'scopeId' => CmsUtils::getScopeId(),
                    ],
                ]
            ],
            [
                'type' => 'personnels',
                'label' => '人员列表',
                'forms' => fn($fields) => [],
                'components' => [
                    \App\Livewire\Components\Personnels::class => [
                        'scopeType' => CmsUtils::getScopeType(),
                        'scopeId' => CmsUtils::getScopeId(),
                    ],
                ]
            ],
            [
                'type' => 'personnel-detail',
                'label' => '人员详情',
                'forms' => fn($fields) => [
                    Forms\Components\Select::make('id')->label('选择人员')
                        ->options(\App\Models\Personnel::normal()->limit(30)->pluck('name', 'id'))
                        ->getSearchResultsUsing(fn(string $search): array => \App\Models\Personnel::where('name', 'like', "%{$search}%")->limit(30)->pluck('name', 'id')->toArray())
                        // ->getOptionLabelUsing(fn($value): ?string => \App\Models\Post::find($value)?->title)
                        ->placeholder('请选择人员详情')
                        ->searchable()
                        ->preload()
                        ->required(),
                ],
                'components' => [
                    \App\Livewire\Components\Personnel::class => [
                        'scopeType' => CmsUtils::getScopeType(),
                        'scopeId' => CmsUtils::getScopeId(),
                    ],
                ]
            ],
            [
                'type' => 'appraise-show',
                'label' => '种质资源列表(带分类)',
                'forms' => fn($fields) => [],
                'components' => [
                    \App\Livewire\Components\AppraiseShow::class => [
                        'scopeType' => CmsUtils::getScopeType(),
                        'scopeId' => CmsUtils::getScopeId(),
                        'style' => 'card',
                    ]
                ]
            ],
            [
                'type' => 'appraise-applies-show',
                'label' => '用种申请列表(带分类)',
                'forms' => fn($fields) => [],
                'components' => [
                    \App\Livewire\Components\AppraiseShow::class => [
                        'scopeType' => CmsUtils::getScopeType(),
                        'scopeId' => CmsUtils::getScopeId(),
                        'style' => 'list',
                    ]
                ]
            ],
        ]);

        // 注册用户侧边栏菜单
        $pluginId = app(\Wsmallnews\Cms\CmsPlugin::class)->getId();
        SidebarMenuRegistryFacade::register($pluginId, fn() => [
            'key' => 'appraise-applies',
            'label' => '种质申请',
            'url' => \Wsmallnews\Cms\Support\Utils::route('user.appraise-applies'),
            'icon' => Heroicon::OutlinedUserGroup,
        ])->registerSortBy($pluginId, [
            '个人中心',
            '种质申请',
            '修改资料',
            '修改密码',
            '双因素认证',
        ]);


        Table::configureUsing(fn(Table $table) => $table->defaultCurrency('CNY'));
        Table::configureUsing(fn(Table $table) => $table->defaultDateDisplayFormat('Y-m-d'));
        Table::configureUsing(fn(Table $table) => $table->defaultDateTimeDisplayFormat('Y-m-d H:i:s'));
        Table::configureUsing(fn(Table $table) => $table->defaultNumberLocale(null));
        Table::configureUsing(fn(Table $table) => $table->defaultTimeDisplayFormat('H:i:s'));

        Schema::configureUsing(fn(Schema $schema) => $schema->defaultCurrency('CNY'));
        Schema::configureUsing(fn(Schema $schema) => $schema->defaultDateDisplayFormat('Y-m-d'));
        Schema::configureUsing(fn(Schema $schema) => $schema->defaultDateTimeDisplayFormat('Y-m-d H:i:s'));
        Schema::configureUsing(fn(Schema $schema) => $schema->defaultNumberLocale(null));
        Schema::configureUsing(fn(Schema $schema) => $schema->defaultTimeDisplayFormat('H:i:s'));

        DateTimePicker::configureUsing(fn(DateTimePicker $dateTimePicker) => $dateTimePicker->defaultDateDisplayFormat('Y-m-d'));
        DateTimePicker::configureUsing(fn(DateTimePicker $dateTimePicker) => $dateTimePicker->defaultDateTimeDisplayFormat('Y-m-d H:i:s'));
        DateTimePicker::configureUsing(fn(DateTimePicker $dateTimePicker) => $dateTimePicker->defaultDateTimeWithSecondsDisplayFormat('Y-m-d H:i:s'));
        DateTimePicker::configureUsing(fn(DateTimePicker $dateTimePicker) => $dateTimePicker->defaultTimeDisplayFormat('H:i'));
        DateTimePicker::configureUsing(fn(DateTimePicker $dateTimePicker) => $dateTimePicker->defaultTimeWithSecondsDisplayFormat('H:i:s'));

        TextEntry::configureUsing(function (TextEntry $entry): void {
            $entry->size(TextSize::Medium)
                ->weight(FontWeight::Bold)
                // ->inlineLabel()
                // ->entryWrapperView('infolists.entry-wrapper')
                ->alignStart();
        });
    }
}
