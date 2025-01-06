<?php

namespace App\Http\Controllers;

use App\Models\TrustedDevice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use PragmaRX\Google2FA\Google2FA;

class TwoFactorAuthController extends Controller
{
    protected Google2FA $google2fa;

    public function __construct()
    {
        $this->google2fa = new Google2FA();
    }

    public function show()
    {
        if (!Auth::check() || !Auth::user()->hasRole('user')) {
            return redirect()->route('login');
        }

        return Inertia::render('User/Auth/TwoFactorChallenge');
    }

    public function showRecoveryCode()
    {
        if (!Auth::check() || !Auth::user()->hasRole('user')) {
            return redirect()->route('login');
        }

        // Add the recovery path to your middleware's publicPaths
        return Inertia::render('User/Auth/RecoveryCode');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6',
        ]);

        $user = Auth::user();

        if (!$user || !$this->verify2FACode($user, $request->code)) {
            throw ValidationException::withMessages([
                'code' => ['Invalid authentication code.'],
            ]);
        }

        // Set the session key to indicate 2FA verification
        session(['auth.2fa_verified' => time()]);


        // Fully authenticate the user and create a session
        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        return redirect()->intended(route('test.dashboard'));
    }

    private function generateDeviceId($request)
    {
        return hash('sha256', $request->ip() . $request->header('User-Agent'));
    }

    public function storeRecoveryCode(Request $request)
    {
        $request->validate([
            'recovery_code' => 'required|string',
        ]);

        $user = Auth::user();

        if (!$user || !$user->hasRole('user')) {
            throw ValidationException::withMessages([
                'recovery_code' => ['Invalid authentication state.'],
            ]);
        }

        if (!$this->verifyRecoveryCode($user, $request->recovery_code)) {
            throw ValidationException::withMessages([
                'recovery_code' => ['Invalid recovery code.'],
            ]);
        }

        // Fully authenticate the user and create a session
        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        return redirect()->intended(route('test.dashboard'));
    }

    protected function verify2FACode($user, $code)
    {
        try {
            return $this->google2fa->verifyKey(
                decrypt($user->two_factor_secret),
                $code,
                2 // Window of 2 to allow for slight time differences
            );
        } catch (\Exception $e) {
            return false;
        }
    }

    protected function verifyRecoveryCode($user, $recoveryCode)
    {
        if (!$user->two_factor_recovery_codes) {
            return false;
        }

        try {
            $recoveryCodes = json_decode(decrypt($user->two_factor_recovery_codes), true);

            // Find and remove the used recovery code
            $position = array_search($recoveryCode, $recoveryCodes);

            if ($position !== false) {
                unset($recoveryCodes[$position]);
                $user->two_factor_recovery_codes = encrypt(json_encode(array_values($recoveryCodes)));
                $user->save();
                return true;
            }

            return false;
        } catch (\Exception $e) {
            return false;
        }
    }
}
