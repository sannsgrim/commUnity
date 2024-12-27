<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function show(Request $request)
    {
        $posts = Post::orderBy('id', 'desc')->cursorPaginate(10);

        if ($request->wantsJson()) {
            return PostResource::collection($posts);
        }

        return inertia::render('User/TestDashboard', [

            'posts' => PostResource::collection($posts)

        ]);
    }
}
