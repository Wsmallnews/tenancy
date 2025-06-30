<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use App\Models\Permission;
use App\Models\Role;
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

        // components
        Livewire::component('sn-components-navigation', \App\Livewire\Components\Navigation::class);
        Livewire::component('sn-components-footer', \App\Livewire\Components\Footer::class);

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
            'navigation' => \App\Models\Navigation::class,
            'content' => \App\Models\Content::class,
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
