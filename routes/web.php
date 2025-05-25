<?php

use Illuminate\Support\Facades\Route;
use Filament\Facades\Filament;

Route::get('/', function () {
    return view('welcome');
});


Route::get('test', function () {
    // $panel = Filament::getCurrentPanel();
    // $user = auth()->user();
    // dd($user->getDefaultTenant($panel));

    // return 'test';
});
