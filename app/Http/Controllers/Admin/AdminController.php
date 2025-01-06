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
use Spatie\Permission\Models\Role;

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
        if (!Auth::check()) {
            return redirect()->route('admin.login');
        }

        $adminUsers = User::role('admin')->with('admin','roles')->get();

        return Inertia::render('Admin/AdminUserTable', [
            'adminUsers' => $adminUsers
        ]);

    }

    public function showRolePermission()
    {
        if (!Auth::check()) {
            return redirect()->route('admin.login');
        }

        $adminUsers = User::role('admin')->with('admin', 'permissions')->get();

        return Inertia::render('Admin/RolePermission', [
            'adminUsers' => $adminUsers
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (Auth::validate($request->only('email', 'password'))) {
            $user = User::where('email', $request->email)->with('admin')->first();
            Auth::login($user, $request->has('remember'));
            $request->session()->regenerate();

            if ($user->hasRole('admin')) {
                return redirect()->intended(route('admin.dashboard'));
            } else {
                return redirect()->intended(route('super-admin.dashboard'));
            }
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

    public function deleteAccount(Request $request, $id)
    {
        if (!Auth::check()) {
            return redirect()->route('admin.login');
        }

        $user = User::findOrFail($id);

        $user->delete();

        $adminUsers = User::role('admin')->with('admin', 'permissions')->get();

        if ($request->wantsJson()) {
            return $adminUsers;
        }


        return Inertia::render('Admin/RolePermission', [
            'adminUsers' => $adminUsers
        ]);
    }

    public function updateAccount(Request $request, $id)
    {
        \Log::info('Starting updateAccount for user ID: ' . $id);

        try {
            $validated = $request->validate([
                'username' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'roles' => 'required|array'
            ]);

            \Log::info('Validation passed:', $validated);

            $user = User::findOrFail($id);
            \Log::info('User found:', $user->toArray());

            $user->username = $validated['username'];
            $user->email = $validated['email'];

            $roles = Role::whereIn('name', $validated['roles'])->get();
            \Log::info('Roles to assign:', $roles->toArray());

            $user->roles()->sync($roles->pluck('id'));

            $user->save();
            \Log::info('User updated successfully.');

            $users = User::with('roles')->get();
            return response()->json($users);

        } catch (\Exception $e) {
            \Log::error('Error updating user: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to update user'], 500);
        }
    }


}
