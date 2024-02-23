<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function updateAvatar(Request $request)
    {
        $user = Auth::user();

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::delete($user->avatar);
            }

            $avatarPath = $request->file('avatar')->store('avatars', 'public');

            $user->avatar = $avatarPath;
            $user->save();
        }

        return back()->with('avatar', 'You have successfully updated your Profile Image!');
    }

    public function allPosts(Request $request)
    {

        $user = $request->user();
        $allUserPosts = Post::where('user_id', '=', $user->id)->get();
        $sorted = $allUserPosts->sortBy('created_at', SORT_REGULAR, true);

        return view(
            'profile.posts',
            ["user" => $user, "data" => $sorted]
        );
    }

    public function userPosts(Request $request)
    {
        $userId = request('id');
        $user = User::find($userId);
        $followersCount = $user->followers()->count();
        $followingCount = $user->following()->count();
        $postsCount = $user->posts()->count();
        $allUserPosts = Post::where('user_id', '=', $userId)->get();
        $sorted = $allUserPosts->sortBy('created_at', SORT_REGULAR, true);


        return view(
            'profile.posts',
            ["user" => $user, "data" => $sorted, 'followersCount' => $followersCount, 'followingCount' => $followingCount, 'postsCount' => $postsCount,]
        );
    }
}
