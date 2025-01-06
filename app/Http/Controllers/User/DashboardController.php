<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function show(Request $request)
    {
        $posts = Post::orderBy('id', 'desc')->cursorPaginate(10);
        $user = User::find($request->user_id);
        $permissions = $user ? $user->getAllPermissions()->pluck('name') : collect();


        if ($request->wantsJson()) {
            return response()->json([
                'posts' => PostResource::collection($posts),
                'permissions' => $permissions,
            ]);
        }

        return inertia::render('User/TestDashboard', [
            'posts' => PostResource::collection($posts),
            'permissions' => $permissions,
        ]);
    }
}
