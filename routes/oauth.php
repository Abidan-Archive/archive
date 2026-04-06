<?php

use App\Http\Controllers\Auth\OAuthProviderController;
use Illuminate\Support\Facades\Route;

Route::get('.well-known/openid-configuration', [OAuthProviderController::class, 'discovery'])
    ->middleware(['throttle:oauth:discovery'])
    ->name('oauth.discovery');
Route::prefix('oauth')->name('oauth.')->group(function () {
    Route::get('authorize', [OAuthProviderController::class, 'authorization'])
        ->middleware(['web', 'auth', 'throttle:oauth:authorize'])
        ->name('authorize');
    Route::post('token', [OAuthProviderController::class, 'token'])
        ->middleware(['throttle:oauth:token'])
        ->name('token');
    // OpenID Connect userinfo
    Route::get('userinfo', [OAuthProviderController::class, 'userinfo'])
        ->middleware(['auth:sanctum', 'throttle:oauth:userinfo'])
        ->name('userinfo');
});
