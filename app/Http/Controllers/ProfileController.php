<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use Log;

class ProfileController extends Controller
{
    /**
     * Display the user's profile page.
     */
    public function show(Request $request)
    {
        $posts = Post::where('user_id', Auth::id())->orderBy('id', 'desc')->cursorPaginate(10);

        if ($request->wantsJson()) {
            return PostResource::collection($posts);
        }

        return Inertia::render('User/Profile/ProfilePage', [
            'user' => Auth::user(),
            'posts' => PostResource::collection($posts)
        ]);
    }

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): Response
    {
        return Inertia::render('User/Profile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $user->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        session()->flash('status', 'Profile updated successfully.');

        return Redirect::route('profile.edit');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function updateProfileImage(Request $request): JsonResponse
    {
        $request->validate([
            'profile_image' => ['required', 'image', 'max:10240'],
        ]);

        $user = $request->user();
        $user->updateProfileImage($request->file('profile_image'));

        return response()->json(['profile_photo_path' => $user->profile_photo_path]);
    }

    public function updateCoverImage(Request $request): JsonResponse
    {
        $request->validate([
            'cover_image' => ['required', 'image', 'max:10240'],
        ]);

        $user = $request->user();
        $user->updateCoverImage($request->file('cover_image'));

        return response()->json(['cover_photo_path' => $user->cover_photo_path]);
    }
}
