<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\ExternalAuth\FacebookAuthController;
use App\Http\Controllers\ExternalAuth\GoogleAuthController;
use App\Http\Controllers\OpenIdController;
use Illuminate\Support\Facades\Route;

Route::get('/.well-known/openid-configuration', [OpenIdController::class, 'discovery'])->name('openid.discovery');
Route::get('/oauth/jwks', [OpenIdController::class, 'jwks'])->name('openid.jwks');

Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);

    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);

    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');

    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('password.store');

    Route::prefix('external-auth')->group(function () {
        Route::get('/facebook', [FacebookAuthController::class, 'redirect'])->name('external-auth.facebook');
        Route::get('/facebook/callback', [FacebookAuthController::class, 'callback'])->name('external-auth.facebook-callback');

        Route::get('/google', [GoogleAuthController::class, 'redirect'])->name('external-auth.google');
        Route::get('/google/callback', [GoogleAuthController::class, 'callback'])->name('external-auth.google-callback');
    });
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

Route::middleware(['auth', 'enabled'])->group(function () {
    Route::get('/verify-email', EmailVerificationPromptController::class)->name('verification.notice');
    Route::get('/verify-email/{id}/{hash}', VerifyEmailController::class)->middleware(['signed', 'throttle:6,1'])->name('verification.verify');
    Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])->middleware('throttle:6,1')->name('verification.send');

    Route::get('/confirm-password', [ConfirmablePasswordController::class, 'show'])->name('password.confirm');
    Route::post('/confirm-password', [ConfirmablePasswordController::class, 'store']);
    Route::put('/password', [PasswordController::class, 'update'])->name('password.update');
});
