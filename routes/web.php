<?php

use App\Http\Controllers\PostCommentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReplyCommentController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('test.dashboard');
    }

    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
    ]);
})->name('welcome');

Route::get('/dashboard', function () {
    return Inertia::render('User/Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/me/profile_page', [ProfileController::class, 'show'])->name('profile.show');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/posts/{post}/upvote', [PostController::class, 'upvote'])->name('post.up_vote.trigger');

    Route::post('/posts/{post}/downvote', [PostController::class, 'downvote'])->name('post.down_vote.trigger');

    Route::post('/comments', [PostCommentController::class, 'store'])->name('comments.store');

    Route::post('/replies', [ReplyCommentController::class, 'store'])->name('replies.store');

});

require __DIR__.'/auth.php';
require __DIR__.'/user_route.php';
