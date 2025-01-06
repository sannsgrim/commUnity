<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckTemporaryAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        // Retrieve the temporary user ID from the session
        $tempUserId = session('auth.temp_user_id');

        if ($tempUserId) {
            $user = User::find($tempUserId);
            Auth::setUser($user);

            // Check if the user is set but not fully authenticated
            if (Auth::user() && !session()->has('auth.2fa_verified')) {
                return $next($request);
            }
        }

        return redirect()->route('login');
    }
}
