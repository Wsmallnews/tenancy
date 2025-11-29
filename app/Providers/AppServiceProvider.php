<?php

namespace App\Providers;

use App\Features\NavigationType;
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
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Str;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

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
        Livewire::component('sn-navigation', \App\Livewire\Navigation::class);
        Livewire::component('sn-posts', \App\Livewire\Posts::class);
        Livewire::component('sn-post', \App\Livewire\Post::class);

        // components
        Livewire::component('sn-components-navigation', \App\Livewire\Components\Navigation::class);
        Livewire::component('sn-components-footer', \App\Livewire\Components\Footer::class);
        Livewire::component('sn-components-index-posts', \App\Livewire\Components\IndexPosts::class);
        Livewire::component('sn-components-posts', \App\Livewire\Components\Posts::class);
        Livewire::component('sn-components-post', \App\Livewire\Components\Post::class);
        Livewire::component('sn-components-personnels', \App\Livewire\Components\Personnels::class);
        Livewire::component('sn-components-personnel', \App\Livewire\Components\Personnel::class);

        Livewire::component('sn-components-appraise-show', \App\Livewire\Components\AppraiseShow::class);
        Livewire::component('sn-components-appraises', \App\Livewire\Components\Appraises::class);
        Livewire::component('sn-components-appraise', \App\Livewire\Components\Appraise::class);
        Livewire::component('sn-components-categories', \App\Livewire\Components\Categories::class);




        // 注册模型别名
        Relation::enforceMorphMap([
            'accurate_identify' => \App\Models\AccurateIdentify::class,
            'activity' => \App\Models\Activity::class,
            'appraise' => \App\Models\Appraise::class,
            'assemble' => \App\Models\Assemble::class,
            'award' => \App\Models\Award::class,
            'award_type' => \App\Models\AwardType::class,
            'catalog' => \App\Models\Catalog::class,
            'category' => \App\Models\Category::class,
            'company' => \App\Models\Company::class,
            'content' => \App\Models\Content::class,
            'navigation' => \App\Models\Navigation::class,
            'new_variety' => \App\Models\NewVariety::class,
            'patent' => \App\Models\Patent::class,
            'patent_type' => \App\Models\PatentType::class,
            'personnel' => \App\Models\Personnel::class,
            'phenotype_identify' => \App\Models\PhenotypeIdentify::class,
            'post' => \App\Models\Post::class,
            'post_category' => \App\Models\PostCategory::class,
            'preserve' => \App\Models\Preserve::class,
            'project_manage' => \App\Models\ProjectManage::class,
            'role' => \App\Models\Role::class,
            'share' => \App\Models\Share::class,
            'team' => \App\Models\Team::class,
            'thesis' => \App\Models\Thesis::class,
            'thesis_type' => \App\Models\ThesisType::class,
            'user' => \App\Models\User::class,
        ]);




        NavigationType::make()->registers([
            [
                'type' => 'posts',
                'label' => '图文列表',
                'forms' => fn($fields) => [
                    // @sn todo 这里需要优化， 明明选了，还是提示字段没填
                    Forms\Components\Select::make('category_ids')->label('选择图文分类')
                        ->options(\App\Models\PostCategory::normal()->whereNull('parent_id')->pluck('name', 'id'))
                        ->getSearchResultsUsing(fn(string $search): array => \App\Models\PostCategory::whereNull('parent_id')->where('name', 'like', "%{$search}%")->limit(30)->pluck('name', 'id')->toArray())
                        // ->getOptionLabelUsing(fn($value): ?string => \App\Models\Post::find($value)?->title)
                        ->placeholder('请选择图文分类')
                        ->multiple()
                        ->searchable()
                        ->preload()
                        ->required(),
                ],
                'components' => [
                    \App\Livewire\Components\Posts::class
                ]
            ],
            [
                'type' => 'post-detail',
                'label' => '图文详情',
                'forms' => fn($fields) => [
                    Forms\Components\Select::make('id')->label('选择图文')
                        ->options(\App\Models\Post::normal()->limit(30)->pluck('title', 'id'))
                        ->getSearchResultsUsing(fn(string $search): array => \App\Models\Post::where('title', 'like', "%{$search}%")->limit(30)->pluck('title', 'id')->toArray())
                        // ->getOptionLabelUsing(fn($value): ?string => \App\Models\Post::find($value)?->title)
                        ->placeholder('请选择图文详情')
                        ->searchable()
                        ->preload()
                        ->required(),
                ],
                'components' => [
                    \App\Livewire\Components\Post::class
                ]
            ],
            [
                'type' => 'personnels',
                'label' => '人员列表',
                'forms' => fn($fields) => [],
                'components' => [
                    \App\Livewire\Components\Personnels::class
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
                    \App\Livewire\Components\Personnel::class
                ]
            ],
            [
                'type' => 'appraise-show',
                'label' => '种质资源列表(带分类)',
                'forms' => fn($fields) => [],
                'components' => [
                    \App\Livewire\Components\AppraiseShow::class
                ]
            ],
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
