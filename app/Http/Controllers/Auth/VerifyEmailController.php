<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;


class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(Request $request): RedirectResponse
    {
        if ($request instanceof EmailVerificationRequest) {
            if ($request->user()->hasVerifiedEmail()) {
                return redirect()->intended(route('dashboard', absolute: false).'?verified=1');
            }

            if ($request->user()->markEmailAsVerified()) {
                event(new Verified($request->user()));
            }

            return redirect()->intended(route('dashboard', absolute: false).'?verified=1');
        }

        $request->validate([
            'otp' => 'required|string|size:6',
        ]);

        $user = $request->user();

        if ($user->email_verification_code === $request->otp && $user->email_verification_code_expires_at->isFuture()) {
            $user->markEmailAsVerified();
            $user->email_verification_code_expires_at = Carbon::now();
            $user->save();
            return redirect()->route('dashboard')->with('status', 'Email verified successfully.');
        }

        return back()->withErrors(['otp' => 'Invalid or expired OTP.']);
    }

    /**
     * Show the OTP verification form.
     */
    public function show(Request $request)
    {
        $user = $request->user();
        $user->generateEmailVerificationCode();
        return Inertia::render('User/Auth/VerifyOtp');
    }


}
