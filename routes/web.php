<?php

use App\Enums\Navigations\Type as NavigationTypeEnum;
use App\Livewire\Index;
use App\Livewire\Navigation;
use App\Livewire\Posts;
use App\Livewire\Post;
use App\Http\Middleware\IdentifyTenant;
use Illuminate\Support\Facades\Route;
use Filament\Facades\Filament;

Route::prefix("tenant/{tenant:slug}")
    ->name('tenant.')
    ->middleware(IdentifyTenant::class)
    // ->domain()
    ->group(function () {
        Route::get('/', Index::class)->name('index');
        Route::get('/navigation/{slug}', Navigation::class)->name('navigation');

        Route::get('/posts', Posts::class)->name('posts');
        Route::get('/posts/{id}', Post::class)->name('posts.show');
    });


// Route::get('test', function () {
//     // $panel = Filament::getCurrentPanel();
//     // $user = auth()->user();
//     // dd($user->getDefaultTenant($panel));

//     // return 'test';
// });
