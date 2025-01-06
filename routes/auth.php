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
use App\Http\Controllers\TwoFactorAuthController;
use App\Http\Middleware\CheckTemporaryAuth;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('register');

    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');
});

// Two-Factor Authentication Routes
// 2FA routes - only needs basic auth
Route::middleware([CheckTemporaryAuth::class])->group(function () {
    Route::get('/two-factor-challenge', [TwoFactorAuthController::class, 'show'])
        ->name('user.two-factor.challenge');
    Route::post('/two-factor-challenge', [TwoFactorAuthController::class, 'store'])
        ->name('user.two-factor.store');
    Route::get('/two-factor-recovery', [TwoFactorAuthController::class, 'showRecoveryCode'])
        ->name('user.two-factor.recovery');
    Route::post('/two-factor-recovery', [TwoFactorAuthController::class, 'storeRecoveryCode'])
        ->name('user.two-factor.recovery.store');
});

Route::middleware('auth')->group(function () {

//    Route::get('verify-email', EmailVerificationPromptController::class)
//        ->name('verification.notice');
//
//    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
//        ->middleware(['signed', 'throttle:6,1'])
//        ->name('verification.verify');


    Route::get('verify-email', [VerifyEmailController::class, 'show'])->name('verification.notice');
    Route::post('verify-email', [VerifyEmailController::class, '__invoke'])->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store'])
        ->name('password.confirmation');

    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});
