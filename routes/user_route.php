<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\User\DashboardController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


Route::prefix('auth/user')->group(function () {

    Route::middleware(['role:user'])->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'show'])
            ->middleware(['auth', 'verified'])
            ->name('test.dashboard');

        Route::post('/commUnity/post', [PostController::class, 'store'])
            ->middleware(['auth', 'verified'])
            ->name('test.post.store');

    });
});
