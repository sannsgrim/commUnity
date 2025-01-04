<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminController extends Controller
{
    public function show()
    {
        return Inertia::render('Admin/MainPage');
    }

    public function showLogin()
    {
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
}
