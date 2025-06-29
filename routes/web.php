<?php

use App\Enums\Navigations\Type as NavigationTypeEnum;
use App\Livewire\Index;
use Illuminate\Support\Facades\Route;
use Filament\Facades\Filament;

Route::get('/', Index::class)->name('index');

Route::get('test', function () {
    // $panel = Filament::getCurrentPanel();
    // $user = auth()->user();
    // dd($user->getDefaultTenant($panel));

    // return 'test';
});
