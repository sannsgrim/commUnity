<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

class AdminController extends Controller
{
    public function show(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('admin.login');
        }

        $posts = Post::orderBy('id', 'desc')->cursorPaginate(10);

        if ($request->wantsJson()) {
            return PostResource::collection($posts);
        }

        return Inertia::render('Admin/MainPage', [

            'posts' => PostResource::collection($posts)

        ]);
    }

    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }
        return Inertia::render('Admin/AdminLogin');
    }
    public function showUserList()
    {
        $adminUsers = User::role('admin')->with('admin','roles')->get();

        return Inertia::render('Admin/AdminUserTable', [
            'adminUsers' => $adminUsers
        ]);
    }

    public function showRolePermission()
    {
        $adminUsers = User::role('admin')->with('admin', 'permissions')->get();

        return Inertia::render('Admin/RolePermission', [
            'adminUsers' => $adminUsers
        ]);
    }

    public function login(Request $request){
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if(Auth::validate($request->only('email', 'password'))){
            $user = User::where('email', $request->email)->with('admin')->first();
            Auth::login($user, $request->has('remember'));
            $request->session()->regenerate();

            return redirect()->intended(route('admin.dashboard'));
        }
        return back()->withErrors(['email' => 'The provided credentials do not match our records.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }
}
