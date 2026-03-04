<?php

use App\Http\Controllers\Api\SsoVerifyController;
use Illuminate\Support\Facades\Route;

Route::post('/sso/verify-credentials', [SsoVerifyController::class, 'verifyCredentials'])
    ->middleware('sso.api-key');
