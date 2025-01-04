<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


Route::prefix('auth/admin')->group(function () {

    Route::get('/dashboard', [AdminController::class, 'show'])->name('admin.dashboard');

    Route::get('/login', [AdminController::class, 'showLogin'])->name('admin.login');

    Route::get('/user-list', [AdminController::class, 'showUserList'])->name('admin.view_user');
});
