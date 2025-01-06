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
        $user = Auth::user();
        $posts = Post::orderBy('id', 'desc')->cursorPaginate(10);
        $adminUsers = User::role('user')
            ->where('id', $user->id)
            ->with('admin', 'permissions')
            ->get();
        $permissions = $adminUsers;


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
