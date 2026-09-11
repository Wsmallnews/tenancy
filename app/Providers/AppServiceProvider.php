<?php

namespace App\Providers;

use App\Livewire\Components\AppraiseCategories;
use App\Livewire\Components\Appraises;
use App\Livewire\Components\Index\Featured;
use App\Livewire\Components\Index\Overview;
use App\Livewire\Components\Index\Posts;
use App\Livewire\Components\Index\ScientificResearch;
use App\Livewire\Components\Personnels;
use App\Livewire\Index;
use App\Models\AccurateIdentify;
use App\Models\Appraise;
use App\Models\AppraiseApply;
use App\Models\Assemble;
use App\Models\Award;
use App\Models\AwardType;
use App\Models\Catalog;
use App\Models\Company;
use App\Models\NewVariety;
use App\Models\Patent;
use App\Models\PatentType;
use App\Models\Permission;
use App\Models\Personnel;
use App\Models\PhenotypeIdentify;
use App\Models\Preserve;
use App\Models\ProjectManage;
use App\Models\Role;
use App\Models\Share;
use App\Models\Team;
use App\Models\Thesis;
use App\Models\ThesisType;
use App\Models\User;
use App\Services\SsoProvider;
use BezhanSalleh\FilamentShield\Commands;
use BezhanSalleh\FilamentShield\Facades\FilamentShield;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Assets\Js;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\TextSize;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Facades\FilamentView;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Socialite\Contracts\Factory as SocialiteFactory;
use Livewire\Livewire;
use Spatie\Permission\PermissionRegistrar;
use Wsmallnews\Cms\CmsPlugin;
use Wsmallnews\Cms\Facades\ContentRegistry as ContentRegistryFacade;
use Wsmallnews\Cms\Support\Utils;
use Wsmallnews\Cms\Support\Utils as CmsUtils;
use Wsmallnews\Support\Facades\Search;
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
        View::prependNamespace('sn-cms', resource_path('views/cms-overrides'));

        // 注册自定义 JS 资源
        FilamentAsset::register([
            Js::make('table-scrollable', Vite::asset('resources/js/filament/table-scrollable.js')),
        ]);

        // Register custom SSO Socialite provider
        $socialite = $this->app->make(SocialiteFactory::class);
        $socialite->extend('sso', function ($app) use ($socialite) {
            $config = $app['config']['services.sso'];

            return $socialite->buildProvider(
                SsoProvider::class,
                $config
            );
        });

        app(PermissionRegistrar::class)
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
                    && ! Str::endsWith($entity, 'RoleResource')       // role 权限节点共用，不会出现在同一个 panel, 这里排除特异生成，使用原生规则
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
        FilamentView::spa(true);

        // pages
        Livewire::component('sn-index', Index::class);

        // components
        Livewire::component('sn-components-personnels', Personnels::class);
        Livewire::component('sn-components-personnel', \App\Livewire\Components\Personnel::class);

        Livewire::component('sn-components-appraises', Appraises::class);
        Livewire::component('sn-components-appraise', \App\Livewire\Components\Appraise::class);
        Livewire::component('sn-components-appraise-categories', AppraiseCategories::class);

        Livewire::component('sn-components-user-appraise-apply', \App\Livewire\Components\User\AppraiseApply::class);

        // 首页组件
        Livewire::component('sn-components-index-featured', Featured::class);
        Livewire::component('sn-components-index-overview', Overview::class);
        Livewire::component('sn-components-index-personnels', \App\Livewire\Components\Index\Personnels::class);
        Livewire::component('sn-components-index-posts', Posts::class);
        Livewire::component('sn-components-index-scientific-research', ScientificResearch::class);

        // 注册模型别名
        Relation::enforceMorphMap([
            'accurate_identify' => AccurateIdentify::class,
            'appraise' => Appraise::class,
            'appraise_apply' => AppraiseApply::class,
            'assemble' => Assemble::class,
            'award' => Award::class,
            'award_type' => AwardType::class,
            'catalog' => Catalog::class,
            'company' => Company::class,
            'new_variety' => NewVariety::class,
            'patent' => Patent::class,
            'patent_type' => PatentType::class,
            'personnel' => Personnel::class,
            'phenotype_identify' => PhenotypeIdentify::class,
            'preserve' => Preserve::class,
            'project_manage' => ProjectManage::class,
            'role' => Role::class,
            'share' => Share::class,
            'team' => Team::class,
            'thesis' => Thesis::class,
            'thesis_type' => ThesisType::class,
            'user' => User::class,
        ]);

        // 注册导航内容
        ContentRegistryFacade::registers(CmsUtils::getScopeType(), [
            [
                'type' => 'index-overview',
                'label' => '统计信息(首页)',
                'forms' => fn ($fields) => [],
                'components' => [
                    Overview::class => [
                        'scopeType' => CmsUtils::getScopeType(),
                        'scopeId' => CmsUtils::getScopeId(),
                    ],
                ],
            ],
            [
                'type' => 'index-personnels',
                'label' => '人员列表(首页)',
                'forms' => fn ($fields) => [],
                'components' => [
                    \App\Livewire\Components\Index\Personnels::class => [
                        'scopeType' => CmsUtils::getScopeType(),
                        'scopeId' => CmsUtils::getScopeId(),
                    ],
                ],
            ],
            [
                'type' => 'index-card-posts',
                'label' => '动态资讯(首页)',
                'forms' => fn ($fields) => [],
                'components' => [
                    Posts::class => [
                        'scopeType' => CmsUtils::getScopeType(),
                        'scopeId' => CmsUtils::getScopeId(),
                    ],
                ],
            ],
            [
                'type' => 'index-scientific-research',
                'label' => '科学研究(首页)',
                'forms' => fn ($fields) => [],
                'components' => [
                    ScientificResearch::class => [
                        'scopeType' => CmsUtils::getScopeType(),
                        'scopeId' => CmsUtils::getScopeId(),
                    ],
                ],
            ],
            [
                'type' => 'personnels',
                'label' => '人员列表',
                'forms' => fn ($fields) => [],
                'components' => [
                    Personnels::class => [
                        'scopeType' => CmsUtils::getScopeType(),
                        'scopeId' => CmsUtils::getScopeId(),
                    ],
                ],
            ],
            [
                'type' => 'personnel-detail',
                'label' => '人员详情',
                'forms' => fn ($fields) => [
                    Forms\Components\Select::make('id')->label('选择人员')
                        ->options(Personnel::normal()->limit(30)->pluck('name', 'id'))
                        ->getSearchResultsUsing(fn (string $search): array => Personnel::where('name', 'like', "%{$search}%")->limit(30)->pluck('name', 'id')->toArray())
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
                ],
            ],
            [
                'type' => 'appraise-categories',
                'label' => '种质资源分类',
                'forms' => fn ($fields) => [],
                'components' => [
                    AppraiseCategories::class => [
                        'scopeType' => CmsUtils::getScopeType(),
                        'scopeId' => CmsUtils::getScopeId(),
                    ],
                ],
            ],
            [
                'type' => 'appraise',
                'label' => '种质资源列表',
                'forms' => fn ($fields) => [],
                'components' => [
                    Appraises::class => [
                        'scopeType' => CmsUtils::getScopeType(),
                        'scopeId' => CmsUtils::getScopeId(),
                    ],
                ],
            ],
            [
                'type' => 'appraise-show',
                'label' => '种质资源列表(带分类)',
                'forms' => fn ($fields) => [],
                'components' => [
                    Appraises::class => [
                        'scopeType' => CmsUtils::getScopeType(),
                        'scopeId' => CmsUtils::getScopeId(),
                        'categoryStyle' => 'tree',
                        'style' => 'card',
                    ],
                ],
            ],
            [
                'type' => 'appraise-applies-show',
                'label' => '用种申请列表(带分类)',
                'forms' => fn ($fields) => [],
                'components' => [
                    Appraises::class => [
                        'scopeType' => CmsUtils::getScopeType(),
                        'scopeId' => CmsUtils::getScopeId(),
                        'categoryStyle' => 'tree',
                        'style' => 'list',
                    ],
                ],
            ],
        ]);

        // 注册全局搜索：种质资源来源（注册到 cms 模块，与 post 共用前端搜索框；
        // cms 配置 search.enabled 关闭时不注册来源，前端也不渲染搜索框）
        if (CmsUtils::getConfig('search.enabled', true)) {
            Search::registers(app(CmsPlugin::class)->getId(), [
                [
                    'key' => 'appraise',
                    'model' => Appraise::class,
                    'group' => '种质资源',
                    // 搜索字段同 Appraise::scopeSearch：名称 / 编号 / 科属名 / 学名
                    'fields' => ['name', 'en_name', 'resource_no', 'germplasm_no', 'original_no', 'subject_name', 'genus_name', 'species_name'],
                    // with('saveCompany')：条目视图展示保存单位，预加载避免 N+1
                    'query' => fn ($query) => $query->scopeTenant()->normal()->with('saveCompany'),
                    // 副标题：保存单位（同 appraises 列表条目）
                    'description' => fn ($record) => $record->saveCompany?->name,
                    'url' => fn ($record) => CmsUtils::route('appraises.show', $record->id),
                    // 自定义条目视图（结构参考 appraises 页面 list 样式条目；数据：$result 含 ->record、$query）
                    'view' => 'components.search.appraise-item',
                ],
            ]);
        }

        // 注册用户侧边栏菜单
        $pluginId = app(CmsPlugin::class)->getId();
        SidebarMenuRegistryFacade::register($pluginId, fn () => [
            'key' => 'appraise-applies',
            'label' => '种质申请',
            'url' => Utils::route('user.appraise-applies'),
            'icon' => Heroicon::OutlinedUserGroup,
        ])->registerSortBy($pluginId, [
            '个人中心',
            '种质申请',
            '修改资料',
            '修改密码',
            '双因素认证',
        ]);

        Table::configureUsing(fn (Table $table) => $table->defaultCurrency('CNY'));
        Table::configureUsing(fn (Table $table) => $table->defaultDateDisplayFormat('Y-m-d'));
        Table::configureUsing(fn (Table $table) => $table->defaultDateTimeDisplayFormat('Y-m-d H:i:s'));
        Table::configureUsing(fn (Table $table) => $table->defaultNumberLocale(null));
        Table::configureUsing(fn (Table $table) => $table->defaultTimeDisplayFormat('H:i:s'));

        Schema::configureUsing(fn (Schema $schema) => $schema->defaultCurrency('CNY'));
        Schema::configureUsing(fn (Schema $schema) => $schema->defaultDateDisplayFormat('Y-m-d'));
        Schema::configureUsing(fn (Schema $schema) => $schema->defaultDateTimeDisplayFormat('Y-m-d H:i:s'));
        Schema::configureUsing(fn (Schema $schema) => $schema->defaultNumberLocale(null));
        Schema::configureUsing(fn (Schema $schema) => $schema->defaultTimeDisplayFormat('H:i:s'));

        DateTimePicker::configureUsing(fn (DateTimePicker $dateTimePicker) => $dateTimePicker->defaultDateDisplayFormat('Y-m-d'));
        DateTimePicker::configureUsing(fn (DateTimePicker $dateTimePicker) => $dateTimePicker->defaultDateTimeDisplayFormat('Y-m-d H:i:s'));
        DateTimePicker::configureUsing(fn (DateTimePicker $dateTimePicker) => $dateTimePicker->defaultDateTimeWithSecondsDisplayFormat('Y-m-d H:i:s'));
        DateTimePicker::configureUsing(fn (DateTimePicker $dateTimePicker) => $dateTimePicker->defaultTimeDisplayFormat('H:i'));
        DateTimePicker::configureUsing(fn (DateTimePicker $dateTimePicker) => $dateTimePicker->defaultTimeWithSecondsDisplayFormat('H:i:s'));

        TextEntry::configureUsing(function (TextEntry $entry): void {
            $entry->size(TextSize::Medium)
                ->weight(FontWeight::Bold)
                // ->inlineLabel()
                // ->entryWrapperView('infolists.entry-wrapper')
                ->alignStart();
        });
    }
}
