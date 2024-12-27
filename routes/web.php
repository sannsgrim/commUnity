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

    //Post Vote
    Route::post('/posts/{post}/upvote', [PostController::class, 'upvote'])->name('post.up_vote.trigger');

    Route::post('/posts/{post}/downvote', [PostController::class, 'downvote'])->name('post.down_vote.trigger');

    // Comment Vote
    Route::post('/posts/{post}/comments/{comment}/upvote', [PostCommentController::class, 'upvote'])->name('comment.up_vote.trigger');

    Route::post('/posts/{post}/comments/{comment}/downvote', [PostCommentController::class, 'downvote'])->name('comment.down_vote.trigger');

    //Reply Vote
    Route::post('/posts/{post}/reply_comments/{comment}/upvote', [ReplyCommentController::class, 'upvote'])->name('reply_comment.up_vote.trigger');

    Route::post('/posts/{post}/reply_comments/{comment}/downvote', [ReplyCommentController::class, 'downvote'])->name('reply_comment.down_vote.trigger');


    Route::post('/comments', [PostCommentController::class, 'store'])->name('comments.store');

    Route::post('/replies', [ReplyCommentController::class, 'store'])->name('replies.store');

});

require __DIR__.'/auth.php';
require __DIR__.'/user_route.php';
