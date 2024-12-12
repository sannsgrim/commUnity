<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Mail;
use App\Mail\SentOtpMail;
use Illuminate\Support\Str;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        return Inertia::render('User/Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|string|lowercase|email|max:255|unique:'.User::class,
            'password'   => [
                'required',
                'string',
                'min:8', // Minimum 8 characters
                'regex:/[a-z]/', // At least one lowercase letter
                'regex:/[A-Z]/', // At least one uppercase letter
                'regex:/[0-9]/', // At least one numeric character
                'regex:/[!@#$%^&*(),.?":{}|<>]/', // At least one special character
                'confirmed',
            ],
        ]);

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'email_verification_code' => Str::random(6),
            'email_verification_code_expires_at' => now()->addMinutes(10),
        ]);

        // Update profile photo path
        $profilePhotoPath = 'profile-picture/default' . $user->id . '.png';
        $user->update(['profile_photo_path' => $profilePhotoPath]);

        // Copy the default profile image to the new path
        $defaultImagePath = 'default-profile/default.png';
        $newImagePath =  $profilePhotoPath;
        Storage::disk('public')->copy($defaultImagePath, $newImagePath);

        // Update cover photo path
        $profileCoverPath = 'cover-photo/default' . $user->id . '.png';
        $user->update(['cover_photo_path' => $profilePhotoPath]);

        // Copy the default profile image to the new path
        $defaultCoverPath = 'default-cover/default.png';
        $newCoverPath =  $profileCoverPath;
        Storage::disk('public')->copy($defaultCoverPath, $newCoverPath);

        event(new Registered($user));

        Auth::login($user);
//
//        return redirect(route('dashboard', absolute: false));
        Mail::to($user->email)->send(new SentOtpMail($user->email_verification_code));

        return redirect()->route('verification.notice');
    }
}
