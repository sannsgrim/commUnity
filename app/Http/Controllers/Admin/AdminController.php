<?php

namespace App\Http\Controllers\Admin;

use App\Helper\EncryptionHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Models\TrustedDevice;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
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

        $adminUsers = User::role('user')->with('admin','roles')->get();

        return Inertia::render('Admin/AdminUserTable', [
            'adminUsers' => $adminUsers
        ]);

    }

    public function showRolePermission()
    {
        if (!Auth::check()) {
            return redirect()->route('admin.login');
        }

        $adminUsers = User::role('user')->with('admin', 'permissions')->get();

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
        $users = User::all();

        foreach ($users as $user) {
            if ($user->email === $request->email && Hash::check($request->password, $user->password)) {
                $deviceId = $this->generateDeviceId($request);

                if ($user->two_factor_secret) {
                    // Temporarily store the user ID in the session
                    session(['auth.temp_user_id' => $user->id]);
                    return redirect()->route('user.two-factor.challenge');
                }


                Auth::login($user, $request->boolean('remember'));
                $request->session()->regenerate();

                if ($user->hasRole('admin')) {
                    return redirect()->intended(route('admin.dashboard'));
                }
                return redirect()->intended(route('super-admin.dashboard'));
            }
        }
        return back()->withErrors(['email' => 'The provided credentials do not match our records.']);
    }

    private function generateDeviceId($request)
    {
        return hash('sha256', $request->ip() . $request->header('User-Agent'));
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
                'email' => 'required|email|max:255',
            ]);

            \Log::info('Validation passed:', $validated);

            $user = User::findOrFail($id);
            \Log::info('User found:', $user->toArray());

            $user->email = EncryptionHelper::encrypt($validated['email'], 'commUnity');

            $user->save();
            \Log::info('User updated successfully.');

            $users = User::with('roles')->get();
            return response()->json($users);

        } catch (\Exception $e) {
            \Log::error('Error updating user: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to update user'], 500);
        }
    }

    public function updatePermissions(Request $request, $id)
    {
        Log::info('Starting updatePermissions for user ID: ' . $id);

        try {
            $validated = $request->validate([
                'permission' => 'required|string',
                'hasPermission' => 'required|boolean',
            ]);

            Log::info('Validation passed:', $validated);

            $user = User::findOrFail($id);
            Log::info('User found:', $user->toArray());

            if ($validated['hasPermission']) {
                $user->givePermissionTo($validated['permission']);
            } else {
                $user->revokePermissionTo($validated['permission']);
            }

            Log::info('Permission updated successfully.');

            return response()->json(['success' => 'Permission updated successfully.']);

        } catch (\Exception $e) {
            Log::error('Error updating permission: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to update permission'], 500);
        }
    }

}
