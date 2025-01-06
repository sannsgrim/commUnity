<?php

namespace App\Http\Controllers\Auth;

use App\Helper\EncryptionHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\TrustedDevice;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): Response
    {
        return Inertia::render('User/Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $users = User::all();

        foreach ($users as $user) {
            if ($user->email === $request->email && Hash::check($request->password, $user->password)) {
                $deviceId = $this->generateDeviceId($request);

                // Check if the device is trusted
                $trustedDevice = TrustedDevice::where('user_id', $user->id)
                    ->where('device_id', $deviceId)
                    ->first();

                if ($user->two_factor_secret && !$trustedDevice) {
                    // Temporarily store the user ID in the session
                    session(['auth.temp_user_id' => $user->id]);
                    return redirect()->route('user.two-factor.challenge');
                }

                // Create session if 2FA is not enabled or device is trusted
                Auth::login($user, $request->boolean('remember'));
                $request->session()->regenerate();

                // Add success message
                $request->session()->flash('login_success', 'Login successful! Welcome back.');

                return redirect()->intended(route('dashboard'));
            }
        }

        return back()->withErrors(['email' => 'Invalid credentials']);
    }

    private function generateDeviceId($request)
    {
        return hash('sha256', $request->ip() . $request->header('User-Agent'));
    }


    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
