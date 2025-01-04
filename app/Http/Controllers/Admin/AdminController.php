<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

class AdminController extends Controller
{
    public function show()
    {
        if (!Auth::check()) {
            return redirect()->route('admin.login');
        }

        return Inertia::render('Admin/MainPage');
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
        return Inertia::render('Admin/AdminUserTable');
    }

    public function showRolePermission()
    {

        return Inertia::render('Admin/RolePermission');
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


}
