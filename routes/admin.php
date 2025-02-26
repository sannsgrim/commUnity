<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


Route::prefix('auth/admin')->group(function () {


    Route::get('/dashboard', [AdminController::class, 'show'])->name('admin.dashboard');

    Route::get('/login', [AdminController::class, 'showLogin'])->name('admin.login');

    Route::post('/login', [AdminController::class, 'login'])->name('admin.login.submit');

    Route::get('/user-list', [AdminController::class, 'showUserList'])->name('admin.view_user');

    Route::get('/role-list', [AdminController::class, 'showRolePermission'])->name('admin.view_permission');

    Route::post('/admin/users/{id}', [AdminController::class, 'deleteAccount'])->name('admin.deleteAccount');

    Route::post('/logout', [AdminController::class, 'logout'])->name('admin.logout');

    Route::post('/users/{id}/permissions', [AdminController::class, 'updatePermissions'])->name('admin.updatePermissions');

    Route::post('/users/{id}/update', [AdminController::class, 'updateAccount'])->name('admin.updateAccount');

});
