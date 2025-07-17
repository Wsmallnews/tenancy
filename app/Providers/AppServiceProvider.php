<?php

namespace App\Providers;

use App\Features\NavigationType;
use App\Models\Permission;
use App\Models\Role;
use Filament\Forms;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
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

        // 注册模型别名
        Relation::enforceMorphMap([
            'activity' => \App\Models\Activity::class,
            'user' => \App\Models\User::class,
            'team' => \App\Models\Team::class,
            'role' => \App\Models\Role::class,
            'thesis' => \App\Models\Thesis::class,
            'thesis_type' => \App\Models\ThesisType::class,
            'award' => \App\Models\Award::class,
            'award_type' => \App\Models\AwardType::class,
            'patent' => \App\Models\Patent::class,
            'patent_type' => \App\Models\PatentType::class,
            'appraise' => \App\Models\Appraise::class,
            'preserve' => \App\Models\Preserve::class,
            'assemble' => \App\Models\Assemble::class,
            'new_variety' => \App\Models\NewVariety::class,
            'category' => \App\Models\Category::class,
            'post' => \App\Models\Post::class,
            'post_category' => \App\Models\PostCategory::class,
            'navigation' => \App\Models\Navigation::class,
            'content' => \App\Models\Content::class,
        ]);




        NavigationType::make()->registers([
            [
                'type' => 'posts',
                'label' => '资讯列表',
                'forms' => fn($fields) => [
                    // @sn todo 这里需要优化， 明明选了，还是提示字段没填
                    Forms\Components\Select::make('category_ids')->label('选择资讯分类')
                        ->options(\App\Models\PostCategory::whereNull('parent_id')->pluck('name', 'id'))
                        ->getSearchResultsUsing(fn(string $search): array => \App\Models\PostCategory::whereNull('parent_id')->where('name', 'like', "%{$search}%")->limit(30)->pluck('name', 'id')->toArray())
                        // ->getOptionLabelUsing(fn($value): ?string => \App\Models\Post::find($value)?->title)
                        ->placeholder('请选择资讯分类')
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
                'label' => '资讯详情',
                'forms' => fn($fields) => [
                    Forms\Components\Select::make('id')->label('选择资讯')
                        ->options(\App\Models\Post::limit(30)->pluck('title', 'id'))
                        ->getSearchResultsUsing(fn(string $search): array => \App\Models\Post::where('title', 'like', "%{$search}%")->limit(30)->pluck('title', 'id')->toArray())
                        // ->getOptionLabelUsing(fn($value): ?string => \App\Models\Post::find($value)?->title)
                        ->placeholder('请选择资讯详情')
                        ->searchable()
                        ->preload()
                        ->required(),
                ],
                'components' => [
                    \App\Livewire\Components\Post::class
                ]
            ],
            // [
            //     'type' => 'lights',
            //     'label' => '生命之光列表',
            //     'forms' => function () {
            //         return [];
            //     },
            //     'components' => [
            //         Lights::class
            //     ],
            // ],
            // [
            //     'type' => 'trustees-light',
            //     'label' => '受托人名单(生命之光)',
            //     'forms' => function () {
            //         return [];
            //     },
            //     'component' => [
            //         Trustees::class => [
            //             'type' => 'light',
            //         ]
            //     ],
            // ],
            [
                'type' => 'mentor-info',
                'label' => '导师简介',
                'forms' => function () {
                    return [];
                },
                'components' => [
                    \App\Livewire\Components\MentorInfo::class
                ],
            ],
        ]);



        \Filament\Tables\Table::$defaultCurrency = 'CNY';
        \Filament\Tables\Table::$defaultDateDisplayFormat = 'Y-m-d';
        \Filament\Tables\Table::$defaultDateTimeDisplayFormat = 'Y-m-d H:i:s';
        \Filament\Tables\Table::$defaultNumberLocale = null;
        \Filament\Tables\Table::$defaultTimeDisplayFormat = 'H:i:s';

        \Filament\Infolists\Infolist::$defaultCurrency = 'CNY';
        \Filament\Infolists\Infolist::$defaultDateDisplayFormat = 'Y-m-d';
        \Filament\Infolists\Infolist::$defaultDateTimeDisplayFormat = 'Y-m-d H:i:s';
        \Filament\Infolists\Infolist::$defaultNumberLocale = null;
        \Filament\Infolists\Infolist::$defaultTimeDisplayFormat = 'H:i:s';

        \Filament\Forms\Components\DateTimePicker::$defaultDateDisplayFormat = 'Y-m-d';
        \Filament\Forms\Components\DateTimePicker::$defaultDateTimeDisplayFormat = 'Y-m-d H:i:s';
        \Filament\Forms\Components\DateTimePicker::$defaultDateTimeWithSecondsDisplayFormat = 'Y-m-d H:i:s';
        \Filament\Forms\Components\DateTimePicker::$defaultTimeDisplayFormat = 'H:i';
        \Filament\Forms\Components\DateTimePicker::$defaultTimeWithSecondsDisplayFormat = 'H:i:s';
    }
}
