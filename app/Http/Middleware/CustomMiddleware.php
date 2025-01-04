<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Middleware\RoleMiddleware;

class CustomMiddleware extends RoleMiddleware
{

    protected $auth;

    public function __construct()
    {
        $this->auth = Auth::guard();
    }

    public function handle($request, Closure $next, $role, $guard = null)
    {
        if (!$this->auth->check() || !$this->auth->user()->hasRole($role)) {
            $userRole = $this->auth->user()->roles->first()->name;

            return match ($userRole) {
                'admin' => redirect()->route('admin.dashboard'),
                'user' => redirect()->route('test.dashboard'),
                default => redirect()->route('welcome'),
            };
        }

        return $next($request);
    }

}
