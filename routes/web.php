<?php

use App\Enums\Navigations\Type as NavigationTypeEnum;
use App\Livewire\Index;
use App\Livewire\Navigation;
use App\Livewire\Posts;
use App\Livewire\Post;
use App\Livewire\Appraise;
use App\Livewire\Personnels;
use App\Livewire\Personnel;
use App\Livewire\User\AppraiseApplies;
use App\Livewire\User\AppraiseApply;
use Illuminate\Support\Facades\Route;
use Filament\Facades\Filament;
use App\Http\Controllers\SsoCallbackController;
use Wsmallnews\Cms\Support\Utils;
use Wsmallnews\Support\Http\Middleware\IdentifyTenant;
use Wsmallnews\Support\Support\Utils as SupportUtils;

// SSO routes (T042) - need session middleware for OAuth state verification
Route::middleware(['web'])->group(function () {
    Route::get('/sso/redirect', [SsoCallbackController::class, 'redirect'])->name('sso.redirect');
    Route::get('/sso/callback', [SsoCallbackController::class, 'callback'])->name('sso.callback');
});

$middlewares = Utils::getConfig('routes.middleware') ?? [];
SupportUtils::isTenancyEnabled() && array_unshift($middlewares, IdentifyTenant::class);

Route::domain(Utils::getConfig('routes.domain'))
    ->middleware($middlewares)
    ->prefix(Utils::getConfig('routes.prefix'))
    ->name(Utils::getConfig('routes.name'))
    ->group(function () {
        Route::get('appraises/{id}', Appraise::class)->name('appraises.show');

        Route::get('personnels', Personnels::class)->name('personnels');
        Route::get('personnels/{id}', Personnel::class)->name('personnels.show');

        // 需登录路由
        Route::middleware('cms-auth:' . Utils::getConfig('guard'))->group(function () {
            // 个人设置
            Route::get('user/appraise-applies', AppraiseApplies::class)->name('user.appraise-applies');
            Route::get('user/appraise-applies/{id}', AppraiseApply::class)->name('user.appraise-applies.show');
        });
    });


Route::get('test', function () {
    $nhgrc = new \App\Features\Nhgrc\Nhgrc();

    // $result = $nhgrc->getClassifications([
    //     'parentId' => 1,
    // ]);
    // dd($result);

    // $result = $nhgrc->getClassificationDetail([
    //     'classId' => 1,
    // ]);
    // dd($result);


    // $result = $nhgrc->getClassificationTree();
    // dd($result);

    $media = Spatie\MediaLibrary\MediaCollections\Models\Media::find(14);
    // dd($media->getFullUrl(), $media->getUrl(), $media->getPath());
    $result = $nhgrc->uploadImage($media);
    dd($result);
});


// Route::prefix("tenant/{tenant:slug}")
//     ->name('tenant.')
//     ->middleware(IdentifyTenant::class)
//     // ->domain()
//     ->group(function () {
//         Route::get('/', Index::class)->name('index');
//         Route::get('/navigation/{slug}', Navigation::class)->name('navigation');

//         Route::get('/posts', Posts::class)->name('posts');
//         Route::get('/posts/{id}', Post::class)->name('posts.show');
//         Route::get('/personnels', Personnels::class)->name('personnels');
//         Route::get('/personnels/{id}', Personnel::class)->name('personnels.show');

//         Route::get('/appraises/{id}', Appraise::class)->name('appraises.show');
//     });


// Route::get('test', function () {
//     // $panel = Filament::getCurrentPanel();
//     // $user = auth()->user();
//     // dd($user->getDefaultTenant($panel));

//     // return 'test';
// });
