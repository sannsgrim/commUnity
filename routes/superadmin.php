<?php

use App\Http\Controllers\SuperAdmin\SuperAdminController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


Route::prefix('auth/super-admin')->group(function () {

    Route::get('/dashboard', [SuperAdminController::class, 'show'])->name('super-admin.dashboard');

    Route::get('/login', [SuperAdminController::class, 'showLogin'])->name('super-admin.login');

    Route::post('/login', [SuperAdminController::class, 'login'])->name('super-admin.login.submit');

    Route::get('/user-list', [SuperAdminController::class, 'showUserList'])->name('super-admin.view_user');

    Route::get('/role-list', [SuperAdminController::class, 'showRolePermission'])->name('super-admin.view_permission');
});
