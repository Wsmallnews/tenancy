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

        // components
        // Livewire::component('sn-components-navigation', Navigation::class);

        // 注册模型别名
        Relation::enforceMorphMap([
            'user' => \App\Models\User::class,
            'team' => \App\Models\Team::class,
            'thesis' => \App\Models\Thesis::class,
            'thesis_type' => \App\Models\ThesisType::class,
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
